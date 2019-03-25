<?php
require_once 'Service.php';
require_once 'ValidationException.php';
require_once 'model/AccountGateway.php';
/**
 * This is the Service Class for Account
 * @copyright Hans Colman
 * @author Hans Colman
 */
class AccountService extends Service {
    /**
     * The AccountGateway
     * @var AccountGateway
     */
    private $accountGateway;
    
    public function __construct() {
        $this->accountGateway = new AccountGateway();
    }
    /**
     * {@inheritDoc}
     */
    public function activate($id, $AdminName) {
        $this->accountGateway->activate($id, $AdminName);
    }
    /**
     * {@inheritDoc}
     */
    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->accountGateway->delete($id, $reason, $AdminName);
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
            $rows = $this->accountGateway->selectAll($order);
            return $rows;
        }  catch (PDOException $e){
            print $e;
        }
    }
    /**
     * {@inheritDoc}
     */
    public function getByID($id) {
        return $this->accountGateway->selectById($id);
    }
    /**
     * This function will create a new Account
     * @param string $UserID The UserID of the Account
     * @param int $Type The ID of the AccountType
     * @param int $Application The ID of the Application
     * @param string $AdminName The name of the Administrator
     * @throws ValidationException
     * @throws PDOException
     */
    public function createNew($UserID,$Type,$Application,$AdminName){
        try {
            $this->validateAccountParams($UserID, $Type, $Application);
            $this->accountGateway->create($UserID, $Type, $Application, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will update a given Account
     * @param int $UUID The unique ID of the Account
     * @param string $UserID The UserID of the Account
     * @param int $Type The ID of the AccountType
     * @param int $Application The ID of the Application
     * @param string $AdminName The name of the Administrator
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($UUID,$UserID,$Type,$Application,$AdminName) {
        try {
            $this->validateAccountParams($UserID, $Type, $Application, $UUID);
            $this->accountGateway->update($UUID,$UserID, $Type, $Application, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will return all Accounts
     * @return array
     */
    public function getAllAcounts(){
        return $this->accountGateway->getAllAcounts();
    }
    /**
     * This function will assign an Account to an Identity
     * @param int $id The unique ID of the Account
     * @param int $Identity The unique ID of the Identity
     * @param DateTime $start The startDate
     * @param DateTime $end The EndDate
     * @param string $AdminName The name of the Administrator
     * @throws ValidationException
     * @throws PDOException
     */
    public function AssignIdentity($id, $Identity, $start, $end, $AdminName){
        try {
            $this->validateAssignParams($Identity, $start,$end);
            $this->accountGateway->AssignIdentity($id, $Identity, $start, $end, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will list all Identities assigned to an Account
     * @param int $id The ID of the Account
     */
    public function listAllIdentities($id){
        return $this->accountGateway->listAllIdentities($id);
    }
    /**
     * This function will return the active Identities from an account
     * @param int $UUID
     * @return array
     */
    public function getIdentityInfo($UUID){
        return $this->accountGateway->getIdentityInfo($UUID);
    }
    /**
     * {@inheritDoc}
     */
    public function search($search) {
        return $this->accountGateway->selectBySearch($search);
    }
    /**
     * This function will create the Release PDF for accounts
     * @param int $id the Unique ID of the Account²
     * @param int $IdenId The unique ID of the Account
     * @param string $Employee
     * @param string $ITEmployee
     */
    public function createReleaseAccountPDF($id,$IdenId,$Employee,$ITEmployee){
        require_once 'PDFGenerator.php';
        $AssignForm = new PDFGenerator();
        $Identities= $this->getIdentityInfo($IdenId);
        $AssignForm->setTitle("Release");
        foreach ($Identities as $identity){
            $AssignForm->setReceiverInfo($identity['Name'], htmlentities($identity['Language']),htmlentities($identity['UserID']));
        }
        $accounts = $this->getByID($id);
        foreach($accounts as $account){
            $AssignForm->setAccountInfo(htmlentities($account['UserID']), htmlentities($account['Application']), htmlentities($account['ValidFrom']), htmlentities($account['ValidEnd']));
        }
        $AssignForm->setEmployeeSingInfo($Employee);
        $AssignForm->setITSignInfo($ITEmployee);
        $AssignForm->createPDf();
    }
    /**
     * This function will release an Identiy from an account²
     * @param int $id
     * @param int $idenityId
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function releaseIdentity($id,$idenityId,$From,$AdminName){
        try{
            $this->validateReleaseIdentityParameters($id, $idenityId);
            $this->accountGateway->releaseIdenity($id,$idenityId,$From,$AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will validate the parameters during assign
     * @param int $Identity The unique ID of the Identity
     * @param DateTime $From The From Date
     * @param DateTime $Until The Until date
     * @throws ValidationException
     */
    private function validateAssignParams($Identity,$From,$Until){
        $errors = array();
        if (empty($Identity)) {
            $errors[] = 'Please select an Identity';
        }
        if (empty($From)){
            $errors[] = 'Please select the From Date';
        }
        if (!empty($From) and !empty($Until)){
            $FromDate =date_create_from_format('d/m/Y',$From);
            $EndDate = date_create_from_format('d/m/Y',$Until);
            if ($EndDate < $FromDate){
                $errors[] = 'The end date is before the from date';
            }
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    
    /**
     * This function will validate the parameters and throw an exception
     * @param string $userid The UserID of the Account
     * @param int $type The ID of the AccountType
     * @param int $application The ID of the Application
     * @throws ValidationException
     */
    private function validateAccountParams($userid, $type, $application, $UUID = 0){
    	$errors = array();
    	if (empty($type)) {
    		$errors[] = 'Please select a Type';
    	}
    	if (empty($userid)){
    		$errors[] = 'Please select a UserID';
    	}
    	if (empty($application)){
    		$errors[] = 'Please select a Application';
    	}
    	if ($UUID > 0){
    		if (strcmp($userid, $this->accountGateway->getUserID($UUID)) != 0){
    			if ($this->accountGateway->CheckDoubleEntry($userid, $application)){
    				$errors[] = 'This UserID already exist in the application';
    			}
    		}
    	}else{
    		if ($this->accountGateway->CheckDoubleEntry($userid, $application)){
    			$errors[] = 'This UserID already exist in the application';
    		}
    	}
    	if ( empty($errors) ) {
    		return;
    	}
    
    	throw new ValidationException($errors);
    }
    /**
     * This function will validate the parameters used when releasing an account
     * @param int $AccountID
     * @throws ValidationException
     */
    private function validateReleaseIdentityParameters($UUID,$IdenID){
        $errors = array();
        if (empty($UUID)) {
            $errors[] = 'Please select an Account';
        }
        if (empty($IdenID)) {
            $errors[] = 'Please select an Identity';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
