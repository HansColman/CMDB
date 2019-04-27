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
     */
    public function activate($id, $AdminName) {
        
    }
    /**
     * {@inheritDoc}
     */
    public function delete($id, $reason, $AdminName) {
        try {
           
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
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
        
    }
    /**
     * 
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
    public function eddit($IMEI,$type,$AdminName){
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
     * 
     * @param int $id
     * @return array
     */
    public function getAssignedIdenty($id){
        return $this->Model->getAssignedIdenty($id);
    }
    /**
     * 
     * @param int $id
     * @return array
     */
    public function getSubsriptions($id){
        return $this->Model->getSubsriptions($id);
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
}