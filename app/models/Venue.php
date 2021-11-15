<?php

class Venue extends \Phalcon\Mvc\Model
{

    public $id; //int
    public $title; //str
    public $details; //str
    public $choices; //str
    public $image; //str

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("tutorial");
        $this->setSource("venue");
        $this->hasMany(
            'id',
            'VenueBookings',
            'venue_id',
            array('foreignKey' => TRUE, 'alias' => 'VenueBookings')
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'venue';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Venue[]|Venue|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Venue|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
