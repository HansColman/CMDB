<?php
require_once 'ValidationException.php';
require_once 'Service.php';
require_once 'model/RoleTypeGateway.php';
/**
 * This is the Service Class for RoleType
 * @copyright Hans Colman
 * @author Hans Colman
 */
class RoleTypeService extends Service{
    /**
     * The RoleTypeGateway
     * @var RoleTypeGateway
     */
    private $roleTypeGateway =NULL;
    /**
     * Constructor
     */
    public function __construct() {
        $this->roleTypeGateway = new RoleTypeGateway();
    }
    /**
     * {@inheritDoc}
     */
    public function activate($id, $AdminName) {
        $this->roleTypeGateway->activate($id, $AdminName);
    }
    /**
     * {@inheritDoc}
     */
    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->roleTypeGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     */
    public function getAll($order) {
        return $this->roleTypeGateway->selectAll($order);
    }
    /**
     * {@inheritDoc}
     */
    public function getByID($id) {
        return $this->roleTypeGateway->selectById($id);
    }
    /**
     * This function will create a new RoleType
     * @param string $type
     * @param string $description
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($type,$description, $AdminName){
        try {
            $this->validateTypeParams($type,$description);
            $this->roleTypeGateway->create($type, $description, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
        	throw $e;
        }
    }
    /**
     * This function will update a given RoleType
     * @param string $UUID
     * @param string $type
     * @param string $description
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($UUID,$type,$description,$AdminName){
        try {
            $this->validateTypeParams($type,$description);
            $this->roleTypeGateway->update($UUID, $type, $description, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
        	throw $e;
        }
    }
    /**
     * This function will return all active RoleTypes
     * @return array
     */
    public function listAllType() {
        return $this->roleTypeGateway->getAllTypes();
    }
    /**
     * {@inheritDoc}
     */
    public function search($search) {
        return $this->roleTypeGateway->selectBySearch($search);
    }
    /**
     * This function will validate the parameters and throws an error when not all required fields are filled in
     * @param string $type
     * @param string $description
     * @throws ValidationException
     */
    private function validateTypeParams($type,$description){
        $errors = array();
        if (empty($type)) {
            $errors[] = 'Please enter a Type';
        }
        if (empty($description)){
            $errors[] = 'Please enter a Description';
        }
        if ($this->roleTypeGateway->CheckDoubleEntry($type, $description)){
            $errors[] = 'The same Identity Type exist in the Application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}