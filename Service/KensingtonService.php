<?php
require_once 'Service.php';
require_once 'ValidationException.php';
require_once 'model/KensingtonGateway.php';
/**
 * This is the Service Class for Kensington
 * @copyright Hans Colman
 * @author Hans Colman
 */
class KensingtonService extends Service{
    /**
     * The KensingtonGateway
     * @var KensingtonGateway
     */
    private $kensingtonGateway = null;
    /**
     * Constuctor
     */
    public function __construct() {
        $this->kensingtonGateway = new KensingtonGateway();
    }

    public function activate($id, $AdminName) {
    	try {
        	$this->kensingtonGateway->activate($id, $AdminName);
        } catch (PDOException $e){
        	throw  $e;
        }
    }
    /**
     * {@inheritDoc}
     */
    public function delete($id, $reason, $AdminName) {
        try {
            $this->validateDeleteParams($reason);
            $this->kensingtonGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw  $e;
        }
    }
    /**
     * This function will Create a new Kensington
     * @param int $Type
     * @param string $Serial
     * @param int $NrKeys
     * @param int $hasLock
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function add($Type,$Serial,$NrKeys,$hasLock,$AdminName){
        try {
            $this->validateParams($Type,$Serial,$NrKeys,$hasLock);
            $this->kensingtonGateway->create($Type,$Serial,$NrKeys,$hasLock,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will edit a given Kensington
     * @param int $UUID
     * @param int $Type
     * @param string $Serial
     * @param int $NrKeys
     * @param int $hasLock
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function edit($UUID,$Type,$Serial,$NrKeys,$hasLock,$AdminName) {
        try {
            $this->validateParams($Type, $Serial, $NrKeys, $hasLock, $UUID);
            $this->kensingtonGateway->update($UUID, $Type, $Serial, $NrKeys, $hasLock, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw  $e;
        }
    }
    /**
     * {@inheritDoc}
     */
    public function getAll($order) {
        return $this->kensingtonGateway->selectAll($order);
    }
	/**
	 * {@inheritDoc}
	 */
    public function getByID($id) {
        return $this->kensingtonGateway->selectById($id);
    }
    /**
     * {@inheritDoc}
     */
    public function search($search) {
        return $this->kensingtonGateway->selectBySearch($search);
    }
    /**
     * This function will return all active Token Types
     * @return Array
     */
    public function listAllTypes(){
        return $this->kensingtonGateway->listAllTypes();
    }
    /**
     * This function will list ALl Asset for a given Key
     * @param int $UUID
     * @return array
     */
    public function listAssets($UUID) {
        return $this->kensingtonGateway->GetAssetInfo($UUID);
    }
    /**
     * This function will return a list of Device that can be assigned
     * @return array
     */
    public function listAlAssets(){
        return $this->kensingtonGateway->listAlAssets();
    }
    /**
     * This function will assing a Device to a Key
     * @param int $id
     * @param string $AssetTag
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function assingDevice($id,$AssetTag,$AdminName){
        try{
            $this->validateAssignParams($AssetTag, $id);
            $this->kensingtonGateway->assingDevice($id,$AssetTag,$AdminName);
        }catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw  $e;
        }
    }
    /**
     * This will return the Identity info that is assigned to a device
     * @param int $id The Kensington ID
     * @return array
     */
    public function getAssignIdentity($id){
        return $this->kensingtonGateway->getAssignIdentity($id);
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
        $AssignForm->setTitle();
        $Identities = $this->getAssignIdentity($id);
        foreach ($Identities as $identity){
            $AssignForm->setReceiverInfo($identity['Name'], htmlentities($identity['Language']),htmlentities($identity['UserID']));
        }
        $Devices = $this->getByID($id);
        foreach ($Devices as $asset){
            $AssignForm->setAssetInfo($asset["Category"], htmlentities($asset['Type']), htmlentities($asset['SerialNumber']), htmlentities($asset['AssetTag']));
        }
        $AssignForm->setEmployeeSingInfo($Employee);
        $AssignForm->setITSignInfo($ITEmployee);
        $AssignForm->createPDf();
    }
    /**
     * This function will release the device from the Kensington
     * @param int $id Unique ID of the Kensington
     * @param string $AssetTag
     * @param string $Employee
     * @param string $ITEmployee
     * @param string $AdminName
     */
    public function releaseDevice($id,$AssetTag,$IdentityID,$Employee,$ITEmployee,$AdminName) {
        try{
            $this->validateReleaseParams($AssetTag, $id, $ITEmployee, $Employee);
            $this->kensingtonGateway->releaseDevice($id, $AssetTag, $IdentityID, $AdminName);
        }catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw  $e;
        }
    }
    
    public function createReleasePDF($id,$identityid,$AssetTag,$Employee,$ITEmployee){
        require_once 'PDFGenerator.php';
        $AssignForm = new PDFGenerator();
        $AssignForm->setTitle("Release");
        $IdenRows = $this->kensingtonGateway->getIdentityInfo($identityid);
        $DeviceRows = $this->kensingtonGateway->getAssetDetails($AssetTag);
        $KeyRows = $this->getByID($id);
        foreach ($IdenRows as $identity){
            $AssignForm->setReceiverInfo($identity['Name'], htmlentities($identity['Language']),htmlentities($identity['UserID']));
        }
        //AssetInfo
        foreach ($DeviceRows as $device):
            $AssignForm->setAssetInfo(htmlentities($device["Category"]), htmlentities($device["Type"]), htmlentities($device["SerialNumber"]), htmlentities($AssetTag));
        endforeach;
        //KeyInfo
        foreach ($KeyRows as $key){
            $AssignForm->setAssetInfo(htmlentities($key["Category"]),htmlentities($key["Type"]),htmlentities($device["SerialNumber"]), htmlentities($AssetTag));
        }
        $AssignForm->setEmployeeSingInfo($Employee);
        $AssignForm->setITSignInfo($ITEmployee);
        $AssignForm->createPDf();
    }
    /**
     * This function will validate the parameters and trows erros if there are missing
     * @param int $Type
     * @param string $Serial
     * @param int $NrKeys
     * @param int $hasLock
     * @param int $UUID
     * @throws ValidationException
     */
    private function validateParams($Type,$Serial,$NrKeys,$hasLock, $UUID = 0) {
        $errors = array();
        if (empty($Type)){
            $errors[] = 'Please select a Type';
        }
        if (empty($Serial)){
            $errors[] = 'Please enter a Serial Number';
        }
        if (!isset($NrKeys)){
            $errors[] = 'Please enter a Amount of Keys';
        }
        if (!isset($hasLock)){
            $errors[] = 'Please select if the key has a lock';
        }
        if (isset($NrKeys) and !is_numeric($NrKeys)){
            $errors[] = 'Please enter only number in the amount of keys';
        }
        if (!empty($Serial) and !empty($Type) and $this->kensingtonGateway->isUnique($Type, $Serial, $UUID)){
            $errors[] = 'The same key alread exsist';
        }
        if (empty($errors)) {
            return;
        }
        throw new ValidationException($errors);
    }
    /**
     * This function will validate the Assign Params
     * @param string $AssetTag
     * @param int $KeyID
     * @throws ValidationException
     */
    private function validateAssignParams($AssetTag,$KeyID) {
        $errors = array();
        if (empty($AssetTag)){
            $errors[] = 'Please select a Asset';
        }
        if (empty($KeyID)){
            $errors[] = 'Please enter a Key';
        }
        if (empty($errors)) {
            return;
        }
        throw new ValidationException($errors);
    }
    /**
     * This function will validate the release params
     * @param string $AssetTag
     * @param int $KeyID
     * @param string $ITEmployee
     * @param string $Employee
     * @throws ValidationException
     */
    private function validateReleaseParams($AssetTag,$KeyID,$ITEmployee,$Employee) {
        $errors = array();
        if (empty($AssetTag)){
            $errors[] = 'Please select a Asset';
        }
        if (empty($KeyID)){
            $errors[] = 'Please enter a Key';
        }
        if (empty($ITEmployee)){
            $errors[] = 'Please enter a IT employee';
        }
        if (empty($Employee)){
            $errors[] = 'Please enter a employee';
        }
        if (empty($errors)) {
            return;
        }
        throw new ValidationException($errors);
    }
}
