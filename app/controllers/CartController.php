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
            return $this->response->redirect("");
        }

        $this->persistent->parameters = null;

        $cart_exist = Cart::findFirst(
                [
                    "user_id = :user_id:",
                    'bind' => [
                        'user_id' => $this->session->get('userid'),
                    ]
                ]
            );
        
        if (!$cart_exist) {
            $cart = new Cart();
            $cart->contents = " ";
            $cart->user_id = $this->session->get('userid');
            $cart->save();
        }
    }

    public function addtocartAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $cart_contents = new CartContents();
        $cart_contents->cart_id = $this->request->getPost("cartId");
        $cart_contents->item_id = $this->request->getPost("itemId");
        $cart_contents->item_type = $this->request->getPost("itemType");
        $cart_contents->quantity = 1;

        if (!$cart_contents->save()) {
            foreach ($cart_contents->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->response->redirect("tickets");
        }
        return $this->response->redirect("tickets");
    }

    public function updateAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $action = $this->request->getPost("action");

        $cart_contents = CartContents::findFirst(
            [
                "cart_id = :cart_id: AND item_id = :item_id: AND item_type = :item_type:",
                'bind' => [
                    'cart_id'      => $this->request->getPost("cartId"),
                    'item_id'   => $this->request->getPost("itemId"),
                    'item_type' => $this->request->getPost("itemType"),
                ]
            ]
        );

        if ($cart_contents && ($action == "update")) {
            $cart_contents->setQuantity((int)$this->request->getPost("quantity"));
            $cart_contents->save();
            return $this->response->redirect("cart");
        } else if ($cart_contents && ($action == "delete")) {
            $cart_contents->delete();
            return $this->response->redirect("cart");
        } else {
            return $this->response->redirect("cart");
        }
    }

}
