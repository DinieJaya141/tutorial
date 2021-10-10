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

    public function updateAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $item_id = (int)$this->request->getPost("itemId");
        $cart = Cart::findFirstByid($this->request->getPost("cartId"));
        $action = $this->request->getPost("action");

        $original_contents = explode(",", $cart->contents);

        $iteration = 0;
        $found = FALSE;
        $updated_contents = "";

        foreach ($original_contents as $value) {
            if (($iteration % 2) == 0) {
                if ((int)$value == $item_id) {
                    $found = TRUE;
                    if ($action === "delete") {
                        $iteration++;
                        continue;
                    }
                }
                $updated_contents = $updated_contents . $value . ",";
            } else {
                if ($found) {
                    $found = FALSE;
                    if ($action === "delete") {
                        $iteration++;
                        continue;
                    }
                    $updated_contents = $updated_contents . (int)$this->request->getPost("quantity") . ",";
                } else {
                    $updated_contents = $updated_contents . $value . ",";
                }
            }
            $iteration++;
        }

        if (strlen($updated_contents) > 0) {
            $updated_contents = substr($updated_contents, 0, -1);
        } else {
            $updated_contents = " ";
        }

        $cart->setContents($updated_contents);
        $cart->save();

        return $this->response->redirect("cart");
    }

}
