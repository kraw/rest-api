<?php

namespace RestApiTest\Model;

use RestApi\Model\Customer;
use PHPUnit_Framework_TestCase;

class CustomerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Used to automate tests by code reflection
     * @type {Array}
     */
    protected $fields = array('id', 'firstName', 'lastName', 'address', 'email');
    
    /**
     * A useful mock used to automate customer initialization
     * @type {Array}
     */
    protected $mockData = array(
        'id'     => 1,
        'firstName' => 'Jorge',
        'lastName'  => 'Albaladejo',
        'address'   => 'Rue de Lausanne, Renens, Switzerland',
        'email'     => 'correo@jorgealbaladejo.com'            
    );
    
    /**
     * Produces a mock customer with some predefined values (for DRY purposes)
     * @return {RestApi\Model\Customer}
     */
    protected function mockCustomer()
    {
        $customer = new Customer();        
        $customer->exchangeArray($this->mockData);        
        return $customer;
    }
    
    /*
     * Test cases
     */
    
    public function testCustomerInitialState()
    {
        $customer = new Customer();
        
        foreach ($this->fields as $field) 
        {
            $this->assertNull($customer->$field, '"' . $field . '" should initially be null');
        }
    }
    
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $customer = $this->mockCustomer();
        
        foreach ($this->fields as $field) 
        {
            $this->assertSame($this->mockData[$field], $customer->$field, '"' . $field . '" was not set correctly');
        }
    }
    
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $customer = $this->mockCustomer();
        
        // now reset its values
        $customer->exchangeArray(array());
        
        foreach ($this->fields as $field) 
        {
            $this->assertNull($customer->$field, '"' . $field . '" should have defaulted to null');
        }
    }
}