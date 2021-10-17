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

}
