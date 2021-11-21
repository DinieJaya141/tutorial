<?php
//namespace ;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
//use Orders;

class OrdersController extends ControllerBase
{

    public function indexAction()
    {
    	if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }
    }

}
