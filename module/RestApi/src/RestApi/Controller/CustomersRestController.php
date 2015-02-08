<?php
namespace RestApi\Controller;
 
use Zend\View\Model\JsonModel;
use Zend\EventManager\EventManagerInterface;

use RestApi\Form\CustomerForm;
use RestApi\Controller\ParentController;
use RestApi\Model\Customer;

/**
 * Manages the Customers entities by interfacing the model and table gateway actions.
 * Provides basic CRUD functionality.
 */
class CustomersRestController extends ParentController
{
    /**
     * Methods that are non idempotent (they operate on data), are restricted.
     * They will require an access token.
     */
    protected $restrictedMethods = array(
        'POST',
        'PUT',
        'DELETE'
    );
  
    /**
     * This token is hardcoded to showcase the authentication feature in a simple way.
     * Ideally, I would use Basic Auth, or DB-based authentication, or even OAuth.
     * In any case, this token should be in a configuration file, but since its scope
     *     limits to this controller, I left it here for simplicity.
     */
    protected $restrictedToken = 'fooToken123';
    
    /**
     * Will check the access token for restricted methods
     * @param {Zend\EventManager\EventManagerInterface}
     */
    public function setEventManager(EventManagerInterface $events)
    {
        // The parent will already forbit unallowed methods
        parent::setEventManager($events);
        
        $events->attach('dispatch', array($this, 'checkAccess'), 10);
    }
    
    /**
     * @param {Zend\EventManager\EventManagerInterface}
     */
    public function checkAccess($e)
    {
        $response = $e->getResponse();
        $request  = $e->getRequest();
        $method   = $request->getMethod();
        
        // We matched a collection; test if we allow the particular request method
        if (in_array($method, $this->restrictedMethods)) {
          
            $token = $request->getHeaders()->get('Authentication')->getFieldValue();
            
            if ($token != 'Token ' . $this->restrictedToken) {
                $response->setStatusCode(401);
                return $response;
            }
        }
    } 
  
    /**
     * CRUD: index (list)
     */    
    public function getList() 
    {
        $results = $this->getCustomerTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }
        
        return new JsonModel(
            array('customers' => $data)
        );
    }
 
    /**
     * CRUD: Read
     */
    public function get($id)
    {
        $customer = $this->getCustomerTable()->getCustomer($id);
        return new JsonModel(
            array('customer' => $customer)
        );
    }
 
    /**
     * CRUD: Create
     */
    public function create($data)
    {
        $form = new CustomerForm();
        $customer = new Customer();
        
        $form->setInputFilter($customer->getInputFilter());
        
        if (!$data)
        {
            return new JsonModel(array(
                'error' => 'empty customer data'
            ));
        }
        
        $form->setData($data);
        
        if ($form->isValid()) {
            $customer->exchangeArray($form->getData());
            $id = $this->getCustomerTable()->saveCustomer($customer);
            
            return new JsonModel(array(
                'customer' => $this->getCustomerTable()->getCustomer($id),
            ));
        }
     
        return new JsonModel(array(
            'error' => 'input data is not valid'
        ));
    }
 
    /**
     * CRUD: Update
     */
    public function update($id, $data)
    {        
        if (!$data)
        {
            return new JsonModel(array(
                'error' => 'empty customer data'
            ));
        }
        
        $data['id'] = $id;
        
        $customer = $this->getCustomerTable()->getCustomer($id);
        $form = new CustomerForm();
        
        $form->setInputFilter($customer->getInputFilter());
        $form->setData($data);
        
        // In this case, the form is bound, so there is no need to manually update it
        if ($form->isValid()) {
            $customer->exchangeArray($form->getData());
            $id = $this->getCustomerTable()->saveCustomer($customer);
                  
            return new JsonModel(array(
                'customer' => $this->getCustomerTable()->getCustomer($id),
            ));
        }
     
        return new JsonModel(array(
            'error' => 'input data is not valid'
        ));
    }
    
    /**
     * @TODO: PATCH is used for partial updates.
     *        I didn't find it interesting to implement right now, so it's pending for a further version.
     *        PATCH is not yet supported nor offered through the OPTIONS method.
     */ 
    public function patch($id, $data){}
 
    /**
     * CRUD: Delete
     */
    public function delete($id)
    {
        $this->getCustomerTable()->deleteCustomer($id);
  
        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }
    
}