<?php
namespace RestApi\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;

/**
 * Maps the DB table through the provided adapter
 */
class CustomerTable
{
    /**
     * DB proxy
     */
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * CRUD: get a list
     * @return {Zend\Db\ResultSet\ResultSet}
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    /**
     * CRUD: Read
     * @param {Int}
     */
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
    
    /**
     * CRUD: Create & Update
     * @param {RestApi\Model\Customer}
     * @return {Int}
     */
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
            $id = $this->tableGateway->getLastInsertValue();
        } 
        else {
            if ($this->getCustomer($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else {
                return null;
            }
        }
        
        return $id;
    }
    
    /**
     * CRUD: Delete
     * @param {Int}
     * @return {Int}
     */
    public function deleteCustomer($id)
    {
        return $this->tableGateway->delete(array('id' => $id));
    }
    
    /**
     * Extra function to search in the collection
     * @return {Zend\Db\ResultSet\ResultSet}
     */
    public function search($params)
    {
        $id = $params['id'];
        $lastName = $params['lastName'];
        $email = $params['email'];
        
        $where = array();
        
        if ($id > 0) $where['id'] = $id;
        if ($email != null && $email != '') $where['email'] = $email;
        if ($lastName != null && $lastName != '') $where[] = new Expression("lastName LIKE '%$lastName%'");
                
        // At this point, I trust that Zend is sanitizing the query for me...
        // otherwise, I should sanitize it manually before constructing the $where array
        $rowset = $this->tableGateway->select(function (Select $select) use ($where){          
            $select->where($where);
        });
        
        return $rowset;
    }
}