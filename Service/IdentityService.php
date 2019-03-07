<?php
require_once 'ValidationException.php';
require_once 'Service.php';
require_once 'model/IdentityGateway.php';
/**
 * This is the Service Class for Identity
 * @copyright Hans Colman
 * @author Hans Colman
 */
class IdentityService extends Service{
    /**
     * 
     * @var IdentityGateway The IdentityGateway
     */
    private $identityGateway  = NULL;
    /**
     * Constructor
     */
    public function __construct() {
        $this->identityGateway = new IdentityGateway();
    }
    /**
     * {@inheritDoc}
     * @see Service::getByID()
     */    
    public function getByID($id){
        try{
            return $this->identityGateway->selectById($id);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
    /**
     * This function will list all Assigned Accounts to an Identity
     * @param int $id The Unique id of the Identity
     * @return Array
     */
    public function listAssignedAccount($id){
        return $this->identityGateway->listAssignedAccount($id);
    }
    /**
     * This function will list all Assigned Devices to an Identity
     * @param int $id The Unique id of the Identity
     * @return Array
     */
    public function listAssignedDevices($id){
        return $this->identityGateway->listAssignedDevices($id);
    }
	/**
	 * {@inheritDoc}
	 * @see Service::delete()
	 */
    public function delete($id,$reason,$AdminName){
        try{
            $this->validateDeleteParams($reason); 
            $this->identityGateway->delete($id,$reason,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($id, $AdminName){
        try{
            $this->identityGateway->activate($id,$AdminName);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Service::getAll()
	 */
    public function getAll($order) {
        try{
            $rows = $this->identityGateway->selectAll($order);
            return $rows;
        }  catch (PDOException $e){
            print $e;
        }
    }    
    /**
     * This function will create a new Identity
     * @param string $firstname The fist name of the Identity
     * @param string $lastname The Last Name of the Identity
     * @param string $company The name of the company of the Identity
     * @param string $language The language of the Identity
     * @param string $userid The UserID of the Identity
     * @param int $type The Type of the Identity
     * @param string $email The e-mail address of the Identity
     * @param string $AdminName The name of the person who did the creation
     * @throws ValidationException
     */
    public function create($firstname, $lastname,$company, $language,$userid, $type, $email, $AdminName) {
        try {
            $this->validateIdentiyParams($firstname,$lastname,$company,$language,$userid,$type, $email);
            $this->identityGateway->create($firstname,$lastname,$company,$language,$userid,$type,$email,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }
    }
    /**
     * This function will update the given Identiy
     * @param int $UUID The unique ID of the Identity
     * @param string $firstname The fist name of the Identity
     * @param string $lastname The Last Name of the Identity
     * @param string $company The name of the company of the Identity
     * @param string $language The language of the Identity
     * @param string $userid The UserID of the Identity
     * @param int $type The Type of the Identity
     * @param string $email The e-mail address of the Identity
     * @param string $AdminName The name of the person who did the update
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($UUID,$firstname, $lastname,$company, $language,$userid, $type,$email, $AdminName){
        try {
            $this->validateIdentiyParams($firstname,$lastname,$company,$language,$userid,$type, $email, $UUID);
            $this->identityGateway->update($UUID, $firstname, $lastname, $userid, $company, $language, $type, $email,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }  catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will update all Identities
     * @return array
     */
    public function listAllIdentities(){
        return $this->identityGateway->listAllIdentities();
    }
    /**
     * This function will list all Accounts
     * @return array
     */
    public function listAllAccounts(){
        return $this->identityGateway->listAllAccounts();
    }
    /**
     * This function will assign an Identity to an Account
     * @param int $UUID THe unique id of the Identity
     * @param int $Account The unique ID of the account
     * @param DateTime $From The From date
     * @param DateTime $Until The Until Date
     * @param string $AdminName The name of the person who did the Assign
     * @throws ValidationException
     * @throws PDOException
     */
    public function AssignAccount($UUID,$Account,$From,$Until,$AdminName) {
        try {
            $this->validateAssignParams($UUID,$Account, $From, $Until);
            $this->identityGateway->AssignAccount($UUID, $Account, $From, $Until, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search){
        return $this->identityGateway->selectBySearch($search);
    }
    /**
     * This function will return all not assigned devices
     * @param int $category The Category of the Asset
     */
    public function listAllDevices($category){
        return $this->identityGateway->listAllFreeDevices($category);
    }
    /**
     * This function will assign all the given devices to an Identity
     * @param int $UUID
     * @param string $Laptop
     * @param string $Desktop
     * @param string $Screen
     * @param int $Internet
     * @param string $Token
     * @param int $Mobilie
     * @param string $AdminName
     */
    public function AssigDevices($UUID,$Laptop,$Desktop,$Screen,$Internet,$Token,$Mobilie, $AdminName){
        try {
            $this->validateAssignDeviceParams($Laptop, $Desktop, $Screen, $Internet, $Token, $Mobilie);
            $this->identityGateway->AssignDevices($UUID,$Laptop,$Desktop,$Screen,$Internet,$Token,$Mobilie, $AdminName);
        }catch (PDOException $e){
            throw  $e;
        }catch (ValidationException $ex){
            throw $ex;
        }
    }
    /**
     * This will list all devices that are assigned to the Identity
     * @param int $id The UUID of the Identity
     */
    public function getAllAssingedDevices($id){
        return $this->identityGateway->getAllAssingedDevices($id);
    }
    /**
     * This function will return the Asset info of an given AssetTag
     * @param string $AssetTag
     * @return array
     */
    public function getAssetInfo($AssetTag){
        return $this->identityGateway->getAssetInfo($AssetTag);
    }
    /**
     * This function will return the Mobile info
     * @param int $IMEI
     * @return array
     */
    public function getMobileInfo($IMEI) {
        return $this->identityGateway->getMobileInfo($IMEI);
    }
    /**
     * This function will create the PDF
     * @param int $id
     * @param string $Employee
     * @param string $ITEmployee
     */
    public function createPDF($id, $Employee, $ITEmployee){
        require_once 'PDFGenerator.php';
        $AssignForm = new PDFGenerator();
        $Identities= $this->getByID($id);
        $AssignForm->setTitle();
        foreach ($Identities as $identity){
            $AssignForm->setReceiverInfo($identity['Name'], htmlentities($identity['Language']),htmlentities($identity['UserID']));
        }
        $Devices = $this->listAssignedDevices($id);
        foreach ($Devices as $asset){
            $AssignForm->setAssetInfo($asset["Category"], htmlentities($asset['Type']), htmlentities($asset['SerialNumber']), htmlentities($asset['AssetTag']));
        }
        $AssignForm->setEmployeeSingInfo($Employee);
        $AssignForm->setITSignInfo($ITEmployee);
        $AssignForm->createPDf();
    }
    /**
     * This function will create the Release PDF for devices
     * @param int $id
     * @param string $AssetCategory
     * @param string $AssetTag
     * @param string $AssetType
     * @param string $SerialNumber
     * @param string $Employee
     * @param string $ITEmployee
     */
    public function createReleasePDF($id,$AssetCategory,$AssetTag,$AssetType,$SerialNumber,$Employee,$ITEmployee){
        require_once 'PDFGenerator.php';
        $AssignForm = new PDFGenerator();
        $Identities= $this->getByID($id);
        $AssignForm->setTitle("Release");
        foreach ($Identities as $identity){
            $AssignForm->setReceiverInfo($identity['Name'], htmlentities($identity['Language']),htmlentities($identity['UserID']));
        }
        $AssignForm->setAssetInfo(htmlentities($AssetCategory), htmlentities($AssetType), htmlentities($SerialNumber), htmlentities($AssetTag));
        $AssignForm->setEmployeeSingInfo($Employee);
        $AssignForm->setITSignInfo($ITEmployee);
        $AssignForm->createPDf();
    }
    /**
     * This function will create the Release PDF for accounts
     * @param int $id
     * @param int $accountId
     * @param string $Employee
     * @param string $ITEmployee
     */
    public function createReleaseAccountPDF($id,$accountId,$Employee,$ITEmployee){
        require_once 'PDFGenerator.php';
        $AssignForm = new PDFGenerator();
        $Identities= $this->getByID($id);
        $AssignForm->setTitle("Release");
        foreach ($Identities as $identity){
            $AssignForm->setReceiverInfo($identity['Name'], htmlentities($identity['Language']),htmlentities($identity['UserID']));
        }
        $accounts = $this->getAccountInfo($accountId);
        foreach($accounts as $account){
            $AssignForm->setAccountInfo(htmlentities($account['UserID']), htmlentities($account['Application']), htmlentities($account['ValidFrom']), htmlentities($account['ValidEnd']));
        }
        $AssignForm->setEmployeeSingInfo($Employee);
        $AssignForm->setITSignInfo($ITEmployee);
        $AssignForm->createPDf();
    }
    /**
     * This function will release an Account
     * @param int $UUID The Identity ID
     * @param int $AccountID The Id of the Account
     * @param DateTime $From the date from when the account was assigned.
     * @param string $AdminName The name of the person who did the release
     */
    public function releaseAccount($UUID,$AccountID,$From,$AdminName){
        try{
            $this->validateReleaseAccountParameters($UUID,$AccountID);
            $this->identityGateway->ReleaseAccount($UUID, $AccountID, $From, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }catch (PDOException $e){
            throw  $e;
        }
    }
    /**
     * This function will release a given Asset from an Identity
     * @param int $UUID
     * @param string $AssetTag
     * @param int $IMEI
     * @param int $Subscription
     * @param string $AdminName
     */
    public  function releaseDevice($UUID, $AssetTag =NULL, $IMEI =0, $Subscription =0, $AdminName){
        try {
            if (isset($AssetTag)){
                $this->validateReleaseDeviceParameters($AssetTag, $IMEI, $Subscription);
                $this->identityGateway->ReleaseDevices($UUID, $AssetTag, $IMEI, $Subscription, $AdminName);
            }
            if (isset($IMEI)){
                $this->validateReleaseDeviceParameters($AssetTag, $IMEI, $Subscription);
                $this->identityGateway->ReleaseDevices($UUID, $AssetTag, $IMEI, $Subscription, $AdminName); 
            }
            if (isset($Subscription)){
                $this->validateReleaseDeviceParameters($AssetTag, $IMEI, $Subscription);
                $this->identityGateway->ReleaseDevices($UUID, $AssetTag, $IMEI, $Subscription, $AdminName);
            }
        }catch (PDOException $e){
            throw  $e;
        }catch (ValidationException $ex){
            throw $ex;
        }
    }
    /**
     * This function will return the account info of an given AccountID
     * @param int $AccountID
     * @return array
     */
    public function getAccountInfo($AccountID){
        return $this->identityGateway->getAccountInfo($AccountID);
    }
    /**
     * This function will validate the parameters
     * @param string $firstname The fist name of the Identity
     * @param string $lastname The Last Name of the Identity
     * @param string $company The name of the company of the Identity
     * @param string $language The language of the Identity
     * @param string $userid The UserID of the Identity
     * @param int $type The Type of the Identity
     * @param string $email The e-mail address of the Identity
     * @param int $UUID The Unique ID of the IdentityType
     * @throws ValidationException
     */
    private function validateIdentiyParams($firstname, $lastname, $company, $language, $userid, $type, $email, $UUID = 0){
    	$errors = array();
    	if (empty($firstname)) {
    		$errors[] = 'Please enter First Name';
    	}
    	if (empty($lastname)) {
    		$errors[] = 'Please enter Last Name';
    	}
    	if (empty($type)) {
    		$errors[] = 'Please select a Type';
    	}
    	if (empty($language)){
    		$errors[] = 'Please select a Language';
    	}
    	if (empty($userid)){
    		$errors[] = 'Please select a UserID';
    	}
    	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		$errors[] = 'Invalid email format';
    	}
    	if ($UUID > 0){
    		if (strcmp($userid, $this->identityGateway->getUserID($UUID)) != 0){
    			if ($this->identityGateway->UserIDChecker($userid)){
    				$errors[] = 'UserID already excist in Application please select an other UserID';
    			}
    		}
    	}  else {
    		if ($this->identityGateway->UserIDChecker($userid)){
    			$errors[] = 'UserID already excist in Application please select an other UserID';
    		}
    	}
    	if ( empty($errors) ) {
    		return;
    	}
    
    	throw new ValidationException($errors);
    }
    /**
     * This function will validate the parameters during assign
     * @param int $Account The ID of the Account
     * @param DateTime $From The From Date
     * @param DateTime $Until The Until Date
     * @throws ValidationException
     */
    private function validateAssignParams($UUID,$Account,$From,$Until){
    	$errors = array();
    	if (empty($Account)) {
    		$errors[] = 'Please select an Account';
    	}
    	if (empty($From)){
    		$errors[] = 'Please select the From Date';
    	}
    	if (!empty($From) and !empty($Until)){
    		$FromDate = date_create_from_format('d/m/Y',$From);
    		$EndDate = date_create_from_format('d/m/Y',$Until);
    		if ($EndDate < $FromDate){
    			$errors[] = 'The end date is before the from date';
    		}
    	}
    	if ($this->identityGateway->checkAccountExist($UUID, $Account, $From)){
    	    $errors[]="The same account is assigned in the same time period";
    	}
    	if ( empty($errors) ) {
    		return;
    	}
    
    	throw new ValidationException($errors);
    }
    /**
     * This function will check the parameters when assigning
     * @param string $Laptop
     * @param string $Desktop
     * @param string $Screen
     * @param int $Internet
     * @param string $Token
     * @param int $Mobilie
     * @throws ValidationException
     */
    private function validateAssignDeviceParams($Laptop,$Desktop,$Screen,$Internet,$Token,$Mobilie){
        $errors = array();
        $Error = TRUE;
        if (!empty($Laptop)) {
            $Error = FALSE;
        }
        if (!empty($Desktop)){
            $Error = FALSE;
        }
        if(!empty($Screen)){
            $Error = FALSE;
        }
        if(!empty($Internet)){
            $Error = FALSE;
        }
        if(!empty($Token)){
            $Error = FALSE;
        }
        if(!empty($Mobilie)){
            $Error = FALSE;
        }
        if ($Error){
            $errors[] = 'Please select at lease one of the Devices';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    /**
     * This function will validate the release Device parameters
     * @param string $AssetTag
     * @param int $IMEI
     * @param int $Subscription
     * @throws ValidationException
     */
    private function validateReleaseDeviceParameters($AssetTag, $IMEI, $Subscription){
        $errors = array();
        $Error = TRUE;
        if (!empty($AssetTag)) {
            $Error = FALSE;
        }
        if (!empty($IMEI)) {
            $Error = FALSE;
        }
        if (!empty($Subscription)) {
            $Error = FALSE;
        }
        if ($Error){
            $errors[] = 'Please select at lease one of the Devices';
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
    private function validateReleaseAccountParameters($UUID,$AccountID){
        $errors = array();
        if (empty($UUID)) {
            $errors[] = 'Please select an Identity';
        }
        if (empty($AccountID)) {
            $errors[] = 'Please select an Account';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
