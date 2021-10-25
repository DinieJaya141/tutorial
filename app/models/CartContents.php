<?php

class CartContents extends \Phalcon\Mvc\Model
{

    public $id; //int
    public $cart_id; //int
    public $ticket_id; //int
    public $merchandise_id; //int
    public $item_type; //str
    public $quantity; //int

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("tutorial");
        $this->setSource("cart_contents");
        $this->belongsTo('cart_id', 'Cart', 'id', ['alias' => 'Cart']);
        $this->belongsTo('ticket_id', 'Tickets', 'id', ['alias' => 'Tickets']);
        $this->belongsTo('merchandise_id', 'Merchandise', 'id', ['alias' => 'Merchandise']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cart_contents';
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CartContents[]|CartContents|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CartContents|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
