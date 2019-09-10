<?php
require_once 'Service.php';
require_once 'model/SubscriptionTypeGateway.php';

class SubscriptionTypeService extends Service
{
    private $model;
    public function __construct(){
        $this->model = new SubscriptionTypeGateway();
    }
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search){
        return $this->model->selectBySearch($search);
    }
    /**
     * {@inheritDoc}
     * @see Service::getAll()
     */
    public function getAll($order){
        return $this->model->selectAll($order);
    }
    /**
     * {@inheritDoc}
     * @see Service::getByID()
     */
    public function getByID($id){
        return $this->model->selectById($id);
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($id, $AdminName){
        $this->model->activate($id, $AdminName);
    }
    /**
     * {@inheritDoc}
     * @see Service::delete()
     */
    public function delete($id, $reason, $AdminName){
        try{
            $this->validateDeleteParams($reason);
            $this->model->delete($id, $reason, $AdminName);
        }catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This funcition will create a new subscription type
     * @param string $Type
     * @param string $Description
     * @param string $Provider
     * @param int $Category
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($Type, $Description, $Provider,$Category,$AdminName) {
        try{
            $this->validateCreateParams($Type, $Description, $Provider, $Category);
            $this->model->Create($Type, $Description, $Provider, $Category, $AdminName);
        }catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function Edit($id,$Type,$Description,$Provider,$Category,$AdminName){
        try {
            $this->validateCreateParams($Type, $Description, $Provider, $Category);
            $this->model->Edit($id,$Type,$Description,$Provider,$Category,$AdminName);
        }catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * THis function will validate the given params
     * @param string $Type
     * @param string $Description
     * @param string $Provider
     * @param int $Category
     * @throws ValidationException
     */
    private  function validateCreateParams($Type, $Description, $Provider,$Category) {
        $errors = array();
        if (empty($Type)){
            $errors[] = 'Please enter a Type';
        }
        if (empty($Description)){
            $errors[] = 'Please enter a Description';
        }
        if (empty($Provider)){
            $errors[] = 'Please enter a Provider';
        }
        if (empty($Category)){
            $errors[] = 'Please select a Category';
        }
        if (empty($errors)) {
            return;
        }
        throw new ValidationException($errors);
    }
}

