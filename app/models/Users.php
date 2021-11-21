<?php

use Phalcon\Validation;
use Phalcon\Mvc\Model\Relation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Users extends \Phalcon\Mvc\Model
{

    public $id; //int
    public $email; //str
    public $password; //str
    public $username; //str
    public $contact; //int

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
        $this->setSource("users");
        $this->hasOne(
            'id',
            'Cart',
            'user_id',
            array('foreignKey' => [
                    'action' => Relation::ACTION_CASCADE,
                ], 'alias' => 'Cart')
        );
        $this->hasMany(
            'id',
            'Orders',
            'user_id',
            array('foreignKey' => [
                    'action' => Relation::ACTION_CASCADE,
                ], 'alias' => 'Orders')
        );
        $this->hasMany(
            'id',
            'PromoRecords',
            'user_id',
            array('foreignKey' => [
                    'action' => Relation::ACTION_CASCADE,
                ], 'alias' => 'PromoRecords')
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
