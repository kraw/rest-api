<?php

namespace RestApiTest\Model;

use RestApi\Model\Customer;
use RestApiTest\ParentTestCase;

class CustomerTest extends ParentTestCase
{
    /**
     * Used to automate tests by code reflection
     * @type {Array}
     */
    protected $fields = array('id', 'firstName', 'lastName', 'address', 'email');
    
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
        $customer = $this->getMockCustomer();
        $mockData = $this->getMockCustomerData();
        
        foreach ($this->fields as $field) 
        {
            $this->assertSame($mockData[$field], $customer->$field, '"' . $field . '" was not set correctly');
        }
    }
    
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $customer = $this->getMockCustomer();
        
        // now reset its values
        $customer->exchangeArray(array());
        
        foreach ($this->fields as $field) 
        {
            $this->assertNull($customer->$field, '"' . $field . '" should have defaulted to null');
        }
    }
}