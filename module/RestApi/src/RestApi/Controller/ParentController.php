<?php
namespace RestApi\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use RestApi\Model\CustomerTable;
 
class ParentController extends AbstractRestfulController 
{
    protected $customerTable;
    
    protected function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('RestApi\Model\CustomerTable');
        }
        return $this->customerTable;
    }
}
 