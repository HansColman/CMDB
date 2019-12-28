<?php
require_once 'Service.php';
require_once 'model/SubsriptionGateway.php';
class SubscriptionService extends Service
{
    /**
     * The model
     * @var SubsriptionGateway
     */
    private $model;
    
    public function __construct()
    {
        $this->model = new SubsriptionGateway();
    }
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search)
    {
        return $this->model->selectBySearch($search);
    }
    /**
     * {@inheritDoc}
     * @see Service::getAll()
     */
    public function getAll($order)
    {
        return $this->model->selectAll($order);
    }
    /**
     * {@inheritDoc}
     * @see Service::getByID()
     */
    public function getByID($id)
    {
        return $this->model->selectById($id);
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($id, $AdminName)
    {
        try{
            $this->model->activate($id, $AdminName);
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::delete()
     */
    public function delete($id, $reason, $AdminName)
    {
        try {
            $this->validateDeleteParams($reason);
            $this->model->delete($id, $reason, $AdminName);
        } catch (ValidationException $e) {
            throw $e;
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This function will create a new Subscription
     * @param string $PhoneNumber
     * @param int $Type
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($PhoneNumber, $Type,$AdminName) {
        try{
            $this->validateParams($PhoneNumber, $Type);
            $this->model->create($PhoneNumber, $Type, $AdminName);
        }catch (ValidationException $e){
            throw $e;
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This function will return all active subscriptions
     * @return array
     */
    public function getAllSubscriptions(){
        return $this->model->getAllSubscriptionTypes();
    }
    /**
     * 
     * @param int $id
     * return array
     */
    public function getAssignedIdenity($id){
        return $this->model->getAssignedIdenity($id);
    }
    /**
     * 
     * @param int $id
     * return array
     */
    public function getAssignedMobile($id){
        return $this->model->getAssignedMobile($id);
    }
    /**
     * This function will return the list of all Identities that does not have any subscription assigned
     * @param int $uuid
     * @return array
     */
    public function listAllIdentities($uuid){
        return $this->model->listAllIdentities($uuid);    
    }
    /**
     * This function will return the list of all Mobiles that does not have any subscription assigned
     * @param int $uuid
     * @return array
     */
    public function listAllMobiles($uuid){
        return $this->model->listAllMobiles($uuid);    
    }
    /**
     * This function will assign a mobile or an Identity
     * @param int $id
     * @param int $cat
     * @param int $Identity
     * @param int $IMEI
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function assign($id,$cat,$Identity,$IMEI,$AdminName){
        try {
            $this->validateAssignParams($cat, $Identity, $IMEI);
            $this->model->assign($id, $cat, $Identity, $IMEI, $AdminName);
        } catch (ValidationException $e) {
            throw $e;
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This function will validate the params
     * @param string $phonenumber
     * @param int $type
     * @param int $uuid 
     * @throws ValidationException
     */
    private function validateParams($phonenumber, $type,$uuid = 0) {
        $errors = array();
        if (empty($phonenumber)) {
            $errors[] = 'Please fill in an PhoneNumber';
        }
        if (empty($type)){
            $errors[] = 'Please select a Subcription Type';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    /**
     * This function will validate the Assign Params
     * @param int $cat
     * @param int $Identity
     * @param int $IMEI
     * @throws ValidationException
     */
    private function validateAssignParams($cat,$Identity,$IMEI) {
        $errors = array();
        if ($cat == 4 and empty($Identity)) {
            $errors[] = 'Please select an Identity';
        }
        if ($cat == 3 and empty($IMEI)){
            $errors[] = 'Please select an Mobile';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}

