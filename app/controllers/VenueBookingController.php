<?php
//namespace ;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
//use VenueBookings;

class VenueBookingController extends ControllerBase
{

    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    public function inquiryAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('venuebooking');
        }
    }

    public function submitAction()
    {
    	if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $venue_booking = new VenueBooking();
        $venue_booking->venue_id = $this->request->getPost('venueIdInput');
        $venue_booking->name = $this->request->getPost('nameInput');
        $venue_booking->email = $this->request->getPost("emailInput", "email");
        $venue_booking->contact = $this->request->getPost('contactInput');
        $venue_booking->agenda = $this->request->getPost('agendaInput');
        $venue_booking->choice = $this->request->getPost('venueInput');
        $venue_booking->date = $this->request->getPost('dateInput');

        if (!$venue_booking->save()) {
            foreach ($venue_booking->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "venuebooking/inquiry",
                'action' => 'index'
            ]);

            return;
        }
    }

}
