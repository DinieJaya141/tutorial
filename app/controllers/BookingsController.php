<?php
//namespace ;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
//use Bookings;

class BookingsController extends ControllerBase
{
    public function createAction()
    {
        $booking = new Bookings();
        $booking->user_id = $this->request->getPost("userId");
        $booking->date = $this->request->getPost("date");

        if (!$booking->save()) {
            foreach ($booking->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->response->redirect("cart");
        }
        return $this->response->redirect("cart");
    }

}
