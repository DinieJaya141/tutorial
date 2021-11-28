<?php
//namespace ;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
//use Cart;

class CartController extends ControllerBase
{

    public function indexAction()
    {
        if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }

        $this->persistent->parameters = null;
    }

    public function addtocartAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('');
        }

        $cart_contents = new CartContents();
        $cart_contents->cart_id = $this->request->getPost('cartId');
        $cart_contents->quantity = 1;

        if ($this->request->getPost('itemType') == 'ticket') {
            $cart_contents->ticket_id = $this->request->getPost('itemId');
            $cart_contents->item_type = $this->request->getPost('itemType');
        } else if ($this->request->getPost('itemType') == 'merchandise') {
            $cart_contents->merchandise_id = $this->request->getPost('itemId');
            $cart_contents->item_type = $this->request->getPost('itemType');
        } else {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'error');
            $this->flashSession->notice('Something went wrong. Try again.');
            return $this->response->redirect('tickets');
        }

        if (!$cart_contents->save()) {
            foreach ($cart_contents->getMessages() as $message) {
                $this->flashSession->notice($message);
            }
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'error');
            return $this->response->redirect('tickets');
        }

        if ($this->request->getPost('itemType') == 'ticket') {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('Item added to cart.');
            return $this->response->redirect('tickets');
        } else if ($this->request->getPost('itemType') == 'merchandise') {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('Item added to cart.');
            return $this->response->redirect('merchandise');
        } else {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'error');
            $this->flashSession->notice('Something went wrong. Try again.');
            return $this->response->redirect('cart');
        }
    }

    public function updateAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $action = $this->request->getPost('action');

        $cart_contents = CartContents::findFirst(
            [
                "cart_id = :cart_id: AND item_type = :item_type: AND (ticket_id = :item_id: OR merchandise_id = :item_id:)",
                'bind' => [
                    'cart_id'   => $this->request->getPost('cartId'),
                    'item_id'   => $this->request->getPost('itemId'),
                    'item_type' => $this->request->getPost('itemType'),
                ]
            ]
        );

        if ($cart_contents && ($action == "update")) {
            $cart_contents->setQuantity((int)$this->request->getPost('quantity'));
            $cart_contents->save();
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'success');
            $this->flashSession->notice('Item quantity updated.');
            return $this->response->redirect("cart");
        } else if ($cart_contents && ($action == 'delete')) {
            $cart_contents->delete();
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'success');
            $this->flashSession->notice('Item removed successfully.');
            return $this->response->redirect('cart');
        } else {
            return $this->response->redirect('cart');
        }
    }

    public function bookdateAction() 
    {
        if (!$this->request->isPost()) {
            if (!$this->session->has('auth')) {
                return $this->response->redirect();
            } else {
                return $this->response->redirect('cart');
            }
        }

        $this->session->set('book_date', $this->request->getPost('date'));
        $this->session->set('flash', TRUE);
        $this->session->set('flash_type', 'success');
        $this->flashSession->notice('Ticket pick up date booked successfully.');
        return $this->response->redirect('cart');
    }

    public function summaryAction()
    {
        if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }

        $user = $this->session->get('user');
        $inventory = $user->Cart->Contents;

        if ($inventory->count() <= 0) {
            return $this->response->redirect('cart');
        }
    }

    public function checkoutAction()
    {
        if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }

        $user =$this->session->get('user');
        $inventory = $user->Cart->Contents;

        if ($inventory->count() <= 0) {
            return $this->response->redirect('cart');
        }

        $cart_contents = CartContents::find(
            [
                "cart_id = :cart_id:",
                'bind' => [
                    'cart_id'   => $user->Cart->id,
                ]
            ]
        );

        $details = "";
        $iteration = 0;

        foreach ($cart_contents as $item) {
            $postId = 'name' . $iteration;
            $costId = 'cost' . $iteration;
            $details .= strtoupper($item->item_type) . "{";
            $details .= $this->request->getPost($postId) . "{";
            $details .= $item->quantity . "{";
            $details .= $this->request->getPost($costId) . "|";
            $iteration++;
        }

        $cart_contents->delete();

        $order = new Orders();
        $order->user_id = $user->id;
        $order->purchase_date = date("Y-m-d");
        $order->book_date = $this->session->get('book_date');
        $order->total_cost = $this->request->getPost('totalcost');
        $order->discount = $this->request->getPost('discount');
        $order->details = $details;
        if (!$order->save()) {
            $this->session->set('book_date', '');
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Database error occurred. Order history not updated.');
            return;
        }

        $this->session->set('book_date', '');

        if (count($this->session->get('discount_codes')) > 0) {
            foreach ($this->session->get('discount_codes') as $code) {
                $promo = Promo::findFirstByCode($code);
                $promo_record = new PromoRecords();
                $promo_record->user_id = ($this->session->get('user'))->id;
                $promo_record->promo_id = $promo->id;
                $promo_record->save();
            }
            $this->session->set('discount_codes', []);
            $this->session->set('discount_rate', 1);
        }

        TestMailer::composeCheckoutMail($this->session->get('email'), $order);
    }

}
