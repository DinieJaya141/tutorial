<?php

class Tickets extends \Phalcon\Mvc\Model
{

    public $id; //int
    public $name; //str
    public $details; //str
    public $type; //str
    public $price; //double
    public $quantity; //int

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("tutorial");
        $this->setSource("Tickets");
        $this->hasMany(
            'id',
            'CartContents',
            'ticket_id',
            array('foreignKey' => TRUE, 'alias' => 'Contents')
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Tickets';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tickets[]|Tickets|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tickets|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
