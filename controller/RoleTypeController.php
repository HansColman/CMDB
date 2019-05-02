<?php
require_once 'Controller.php';
require_once 'Service/RoleTypeService.php';
require_once 'view/TypeView.php';
/**
 * This Class is the Controller for Role
 * @author Hans Colman
 * @copyright Hans Colman
 * @abstract
 * @package controller
 */
class RoleTypeController extends Controller{
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart ="Role Type";
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * @var RoleTypeService The RoleTypeService
     */
    private $roleTypeService = NULL;
    /**
     * The RoleTypeView
     * @var TypeView
     */
    private $view;
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->roleTypeService = new RoleTypeService();
        $this->view = new TypeView();
        $this->view->setType("Role");
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
        		$this->roleTypeService->activate($id, $AdminName);
        		$this->redirect('RoleType.php');
        	}catch (PDOException $e){
        	    $this->view->print_error("Database exception",$e);
        	}
        } else {
            $this->view->print_error("Application error", "You do not access to activate a role type");
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
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $title = 'Delete Role Type';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->roleTypeService->delete($id, $Reason, $AdminName);
                //$_POST = array();
                $this->redirect('RoleType.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $rows = $this->roleTypeService->getByID($id);
        $this->view->print_deleteForm($title, $errors, $rows, $Reason, $DeleteAccess);
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
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $title = 'Update Role Type';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type = '';
            $Description = '';
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try{
                $this->roleTypeService->update($id,$Type,$Description,$AdminName);
                $this->redirect('RoleType.php');
                return;
                
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }  else {
            $rows = $this->roleTypeService->getByID($id);
            foreach($rows as $row){
                $Type = $row["Type"];
                $Description = $row["Description"];
            }
        }
        $this->view->print_Update($title, $UpdateAccess, $errors, $Type, $Description);
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
        $rows = $this->roleTypeService->getAll($orderby);
        $this->view->print_ListAll($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new Role Type';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Type = '';
        $Description = '';
        
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try {
                $this->roleTypeService->create($Type, $Description, $AdminName);
                $this->redirect('RoleType.php');
                return;           
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $this->view->print_CreateForm($title, $errors, $Type, $Description, $AddAccess);
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
        $rows = $this->roleTypeService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('roletype', $id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_overview($ViewAccess, $AddAccess, $rows, $logrows, $LogDateFormat);
    }
    /**
     * This function returns all active Role Types
     * @return array
     */
    public function listAllType(){
        return $this->roleTypeService->listAllType();
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
            $rows = $this->roleTypeService->search($search);
            $this->view->print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $search);
        }
    }

}
