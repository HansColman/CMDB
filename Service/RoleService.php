<?php
require_once 'Service.php';
require_once 'ValidationException.php';
require_once 'model/RoleGateway.php';
/**
 * This is the Service Class for Role
 * @copyright Hans Colman
 * @author Hans Colman
 */
class RoleService extends Service{
    /**
     * The RoleGateway
     * @var RoleGateway
     */
    private $roleGateway = NULL;
    /**
     * Constructor
     */
    public function __construct() {
        $this->roleGateway = new RoleGateway();
    }
    /**
     * {@inheritDoc}
     */
    public function activate($id, $AdminName) {
        $this->roleGateway->activate($id, $AdminName);
    }
    /**
     * {@inheritDoc}
     */
    public function delete($id, $reason, $AdminName) {
        try {
            $this->validateDeleteParams($reason);
            $this->roleGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     */
    public function getAll($order) {
        return $this->roleGateway->selectAll($order);
    }
    /**
     * {@inheritDoc}
     */
    public function getByID($id) {
        return $this->roleGateway->selectById($id);
    }
    /**
     * This function will create a new Role
     * @param string $Name
     * @param string $Description
     * @param int $Type
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($Name,$Description,$Type,$AdminName){
        try {
            $this->validateParameters($Name, $Description, $Type);
            $this->roleGateway->create($Name,$Description,$Type,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will update a given Role
     * @param int $UUID
     * @param string $Name
     * @param string $Description
     * @param int $Type
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($UUID,$Name,$Description,$Type,$AdminName){
        try {
            $this->validateParameters($Name, $Description, $Type);
            $this->roleGateway->update($UUID,$Name,$Description,$Type,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     */
    public function search($search) {
        return $this->roleGateway->selectBySearch($search);
    }
    /**
     * This function will validate the given parameters and thorws errors if there is one missing
     * @param string $Name
     * @param string $Description
     * @param string $Type
     * @throws ValidationException
     */
    private function validateParameters($Name,$Description,$Type){
        $errors = array();
        if (empty($Name)) {
            $errors[] = 'Please enter a Name';
        }
        if (empty($Type)){
            $errors[] = 'Please select a Type';
        }
        if($this->roleGateway->CheckDoubleEntry($Type, $Description, $Name)){
            $errors[] = 'Role already exist in the application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
