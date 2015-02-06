<?php

namespace RestApi\Form;

use Zend\Form\Form;

/**
 * This basic representation of a customer form is used for data input validation
 */
class CustomerForm extends Form
{
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('customer');
        
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'id'
        ));
        
        $this->add(array(
            'name' => 'firstName'
        ));
        
        $this->add(array(
            'name' => 'lastName'
        ));
        
        $this->add(array(
            'name' => 'address'
        ));
        
        $this->add(array(
            'name' => 'email'
        ));
    }
}