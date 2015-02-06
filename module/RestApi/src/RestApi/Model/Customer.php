<?php

namespace RestApi\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Model Customer to represent the data structure
 */
class Customer implements InputFilterAwareInterface
{
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $address;
    protected $inputFilter;
    
    /**
     * Provided an input array, it copies its content based on the Customer data structure
     * @param {Array} $data
     */
    public function exchangeArray($data)
    {
        // @TODO: refactor this with reflection!! 
        $this->id         = (isset($data['id']))        ? $data['id']        : null;
        $this->firstName  = (isset($data['firstName'])) ? $data['firstName'] : null;
        $this->lastName   = (isset($data['lastName']))  ? $data['lastName']  : null;
        $this->email      = (isset($data['email']))     ? $data['email']     : null;
        $this->address    = (isset($data['address']))   ? $data['address']   : null;
    }
    
    /**
     * Provides an array representation of this object
     * @return {Array}
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    /**
     * Must be implemented, although not used
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    /**
     * Sets an input filter so that data model gets validated
     * @return {Zend\InputFilter\InputFilter}
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            
            // ID is required and numeric
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            
            // Last name is required and a string between 1 and 20 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'lastName',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            )));
            
            // First name is not required, but if present, a string between 1 and 20 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'firstName',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            )));
            
            // Address is not required, but if present, a string between 5 and 255 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'firstName',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));
            
            // Email is required and must be properly validated
            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(                     
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min'      => 5, 
                            'max'      => 255, 
                            'messages' => array( 
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid' 
                            ) 
                        ),
                    ),
                ),
            )));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}