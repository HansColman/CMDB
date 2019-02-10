<?php
require_once 'Service.php';
require_once 'model/AssetTypeGateway.php';
/**
 * This is the Service Class for AssetType
 * @copyright Hans Colman
 * @author Hans Colman
 */
class AssetTypeService extends Service{
    /**
     * The AssetTypeGateway
     * @var AssetTypeGateway
     */
    private $assetTypeGateway = NULL;
    
    public function __construct() {
        $this->assetTypeGateway = new AssetTypeGateway();
    }
	/**
	 * {@inheritDoc}
	 */
    public function activate($id, $AdminName) {
        try{
            $this->assetTypeGateway->activate($id, $AdminName);
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
            $this->assetTypeGateway->delete($id, $reason, $AdminName);
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
        return $this->assetTypeGateway->selectAll($order);
    }
    /**
     * {@inheritDoc}
     */
    public function getByID($id) {
        return $this->assetTypeGateway->selectById($id);
    }
    /**
     * This function will create a new AssetType
     * @param int $Category The category of the Asset Type
     * @param string $Vendor The name of the vendor of the Asset Type
     * @param string $Type The name of the asset type
     * @param string $AdminName The name of the person who did the creation
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($Category,$Vendor,$Type,$AdminName){
        try{
            $this->validateParameters($Category, $Vendor, $Type);
            $this->assetTypeGateway->create($Category, $Vendor, $Type, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            print $e;
        }
    }
    /**
     * This function will update a given Asset Type
     * @param int $UUID The unique ID of the Asset Type
     * @param int $Category The category of the Asset Type
     * @param string $Vendor The name of the vendor of the Asset Type
     * @param string $Type The name of the asset type
     * @param string $AdminName The name of the person who did the update
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($UUID, $Category,$Vendor,$Type,$AdminName) {
        try{
            $this->validateParameters($Category, $Vendor, $Type);
            $this->assetTypeGateway->update($UUID,$Category, $Vendor, $Type, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function All Categories
     * @return array
     */
    public function listAllCategories(){
        return $this->assetTypeGateway->getAllCategories();
    }
    /**
     * {@inheritDoc}
     */
    public function search($search) {
        return $this->assetTypeGateway->selectBySearch($search);
    }
    /**
     * This function will validate the parameters
     * @param int $Category The category of the Asset Type
     * @param string $Vendor The name of the vendor of the Asset Type
     * @param string $Type The name of the asset type
     * @throws ValidationException
     */
    private function validateParameters($Category,$Vendor,$Type){
        $errors = array();
        if (empty($Category)) {
            $errors[] = 'Please select a Category';
        }
        if (empty($Vendor)){
            $errors[] = 'Please enter a Vendor';
        }
        if (empty($Type)){
            $errors[] = 'Please enter a Type';
        }
        if ($this->assetTypeGateway->CheckDoubleEntry($Category,$Vendor, $Type)){
            $errors[] = 'The same Asset Type exist in the Application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
