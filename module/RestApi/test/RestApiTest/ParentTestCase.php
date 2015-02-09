<?php

namespace RestApiTest;

use PHPUnit_Framework_TestCase;
use RestApi\Model\Customer;

class ParentTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Produces a mock customer with fake data
     * @return {RestApi\Model\Customer}
     */
    protected function getMockCustomer()
    {
        $customer = new Customer();
        $customer->exchangeArray($this->getMockCustomerData());
        
        return $customer;
    }
    
    /**
     * Provides fake data for a customer object, so that it is DRY.
     * @return {Array}
     */
    protected function getMockCustomerData()
    {
        return array(
            'id' => 123,
            'lastName' => 'Last Name',
            'firstName' => 'First Name',
            'email' => 'foo@email.com',
            'address' => 'Foo Address'
        );
    }
    
    /**
     * Produces a mock table gateway for the selected method
     * @param {String}
     * @return {Zend\Db\TableGateway\TableGateway}
     */
    protected function getMockTableGateway($method = 'select')
    {
        if (gettype($method) != 'array'){
            $method = array($method);
        }
        return $this->getMock('Zend\Db\TableGateway\TableGateway', $method, array(), '', false);
    }
}
