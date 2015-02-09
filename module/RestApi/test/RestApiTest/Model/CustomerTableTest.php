<?php

namespace RestApiTest\Model;

use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

use RestApi\Model\Customer;
use RestApi\Model\CustomerTable;

class CustomerTableTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests RestApi\Model\CustomerTable::fetchAll()
     */
    public function testFetchAllReturnsAllCustomer()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMockTableGateway('select');
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $customerTable = new \RestApi\Model\CustomerTable($mockTableGateway);

        $this->assertSame($resultSet, $customerTable->fetchAll());
    }
    
    /**
     * Tests RestApi\Model\CustomerTable::getCustomer($id)
     */
    public function testCanRetrieveCustomerByItsId()
    {
        // Prepare a mock customer
        $customer = $this->getMockCustomer();
        
        // Now prepare a mock resultset
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Customer());
        $resultSet->initialize(array($customer));
        
        // Mock the table gateway's behavior
        $mockTableGateway = $this->getMockTableGateway('select');;
        
        // Assert that select method will be called with ID 123        
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));
        
        // And call customerTable's method with the mocked gateway
        $customerTable = new CustomerTable($mockTableGateway);
        $this->assertSame($customer, $customerTable->getCustomer(123));
    }

    /**
     * Tests RestApi\Model\CustomerTable::deleteCustomer($id)
     */
    public function testCanDeleteCustomerByItsId()
    {
        // Mock a table gateway so that it will expect a call on 'delete' with id 123
        $mockTableGateway = $this->getMockTableGateway('delete');
        
        // This is an assertion, actually
        $mockTableGateway->expects($this->once())
                         ->method('delete')
                         ->with(array('id' => 123));
                         
        // Initialize and run
        $customerTable = new CustomerTable($mockTableGateway);
        $customerTable->deleteCustomer(123);
    }
    
    /**
     * Tests RestApi\Model\CustomerTable::saveCustomer() - create
     */
    public function testSaveCustomerWillCreateIfItDoesNotHaveAnId()
    {
        $customerData = $this->getMockCustomerData();
        $mockTableGateway = $this->getMockTableGateway('insert');
                         
        // No ID for our customer
        unset($customerData['id']);
        $customer = new Customer();        
        $customer->exchangeArray($customerData);
        
        // Assert that the insert method will be called with fake customer data
        $mockTableGateway->expects($this->once())
                         ->method('insert')
                         ->with($customerData);
                         
        $customerTable = new CustomerTable($mockTableGateway);
        $id = $customerTable->saveCustomer($customer);
    }
    
    public function testSaveCustomerWillUpdateIfItHasAnId()
    {
        $customer = $this->getMockCustomer();
        $customerData = $this->getMockCustomerData();
        $mockTableGateway = $this->getMockTableGateway(array('select', 'update'));
        
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Customer());
        $resultSet->initialize(array($customer));
        
        // Assert that the insert method will be called with fake customer data                         
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        // The 'update' method in the gateway will expect a customer array without the ID
        unset($customerData['id']);
        $mockTableGateway->expects($this->once())
                         ->method('update')
                         ->with($customerData, array('id' => 123));
                         
        $customerTable = new CustomerTable($mockTableGateway);
        $customerTable->saveCustomer($customer);
    }
    
    /**
     * Produces a mock customer with fake data
     * @return {RestApi\Model\Customer}
     */
    private function getMockCustomer()
    {
        $customer = new Customer();
        $customer->exchangeArray($this->getMockCustomerData());
        
        return $customer;
    }
    
    /**
     * Provides fake data for a customer object, so that it is DRY.
     * @return {Array}
     */
    private function getMockCustomerData()
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
    private function getMockTableGateway($method = 'select')
    {
        if (gettype($method) != 'array'){
            $method = array($method);
        }
        return $this->getMock('Zend\Db\TableGateway\TableGateway', $method, array(), '', false);
    }
    
}