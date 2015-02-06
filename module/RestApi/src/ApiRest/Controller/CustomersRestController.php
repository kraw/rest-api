<?php
namespace ApiRest\Controller;
 
use Zend\Mvc\Controller\AbstractRestfulController;
 
use ApiRest\Model\Customer;
use ApiRest\Model\CustomerTable;
use Zend\View\Model\JsonModel;
 
class CustomersRestController extends AbstractRestfulController
{
    
    public function getList()
    {
        $results = $this->getCustomerTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }
 
        return array('data' => $data);
    }
 
    public function get($id)
    {
        # code...
    }
 
    public function create($data)
    {
        # code...
    }
 
    public function update($id, $data)
    {
        # code...
    }
 
    public function delete($id)
    {
        # code...
    }
    
    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTableTable = $sm->get('ApiRest\Model\CustomerTable');
        }
        return $this->customerTable;
    }
    
    protected $customerTable;
}