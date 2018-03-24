<?php
require_once 'Service/AccessService.php';
require_once 'Controller.php';
/**
 * This Class is the Controller for Menu
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class MenuController{
    /**
     * @var AccessService The AccessService
     */
    private $accessService = NULL;
    /**
     * Constructor
     */
    public function __construct() {
        $this->accessService = new AccessService();
    }
    /**
     * This is the main class of the controller
     */
    public function handleRequest(){
        $this->listMenu();
    }
    /**
     * This function will return the menu on the top level.
     * @uses view/menu.php
     */
    private function listMenu(){
        $Level = $_SESSION["Level"];
//        $Action = "Read";
        $FirstMenu = $this->accessService->getFirstLevel();
        include 'view/menu.php';
    }
}
