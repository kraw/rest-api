<?php

namespace ApiRestTest\Model;

use ApiRest\Model\Customer;
use PHPUnit_Framework_TestCase;

class CustomerTest extends PHPUnit_Framework_TestCase
{
    public function testCustomerInitialState()
    {
        $customer = new Customer();
        $this->assertNull($customer->id, '"id" should initially be null');
        $this->assertNull($customer->firstName, '"firstName" should initially be null');
        $this->assertNull($customer->lastName, '"firstName" should initially be null');
        $this->assertNull($customer->address, '"address" should initially be null');
        $this->assertNull($customer->email, '"email" should initially be null');
    }
    
    /*public function testExchangeArraySetsPropertiesCorrectly()
    {
        $album = new Album();
        $data  = array('artist' => 'some artist',
                       'id'     => 123,
                       'title'  => 'some title');
        $album->exchangeArray($data);
        $this->assertSame($data['artist'], $album->artist, '"artist" was not set correctly');
        $this->assertSame($data['id'], $album->id, '"title" was not set correctly');
        $this->assertSame($data['title'], $album->title, '"title" was not set correctly');
    }
    
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $album = new Album();
        $album->exchangeArray(array('artist' => 'some artist',
                                    'id'     => 123,
                                    'title'  => 'some title'));
        $album->exchangeArray(array());
        $this->assertNull($album->artist, '"artist" should have defaulted to null');
        $this->assertNull($album->id, '"title" should have defaulted to null');
        $this->assertNull($album->title, '"title" should have defaulted to null');
    }*/
}