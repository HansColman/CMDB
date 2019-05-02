<?php
require_once 'Service/AccessService.php';
require_once 'LoggerController.php';
require_once 'model/configuration.php';
/**
 * This is the Controller class
 * @author Hans Colman
 * @copyright Hans Colman
 * @abstract
 * @package controller
 */
abstract class Controller {
    /**
     * @var AccessService The AccessService
     */
    protected $accessService = NULL;
    /**
     * @var LoggerController The LoggerController
     */
    protected $loggerController = NULL;
    /**
     * @var configuration The cconfiguration
     */
    protected $config =NULL;
    /**
     * This function is the main function.
     * It will be used to call the other functions.
     * @throws Exception
     */
    abstract function handleRequest();
    /**
     * This function will be used to Edit the given object
     * @throws PDOException
     * @throws ValidationException
     */
    abstract function edit();
    /**
     * This function will be used to give the details of the given object
     */
    abstract function show();
    /**
     * This function will be used to activate the given object.
     * @throws PDOException
     * @throws ValidationException
     */
    abstract function activate();
    /**
     * This function will be used to deactivate the given object.
     * @throws PDOException
     * @throws ValidationException
     */
    abstract function delete();
    /***
     * This function will be used to create a given object
     * @throws PDOException
     * @throws ValidationException
     */
    abstract function save();
    /**
     * This function will be used to get an overview of all objects
     */
    abstract function listAll();
    /**
     * This function will be used to search in all objects
     */
    abstract function search();
    /**
     * The default Constructor
     */
    public function __construct() {
        $this->accessService = new AccessService();
        $this->loggerController = new LoggerController();
        $this->config = new configuration();
    }
    /**
     * This function will redirect to the the given location
     * @param string $location
     */
    public function redirect($location) {
        header('Location: '.$location);
    }
    /**
     * This function will return the date format.
     * @return string
     */
    protected function getDateFormat(){
        return $this->config->getDataFormat();
    }
    /**
     * This function will return the date format.
     * @return string
     */
    protected function getLogDateFormat(){
        return $this->config->getLogDataFormat();
    }
}
