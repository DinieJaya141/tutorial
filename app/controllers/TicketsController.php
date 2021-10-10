<?php
//namespace ;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
//use Tickets;

class TicketsController extends ControllerBase
{

    public function indexAction()
    {
        if (!$this->session->has('auth')) {
            return $this->response->redirect("");
        }

        $this->persistent->parameters = null;
    }

    public function addtocartAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $ticket_id = (int)$this->request->getPost("itemId");
        $cart = Cart::findFirstByid($this->request->getPost("cartId"));

        if ($cart->contents == " ") {
            $updated_contents = $ticket_id . ",1";
        } else {
            $updated_contents = $cart->contents . "," . $ticket_id . ",1";
        }

        $cart->setContents($updated_contents);
        $cart->save();

        return $this->response->redirect("tickets");
    }

}
