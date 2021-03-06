<?php
require_once 'Service.php';
require_once 'model/AdminGateway.php';
/**
 * This is the Service Class for Admin
 * @copyright Hans Colman
 * @author Hans Colman
 */
class AdminService extends Service {
    /**
     * The AdminGateway
     * @var AdminGateway
     */
	private $adminGateway;
	
	public function __construct() {
		$this->adminGateway = new AdminGateway();
	}
	/**
	 * {@inheritDoc}
	 */
	public function activate($id, $AdminName) {
		$this->adminGateway->activate($id, $AdminName);
	}
	/**
	 * {@inheritDoc}
	 */
	public function delete($id, $reason, $AdminName) {
		try{
			$this->validateDeleteParams($reason);
			$this->adminGateway->delete($id, $reason, $AdminName);
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
		try{
			$rows = $this->adminGateway->selectAll($order);
			return $rows;
		}  catch (PDOException $e){
			print $e;
		}
	}
	/**
	 * {@inheritDoc}
	 */
	public function getByID($id) {
		return $this->adminGateway->selectById($id);
	}
	/**
	 * This function will create a new Account
	 * @param int $Account The ID of the Account
	 * @param int $Level The level
	 * @param string $AdminName The name of the administrator that did the creation
	 * @throws ValidationException
	 * @throws PDOException
	 */
	public function create($Level,$Account,$AdminName){
		try {
			$this->validateParams($Level, $Account);
			$this->adminGateway->create($Account,$Level, $AdminName);
		} catch (ValidationException $exc) {
			throw $exc;
		} catch (PDOException $e){
			throw $e;
		}
	}
	/**
	 * This function will update a given Account
	 * @param int $UUID The ID of Administrator
	 * @param int $Level The level of the Administrator
	 * @param string $Account The Unique ID of the account
	 * @throws ValidationException
	 * @throws PDOException
	 */
	public function update($UUID,$Level,$Account,$AdminName) {
		try {
			$this->validateParams($Level,$Account, $UUID);
			$this->adminGateway->update($UUID,$Account, $Level,$AdminName);
		} catch (ValidationException $exc) {
			throw $exc;
		} catch (PDOException $e){
			throw $e;
		}
	}
	/**
	 * {@inheritDoc}
	 */
	public function search($search) {
		return $this->adminGateway->selectBySearch($search);
	}
	/**
	 * This function will return all Accounts for the Application CMDB.
	 */
	public function getAllAccounts(){
		return $this->adminGateway->getAllAccount();
	}
	/**
	 * This function will return all Levels
	 */
	public function getAllLevels(){
		return $this->adminGateway->getAllLevels();		
	}
	/**
	 * This function will validate the Parameters
	 * @param int $Level
	 * @param int $Account
	 * @param int $UUID
	 * @throws ValidationException
	 */
	private function validateParams($Level,$Account, $UUID =0){
		$errors = array();
		if (empty($Level)) {
			$errors[] = 'Please select a Level';
		}
		if (empty($Account)) {
			$errors[] = 'Please select an Account';
		}
		if ($this->adminGateway->alreadyExist($Level, $Account,$UUID)){
			$errors[]= "The account is already an administrator";
		}
		if ( empty($errors) ) {
			return;
		}
		throw new ValidationException($errors);
	}
}