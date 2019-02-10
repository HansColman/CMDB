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
     */
    public function getByID($id) {
        return $this->Model->selectById($id);
    }
    /**
     * 
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search)
    {
        
    }
    public function listAllTypes(){
        return $this->Model->ListAllTypes();
    }

}