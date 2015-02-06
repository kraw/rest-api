<?php
namespace RestApiTest\Controller;

use RestApiTest\Bootstrap;
use RestApi\Controller\CustomersRestController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use PHPUnit_Framework_TestCase;

class CustomersRestControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new CustomersRestController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testGetListCanBeAccessed()
    {
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCanBeAccessed()
    {
        $this->routeMatch->setParam('id', '1');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateCanBeAccessed()
    {
        $this->request->setMethod('post');
        
        $this->request->getPost()->set('firstName', 'Jorge');
        $this->request->getPost()->set('lastName', 'Albaladejo');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateCanBeAccessed()
    {
        $this->routeMatch->setParam('id', '1');
        $this->request->setMethod('put');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteCanBeAccessed()
    {
        $this->routeMatch->setParam('id', '1');
        $this->request->setMethod('delete');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCustomerTableReturnsAnInstanceOfCustomerTable()
    {
        $this->assertInstanceOf('RestApi\Model\CustomerTable', $this->controller->getCustomerTable());
    }
}