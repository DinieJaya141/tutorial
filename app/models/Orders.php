<?php

class Orders extends \Phalcon\Mvc\Model
{

    public $id; //int
    public $user_id; //int
    public $purchase_date; //str(date)
    public $book_date; //str(date)
    public $total_cost; //double
    public $details; //str

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("tutorial");
        $this->setSource("orders");
        $this->belongsTo('user_id', 'Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'orders';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders[]|Orders|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function listUserOrders($userid)
    {
        $orders = Orders::find(
            [
                "user_id = :user_id:",
                'bind' => [
                    'user_id'   => $userid,
                ],
                'order'  => 'id DESC',
            ]
        );

        return $orders;
    }

}
