<?php
require_once 'Service.php';
require_once 'ValidationException.php';
require_once 'model/MobileGateway.php';

/**
 * This is the Service Class for Role
 * @copyright Hans Colman
 * @author Hans Colman
 */
class MobileService extends Service{
    /**
     * The model
     */
    private $Model = NULL;
    /**
     * Constructor
     */
    public function __construct() {
        $this->Model = new MobileGateway();
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($id, $AdminName) {
        
    }
    /**
     * {@inheritDoc}
     * @see Service::delete()
     */
    public function delete($id, $reason, $AdminName) {
        try {
           $this->validateDeleteParams($reason);
           $this->Model->delete($id, $reason, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::getAll()
     */
    public function getAll($order) {
        return $this->Model->selectAll($order);
    }
    /**
     * {@inheritDoc}
     * @see Service::getByID()
     */
    public function getByID($id) {
        return $this->Model->selectById($id);
    }
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search)
    {
        return $this->Model->selectBySearch($search);
    }
    /**
     * This will return all subscription
     * @return array
     */
    public function ListAllSubsription(){
        return $this->Model->ListAllSubscriptions();    
    }
    /**
     * This will return all types
     * @return array
     */
    public function listAllTypes(){
        return $this->Model->ListAllTypes();
    }
    /**
     * This function will create a new Mobile
     * @param int $IMEI
     * @param int $type
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function add($IMEI,$type,$AdminName) {
        try{
            $this->validateParams($IMEI,$type);
            $this->Model->add($IMEI,$type,$AdminName);
        }catch (ValidationException $e){
            throw $e;
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This function will edit the mobile
     * @param int $IMEI
     * @param int $type
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function edit($IMEI,$type,$AdminName){
        try {
            $this->validateParams($IMEI, $type);
            $this->Model->edit($IMEI,$type,$AdminName);
        } catch (ValidationException $e){
            throw $e;
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This function will return all assigned Identies
     * @param int $id
     * @return array
     */
    public function getAssignedIdenty($id){
        return $this->Model->getAssignedIdenty($id);
    }
    /**
     * This function will return all Subsriptions
     * @param int $id
     * @return array
     */
    public function getSubsriptions($id){
        return $this->Model->getSubsriptions($id);
    }
    /**
     * This function will list all Identity that are not linked to a Device or this device.
     * @param int $IMEI
     * @return array
     */
    public function listAllIdentities($IMEI){
        return $this->Model->listAllIdentities($IMEI);
    }
    /**
     * This function will assing an Identity to a Mobile
     * @param int $IMEI
     * @param int $Identity
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function assingIdentity($IMEI,$Identity,$AdminName){
        try {
            $this->validateAssignParams($IMEI,$Identity);
            $this->Model->assingIdentity($IMEI,$Identity,$AdminName);
        }catch (ValidationException $e){
            throw $e;
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This will assign the subscription
     * @param int $IMEI
     * @param int $SubID
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function assignSubscription($IMEI,$SubID,$AdminName){
        try {
            $this->validateAssignSubParams($IMEI,$SubID);
            $this->Model->assingSubscription($IMEI, $SubID, $AdminName);
        }catch (ValidationException $e){
            throw $e;
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This function will release the Identity from the mobile
     * @param int $IMEI
     * @param int $IdenID
     * @param string $Employee
     * @param string $ITEmployee
     * @param string $AdminName
     */
    public function releaseIdentity($IMEI,$IdenID,$Employee,$ITEmployee,$AdminName){
        try {
            $this->validateReleaseParams($IdenID, $IMEI, $Employee, $ITEmployee);
            $this->Model->releaseIdenity($IMEI, $IdenID, $AdminName);
        }catch (ValidationException $e){
            throw $e;
        }catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This function will check if all required fields are filled
     * @param int $IMEI
     * @param int $type
     * @throws ValidationException
     */
    private function validateParams($IMEI,$type){
        $errors = array();
        if (empty($type)) {
            $errors[] = 'Please select a Type';
        }
        if (empty($IMEI)){
            $errors[] = 'Please enter a IMEI';
        }
        if ($this->Model->CheckDoubleEntry($IMEI, $type)){
            $errors[] = 'The same Account Type exist in the Application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    /**
     * This function will validate the assing params
     * @param int $IMEI
     * @param int $Identity
     * @throws ValidationException
     */
    private function validateAssignParams($IMEI, $Identity){
        $errors = array();
        if (empty($Identity)) {
            $errors[] = 'Please select a Identity';
        }
        if (empty($IMEI)){
            $errors[] = 'Please enter a IMEI';
        }
        if ( empty($errors) ) {
            return;
        }        
        throw new ValidationException($errors);
    }
    /**
     * This function will validate the assign Subscription params 
     * @param int $IMEI
     * @param int $SubID
     * @throws ValidationException
     */
    private function validateAssignSubParams($IMEI,$SubID) {
        $errors = array();
        if (empty($SubID)) {
            $errors[] = 'Please select a Subscription';
        }
        if (empty($IMEI)){
            $errors[] = 'Please enter a IMEI';
        }
        if ( empty($errors) ) {
            return;
        }
        throw new ValidationException($errors);
    }
}