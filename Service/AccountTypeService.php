<?php
require_once 'ValidationException.php';
require_once 'Service.php';
require_once 'model/AccountTypeGateway.php';
/**
 * This is the Service Class for AccountType
 * @copyright Hans Colman
 * @author Hans Colman
 */
class AccountTypeService extends Service{
    /**
     * The accountTypeGateway
     * @var AccountTypeGateway
     */
    private $accountTypeGateway = NULL;
    /**
     * The default contructor
     */
    public function __construct() {
        $this->accountTypeGateway = new AccountTypeGateway();
    }
	/**
	 * {@inheritDoc}
	 */
    public function activate($id, $AdminName) {
        $this->accountTypeGateway->activate($id, $AdminName);
    }
	/**
	 * {@inheritDoc}
	 */
    public function delete($id, $reason, $AdminName) {
        try {
            $this->validateDeleteParams($reason);
            $this->accountTypeGateway->delete($id, $reason, $AdminName);
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
        return $this->accountTypeGateway->selectAll($order);
    }
	/**
	 * {@inheritDoc}
	 */
    public function getByID($id) {
        return $this->accountTypeGateway->selectById($id);
    }
    /**
     * This function will return all Types
     * @return array
     */
    public function listAllTypes() {
        return $this->accountTypeGateway->getAllTypes();
    }
    /**
     * This function will create a new Account Type
     * @param string $type The type of the account type
     * @param string $description the description for this type
     * @param string $AdminName The name of the person who did the creation
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($type,$description, $AdminName){
        try {
            $this->validateTypeParams($type,$description);
            $this->accountTypeGateway->create($type, $description, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will update a given account type
     * @param int $UUID The Unique ID of the AccountType
     * @param string $type The type of the account type
     * @param string $description The description for this type
     * @param string $AdminName The name of the person who did the update
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($UUID,$type,$description,$AdminName){
        try {
            $this->validateTypeParams($type,$description);
            $this->accountTypeGateway->update($UUID, $type, $description, $AdminName);
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
        return $this->accountTypeGateway->selectBySearch($search);
    }
    /**
     * This function will validate the parameters
     * @param string $type The type of the account type
     * @param string $description The description for this type
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
        if ($this->accountTypeGateway->CheckDoubleEntry($type, $description)){
            $errors[] = 'The same Account Type exist in the Application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
