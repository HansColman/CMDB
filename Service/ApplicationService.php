<?php
require_once 'Service.php';
require_once 'model/ApplicationGateway.php';
/**
 * This is the Service Class for Application
 * @copyright Hans Colman
 * @author Hans Colman
 */
class ApplicationService extends Service{
    /**
     * The applicationGateway
     * @var ApplicationGateway
     */
    private $applicationGateway = NULL;
    /**
     * The contructor
     */
    public function __construct() {
        $this->applicationGateway = new ApplicationGateway();
    }
	/**
	 * {@inheritDoc}
	 */
    public function activate($id, $AdminName) {
    	try{
        	$this->applicationGateway->activate($id, $AdminName);
        } catch (PDOException $exc){
        	throw $exc;
        }
    }
	/**
	 * {@inheritDoc}
	 */
    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->applicationGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $exc){
            throw $exc;
        }
    }
	/**
	 * {@inheritDoc}
	 */
    public function getAll($order) {
        return $this->applicationGateway->selectAll($order);
    }
	/**
	 * {@inheritDoc}
	 */
    public function getByID($id) {
        return $this->applicationGateway->selectById($id);
    }
    /**
     * This function will return all applications
     * @return array
     */
    public function listAllApplications() {
        return $this->applicationGateway->getAllApplications();
    }
	/**
	 * {@inheritDoc}
	 */
    public function search($search) {
        return $this->applicationGateway->selectBySearch($search);
    }
    /**
     * This function will create a new Application
     * @param string $Name The name of the application
     * @param string $AdminName The name of the person who did the creation
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($Name,$AdminName){
        try{
            $this->validateParameters($Name);
            $this->applicationGateway->create($Name,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will return all accounts
     * @param int $UUID The unique ID of the Application
     */
    public function listAllAccounts($UUID) {
        return $this->applicationGateway->listAllAccounts($UUID);
    }
    /**
     * This function will update an existing Application
     * @param int $UUID The unique of the application
     * @param string $Name The name of the application
     * @param string $AdminName The name of the person who did the update
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($UUID,$Name,$AdminName){
    	try {
    		$this->validateParameters($Name,$UUID);
    		$this->applicationGateway->update($UUID,$Name,$AdminName);
    	} catch (ValidationException $ex) {
    		throw $ex;
    	} catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This will validate the given parameters
     * @param String $Name The Name of the Application
     * @throws ValidationException
     */
    private function validateParameters($Name, $UUID = 0){
        $errors = array();
        if (empty($Name)) {
            $errors[] = 'Please enter a Name';
        }
        if ($UUID >0 and $this->applicationGateway->alreadyExist($Name,$UUID)){
        	$errors[] = 'This application already exist';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
