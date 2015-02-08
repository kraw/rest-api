<?php
namespace RestApi\Controller;

use RestApi\Controller\ParentController;
use Zend\View\Model\JsonModel;

/**
 * Manages the search action on the customer collection.
 */
class CustomerSearchRestController extends ParentController
{
  
    /**
     * @Override
     * Only allow the search itself: a GET action on the collection.
     */
    protected $allowedCollectionMethods = array(
        'GET'
    );
    
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
    
}