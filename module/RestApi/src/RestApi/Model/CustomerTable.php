<?php
namespace RestApi\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;

class CustomerTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getCustomer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }
    
    public function saveCustomer(Customer $customer)
    {
        $data = array(
            'firstName' => $customer->firstName,
            'lastName'  => $customer->lastName,
            'address'   => $customer->address,
            'email'     => $customer->email,
        );
        
        $id = (int)$customer->id;
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue(); //Add this line
        } 
        else {
            if ($this->getCustomer($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else {
                return null;
            }
        }
        
        return $id; // Add Return
    }
    
    public function deleteCustomer($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function search($params)
    {
        $id = $params['id'];
        $lastName = $params['lastName'];
        $email = $params['email'];
        
        $where = array();
        
        if ($id > 0) $where['id'] = $id;
        if ($email != null && $email != '') $where['email'] = $email;
        if ($lastName != null && $lastName != '') $where[] = new Expression("lastName LIKE '%$lastName%'");
                
        $rowset = $this->tableGateway->select(function (Select $select) use ($where){          
            $select->where($where);
        });
        
        return $rowset;
    }
}