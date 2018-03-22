<?php
require_once 'Service.php';
require_once 'model/TokenGateway.php';
/**
 * This is the Service Class for Device
 * @copyright Hans Colman
 * @author Hans Colman
 */
class TokenService extends Service{
    /**
     * The TokenGateway
     * @var TokenGateway
     */
    private $tokenModel = NULL;
    /**
     * Constructor
     */
    public function __construct() {
        $this->tokenModel = new TokenGateway();
    }
    /**
     * {@inheritDoc}
     */
    public function activate($id, $AdminName) {
        try{
            $this->tokenModel->activate($id, $AdminName);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
    /**
     * {@inheritDoc}
     */
    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->tokenModel->delete($id, $reason, $AdminName);
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
        return $this->tokenModel->selectAll($order);
    }
    /**
     * {@inheritDoc}
     */
    public function getByID($id) {
        return $this->tokenModel->selectById($id);
    }
    /**
     * {@inheritDoc}
     */
    public function search($search) {
        return $this->tokenModel->selectBySearch($search);
    }
    /**
     * This function will create a new Token
     * @param string $assetTag
     * @param string $serialNumber
     * @param int $type
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($assetTag, $serialNumber, $type,$AdminName) {
        try{
            $this->validateParameters($assetTag, $serialNumber, $type);
            $this->tokenModel->create($assetTag, $serialNumber, $type, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will update an given Token
     * @param string $AssetTag
     * @param string $SerialNumber
     * @param int $Type
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($AssetTag, $SerialNumber, $Type, $AdminName){
        try{
            $this->validateParameters($AssetTag, $SerialNumber, $Type, TRUE);
            $this->tokenModel->update($AssetTag,$SerialNumber,$Type,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }  catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will return all Types
     * @return Array
     */
    public function listAllTypes(){
        return $this->tokenModel->listAllTokenCategories();
    }
    /**
     * This function will return the list of assigned Identities
     * @param string $assetTag
     * @return Array
     */
    public function listOfAssignedIdentities($assetTag){
        return $this->tokenModel->listOfAssignedIdentities($assetTag);
    }
    /**
     * This function will validate the paramaters and throws errors when not all required fields are filled in
     * @param string $assetTag
     * @param string $serialNumber
     * @param int $type
     * @param boolean $update
     * @throws ValidationException
     */
    private function validateParameters($assetTag, $serialNumber, $type, $update = FALSE){
        $errors = array();
        if (empty($type)) {
            $errors[] = 'Please select a Type';
        }
        if (empty($assetTag)) {
            $errors[] = 'Please enter a assetTag';
        }
        if (empty($serialNumber)) {
            $errors[] = 'Please enter a serial number';
        }
        if (!$update){
            if (!$this->tokenModel->isAssetTagUnique($assetTag)){
                $errors[] = 'Asset is not unique';
            }
        }
        if (!$this->tokenModel->isSerialUnique($serialNumber)){
            $errors[] = 'SerialNumber is not unique';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
