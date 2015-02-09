<?php

namespace RestApi\Form;

use Zend\Form\Form;

/**
 * This basic representation of a search form is used for data input validation
 */
class SearchForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'id'
        ));
        
        $this->add(array(
            'name' => 'lastName'
        ));
        
        $this->add(array(
            'name' => 'email'
        ));
    }
}