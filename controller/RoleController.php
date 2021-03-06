<?php
require_once 'Controller.php';
require_once 'RoleTypeController.php';
require_once 'Service/RoleService.php';
require_once 'view/RoleView.php';
/**
 * This Class is the Controller for Role
 * @author Hans Colman
 * @copyright Hans Colman
 * @abstract
 * @package controller
 */
class RoleController extends Controller{
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart ="Role";
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level = NULL;
    /**
     * @var RoleService The RoleService
     */
    private $roleService = NULL;
    /**
     * @var RoleTypeController The RoleTypeController
     */
    private $roleTypeController = NULL;
    /**
     * This is the RoleView
     * @var RoleView
     */
    private $view;
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->roleService = new RoleService();
        $this->roleTypeController = new RoleTypeController();
        $this->view = new RoleView();
    }
   	/**
   	 * {@inheritDoc}
   	 * @see Controller::handleRequest()
   	 */
    public function handleRequest() {
        $op = isset($_GET['op'])?$_GET['op']:NULL;
        try {
            if ( !$op || $op == 'list' ) {
                $this->listAll();
            } elseif ( $op == 'new' ) {
                $this->save();
            } elseif ( $op == 'delete' ) {
                $this->delete();
            } elseif ( $op == 'show' ) {
                $this->show();
            } elseif ( $op == 'edit' ) {
                $this->edit();
            }elseif ($op == "activate") {
                $this->activate();
            }elseif ($op == "assign"){
                $this->assign();
            }elseif ($op == "search") {
                $this->search();
            } else {
                $this->view->print_error("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->view->print_error("Application error", $e->getMessage());
        } 
    }
    /**
     * {@inheritDoc}
     */
    public function activate() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AdminName = $_SESSION["WhoName"];
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        if ($ActiveAccess){
	        try{
	            $this->roleService->activate($id, $AdminName);
	            $this->redirect('Role.php');
	        } catch (PDOException $e){
	            $this->view->print_error("Database exception",$e);
	        }
        }else {
            $this->view->print_error("Application error", "You do not access to activate a role");
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::delete()
	 */
    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Delete Role';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->roleService->delete($id,$Reason,$AdminName);
                $this->redirect('Role.php');
                return;
            }  catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        } 
        $rows = $this->roleService->getByID($id);   
        $this->view->print_delete($title, $errors, $rows, $Reason);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::edit()
	 */
    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Update Role';
        $UpdateAccess =$this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Name = isset($_POST["Name"]) ? $_POST["Name"] : NULL;
            $Description = isset($_POST["Description"]) ? $_POST["Description"] : NULL;
            $Type = isset($_POST["type"]) ? $_POST["type"] : NULL;
            try{
                $this->roleService->update($id,$Name, $Description, $Type, $AdminName);
                $this->redirect('Role.php');
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }else{
            $rows = $this->roleService->getByID($id); 
            foreach ($rows as $row){
                $Name = $row["Name"];
                $Description = $row["Description"];
                $Type = $row["Type_ID"];
            }
        }
        $types = $this->roleTypeController->listAllType();
        $this->view->print_UpdateForm($title, $errors, $UpdateAccess, $Name, $Description, $Type, $types);
    }
    /**
     * {@inheritDoc}
     * @see Controller::listAll()
     */
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->roleService->getAll($orderby);
        $this->view->print_ListAll($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new Role';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $AdminName = $_SESSION["WhoName"];
        $Name = "";
        $Description = "";
        $Type = "";
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Name = isset($_POST["Name"]) ? $_POST["Name"] : NULL;
            $Description = isset($_POST["Description"]) ? $_POST["Description"] : NULL;
            $Type = isset($_POST["type"]) ? $_POST["type"] : NULL;
            try{
                $this->roleService->create($Name, $Description, $Type, $AdminName);
                $this->redirect('Role.php');
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $types = $this->roleTypeController->listAllType();
        $this->view->print_CreateForm($title, $errors, $AddAccess, $Name, $Description, $types);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::show()
	 */
    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->roleService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('role', $id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_Overview($ViewAccess, $AddAccess, $rows, $logrows, $LogDateFormat);
    }
    /**
     * This function will assign a Role to TBD
     * TODO: Implement
     */
    public function assign(){
        
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::search()
	 */
    public function search() {
        $search = isset($_POST['search']) ? $_POST['search'] :NULL;
        if (empty($search)){
            $this->listAll();
        }  else {
            $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
            $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
            $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
            $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
            $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
            $rows = $this->roleService->search($search);
            $this->view->print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $search);
        }
    }
}
