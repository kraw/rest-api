<?php
namespace RestApi\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use RestApi\Model\CustomerTable;
use Zend\EventManager\EventManagerInterface;
 
/**
 * Parent Controller used for DRY purposes
 */
class ParentController extends AbstractRestfulController 
{
    /**
     * Singleton
     */
    protected $customerTable;
    
    /**
     * Will initialize $customerTable providing access to database
     * @return {RestApi\Model\CustomerTable}
     */
    protected function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('RestApi\Model\CustomerTable');
        }
        return $this->customerTable;
    }
    
    /**
     * For OPTIONS response automation
     */
    protected $allowedCollectionMethods = array(
        'GET',
        'POST',
        'OPTIONS'
    );

    /**
     * For OPTIONS response automation
     */
    protected $allowedResourceMethods = array(
        'GET',
        'PUT',
        'DELETE',
        'OPTIONS'
    );

    /**
     * Will check the HTTP verb before processing the request
     * @param {Zend\EventManager\EventManagerInterface}
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach('dispatch', array($this, 'checkOptions'), 10);
    }

    /**
     * Return a 405 error if the method (verb) is not allowed
     * @param {Zend\EventManager\EventManagerInterface}
     */
    public function checkOptions($e)
    {
        $matches  = $e->getRouteMatch();
        $response = $e->getResponse();
        $request  = $e->getRequest();
        $method   = $request->getMethod();

        // test if we matched an individual resource, and then test
        // if we allow the particular request method
        if ($matches->getParam('id', false)) {
            if (!in_array($method, $this->allowedResourceMethods)) {
                $response->setStatusCode(405);
                return $response;
            }
            return;
        }

        // We matched a collection; test if we allow the particular request method
        if (!in_array($method, $this->allowedCollectionMethods)) {
            $response->setStatusCode(405);
            return $response;
        }
    }
    
    /**
     * The OPTIONS action is automated, so it can safely be placed in the parent.
     * Override $allowedCollectionMethods or $allowedResourceMethods in the child classes to change behavior.
     */
    public function options()
    {
        $response = $this->getResponse();
        $headers  = $response->getHeaders();

        // If there is an ID, we are addressing an individual resource
        if ($this->params()->fromRoute('id', false)) {
            $headers->addHeaderLine('Allow', implode(',', $this->allowedResourceMethods));
            return $response;
        }

        // Otherwise, provide options for collections
        $headers->addHeaderLine('Allow', implode(',', $this->allowedCollectionMethods));
        
        return $response;
    }
}
 