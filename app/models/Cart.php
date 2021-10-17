<?php

class Cart extends \Phalcon\Mvc\Model
{

    public $id; //int
    public $user_id; //int
    
    public function initialize()
    {
        $this->setSchema("tutorial");
        $this->setSource("cart");
        $this->belongsTo('user_id', 'Users', 'id', ['alias' => 'Users']);
        $this->hasMany('cart_id', 'Cart', 'id', ['alias' => 'Cart']);
        $this->hasMany(
            'id',
            'CartContents',
            'cart_id',
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
        return 'cart';
    }

    public function setContents($contents) {
        $this->contents = $contents;
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cart[]|Cart|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cart|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function getInventory($cart_contents)
    {
        if (strlen($cart_contents) < 3) {
            return 0;
        }

        $contents = explode(",", $cart_contents);

        $iteration = 0;
        $key;
        $inventory;

        foreach ($contents as $item) {
            if (($iteration % 2) == 0) {
                $inventory[$item] = 0;
                $key = $item;
            } else {
                $inventory[$key] = $item;
            }
            $iteration++;
        }

        return $inventory;
    }

    public static function getItemIds($cart_contents)
    {
        $contents = [];
        foreach ($cart_contents as $item) {
            $contents[] = $item->item_id;
        }
        return $contents;
    }

}
