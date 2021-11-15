<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class VenueBooking extends \Phalcon\Mvc\Model
{

    public $id; //int
    public $venue_id; //int
    public $name; //str
    public $email; //str
    public $contact; //str
    public $agenda; //str
    public $choice; //str
    public $date; //str

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("tutorial");
        $this->setSource("venue_booking");
        $this->belongsTo('venue_id', 'Venue', 'id', ['alias' => 'Venue']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'venue_booking';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return VenueBooking[]|VenueBooking|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return VenueBooking|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
