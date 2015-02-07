<?php
namespace RestApi\Controller;
 
use Zend\Mvc\Controller\AbstractRestfulController;
 
use RestApi\Model\Customer;
use RestApi\Model\CustomerTable;
use RestApi\Form\CustomerForm;
use Zend\View\Model\JsonModel;
 
class CustomerSearchRestController extends AbstractRestfulController
{
    protected $customerTable;

    /**
     * The default action when calling GET on this controller is the search itself
     */
    public function getList($customerId = null, $lastName = null, $email = null)
    {
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $request = $this->getRequest();
        
        // @TODO: This input should be sanitized, since I'm not 100% that ZF2 is doing that
        //        I am skipping this right now to focus on the functionality instead.
        $inputData = array( 
            'id' => (int)$request->getQuery('customerId'),
            'lastName' => $request->getQuery('lastName'),
            'email' => $request->getQuery('email')
        );        
        
        // Pass the ball on to the customer table to search        
        $results = $this->getCustomerTable()->search($inputData);
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }
        
        // Avoid cache 
        $headers->addHeaderLine('Cache-Control', 'max-age=0, no-cache, no-store');
        $headers->addHeaderLine('Pragma', 'no-cache');
        
        return new JsonModel(
            array('customers' => $data)
        );
    }
    
    public function options()
    {
        $response = $this->getResponse();
        $headers  = $response->getHeaders();

        // Allow only retrieval and creation on collections
        $headers->addHeaderLine('Allow', implode(',', array(
            'GET'
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