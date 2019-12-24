<?php
require_once 'Service.php';
require_once 'model/SubsriptionGateway.php';
class SubscriptionService extends Service
{
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
        return $this->getByID($id);
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($id, $AdminName)
    {
        
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
}

