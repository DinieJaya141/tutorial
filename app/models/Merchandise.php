<?php

class Merchandise extends \Phalcon\Mvc\Model
{

    public $id; //int
    public $name; //string
    public $details; //string
    public $price; //double
    public $image; //string

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("tutorial");
        $this->setSource("merchandise");
        $this->hasMany(
            'id',
            'CartContents',
            'merchandise_id',
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
        return 'merchandise';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Merchandise[]|Merchandise|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Merchandise|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
