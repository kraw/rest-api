<?php
namespace RestApi\Controller;
 
use Zend\Mvc\Controller\AbstractRestfulController;
 
use RestApi\Model\Customer;
use RestApi\Model\CustomerTable;
use RestApi\Form\CustomerForm;
use Zend\View\Model\JsonModel;
 
class CustomersRestController extends AbstractRestfulController
{
    protected $customerTable;
    
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
 
    public function get($id)
    {
        $customer = $this->getCustomerTable()->getCustomer($id);
        return new JsonModel(
            array('customer' => $customer)
        );
    }
 
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
    
    // @TODO: 
    public function patch($id, $data)
    {
        # code...
    }
 
    public function delete($id)
    {
        $this->getCustomerTable()->deleteCustomer($id);
  
        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }
    
    public function options()
    {
        $response = $this->getResponse();
        $headers  = $response->getHeaders();

        // If you want to vary based on whether this is a collection or an
        // individual item in that collection, check if an identifier from
        // the route is present
        if ($this->params()->fromRoute('id', false)) {
            // Allow viewing, partial updating, replacement, and deletion
            // on individual items
            $headers->addHeaderLine('Allow', implode(',', array(
                'GET',
                'PATCH',
                'PUT',
                'DELETE',
            )));
            return $response;
        }

        // Allow only retrieval and creation on collections
        $headers->addHeaderLine('Allow', implode(',', array(
            'GET',
            'POST',
        )));
        return $response;
    }
    
    protected function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('RestApi\Model\CustomerTable');
        }
        return $this->customerTable;
    }
}