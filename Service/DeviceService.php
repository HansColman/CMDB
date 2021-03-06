<?php
require_once 'Service.php';
require_once 'model/DeviceGateway.php';
/**
 * This is the Service Class for Device
 * @copyright Hans Colman
 * @author Hans Colman
 */
class DeviceService extends Service{
    /**
     * The DeviceGateway
     * @var DeviceGateway
     */
    private $deviceGateway = NULL;
    /**
     * The category of the Device
     * @var string
     */
    private $category = NULL;

    public function __construct() {
        $this->deviceGateway = new DeviceGateway();
    }
    /**
     * This function will set the Category
     * @param string $category
     */
    public function setCategory($category) {
        $this->category = $category;
        $this->deviceGateway->setCategory($category);
    }
    /**
     * {@inheritDoc}
     */
    public function activate($id, $AdminName) {
        $this->deviceGateway->activate($id,$AdminName);
    }
    /**
     * {@inheritDoc}
     */
    public function delete($id, $reason, $AdminName) {
        try {
            $this->validateDeleteParams($reason);
            $this->deviceGateway->delete($id, $reason, $AdminName);
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
        return $this->deviceGateway->selectAll($order);
    }
    /**
     * This function will return all objects per category
     * @param string $order
     * @param string $category
     * @return array
     */
    public function getAllPerCategory($order,$category) {
        return $this->deviceGateway->selectAllPerCategory($order,$category);
    }
	/**
	 * {@inheritDoc}
	 */
    public function getByID($id) {
        return $this->deviceGateway->selectById($id);
    }
    /**
     * This function will create a new Asset
     * @param string $AssetTag The Asset tag of the Asset
     * @param string $SerialNumber The serial number of the Asset
     * @param int $Type The ID of the Asset Type
     * @param string $RAM The amount of RAM
     * @param string $IP The IP address
     * @param string $Name The name of the Asset
     * @param string $MAC The MAC address of the Asset
     * @param string $AdminName The name of the person who did the creation
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($AssetTag,$SerialNumber,$Type,$RAM,$IP,$Name,$MAC,$AdminName){
        try{
            $this->validateParameters($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC);
            $this->deviceGateway->create($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC, $AdminName);
        }  catch (ValidationException $ex){
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will update a given Asset
     * @param string $AssetTag The Asset tag of the Asset
     * @param string $SerialNumber The serial number of the Asset
     * @param int $Type The ID of the Asset Type
     * @param string $RAM The amount of RAM
     * @param string $IP The IP address
     * @param string $Name The name of the Asset
     * @param string $MAC The MAC address of the Asset
     * @param string $AdminName The name of the person who did the update
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($AssetTag,$SerialNumber,$Type,$RAM,$IP,$Name,$MAC,$AdminName){
        try{
            $this->validateParameters($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC);
            $this->deviceGateway->update($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC, $AdminName);
        }  catch (ValidationException $ex){
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will return all AssetTypes for the given category
     * @param string $Category
     * @return array
     */
    public function listAllTypes($Category){
        return $this->deviceGateway->listAllTypes($Category);
    }
    /**
     * This function will return all possibles RAM's
     * @return array
     */
    public function listAllRams(){
        return $this->deviceGateway->listAllRams();
    }
	/**
	 * {@inheritDoc}
	 */
    public function search($search) {
        return $this->deviceGateway->selectBySearch($search);
    }
    /**
     * This function will return any matching row by the given search term
     * @param string $search the search term
     * @param string $category the category of the Device
     */
    public function searchByCategory($search,$category){
       return $this->deviceGateway->selectBySearchAndCategory($search, $category); 
    }
    /**
     * This function will return a list of Assigned identities to a given device
     * @param string $id The AssetTag of the Devive
     * @return array
     */
    public function ListAssignedIdentities($id){
        return $this->deviceGateway->ListAssignedIdentities($id);
    }
    /**
     * This function will return a list of All identities that have no device assinged
     * @param string $AssetTag The AssetTag of the current Device
     * @return array
     */
    public function listAllIdentities($AssetTag){
    	return $this->deviceGateway->listAllIdentities($AssetTag);
    }
    /**
     * This function will assign the AssetTag to an Identity
     * @param string $AssetTag
     * @param int $Identity
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function assign2Identity($AssetTag,$Identity,$AdminName){
    	try{
    		$this->validateAssignParameters($Identity, $AssetTag);
    		$this->deviceGateway->assign2Identity($AssetTag,$Identity,$AdminName);
    	} catch (ValidationException $ex){
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will release the identity from the device
     * @param int $IdenId
     * @param string $id
     * @param string $Employee
     * @param string $ITEmployee
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function releaseIdentity($IdenId,$id,$Employee,$ITEmployee,$AdminName){
        try{
            $this->validateReleaseParams($IdenId, $id, $Employee, $ITEmployee);
            $this->deviceGateway->releaseIdentity($IdenId,$id,$Employee,$ITEmployee,$AdminName);
        }catch (ValidationException $ex){
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will validate the parameters on update and Insert and throws an error when not all required fields are filled in
     * @param string $AssetTag The Asset tag of the Asset
     * @param string $SerialNumber The serial number of the Asset
     * @param int $Type The ID of the Asset Type
     * @param string $RAM The amount of RAM
     * @param string $IP The IP address
     * @param string $Name The name of the Asset
     * @param string $MAC The MAC address of the Asset
     * @throws ValidationException
     */
    private function validateParameters($AssetTag,$SerialNumber,$Type,$RAM,$IP,$Name,$MAC){
        $errors = array();
        if (empty($AssetTag)) {
            $errors[] = 'Please enter AssetTag';
        }
        if (empty($SerialNumber)) {
            $errors[] = 'Please enter SerialNumber';
        }
        if (empty($Type)) {
            $errors[] = 'Please select a type';
        }
        if (!$this->deviceGateway->isAssetTagUnique($AssetTag)){
            $errors[] = 'Asset is not unique';
        }
        //TODO: implement more checks depending on the Category.
        switch ($this->category) {
            case "Laptop":
                if (empty($RAM)){
                    $errors[] = 'Please select a amount of RAM';
                }
                break;
            case "Desktop":
                if (empty($RAM)){
                    $errors[] = 'Please select a amount of RAM';
                }
                break;
            default:
                break;
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    /**
     * This function will validate the parameters needed for assign
     * @param int $identity
     * @param string $AssetTag
     * @throws ValidationException
     */
    private function validateAssignParameters($identity, $AssetTag){
    	$errors = array();
    	if (empty($identity)) {
    		$errors[] = 'Please select an Identity';
    	}
    	if (empty($AssetTag)) {
    		$errors[] = 'Please select an AssetTag';
    	}
    	if ( empty($errors) ) {
    		return;
    	}
    	
    	throw new ValidationException($errors);
    }
    
}
