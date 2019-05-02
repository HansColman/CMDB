<?php
require_once 'Controller.php';
require_once 'Service/AccessService.php';
require_once 'view/PermissionView.php';
/**
 * This Class is the Controller for Permission
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class PermissionController extends Controller{
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart ="Permissions";
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level = NULL;
    /**
     * The PermissionView
     * @var PermissionView
     */
    private $view;
    /**
     * 
     */
    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->view = new PermissionView();
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
     * @see Controller::activate()
     */
    public function activate() {
    	$id = isset($_GET['id'])?$_GET['id']:NULL;
    	if ( !$id ) {
    	    $this->view->print_error("Application error","Required field is not set!");
    	}
    	$AdminName = $_SESSION["WhoName"];
    	$ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
    	if ($ActiveAccess){
    		$this->accessService->activate($id, $AdminName);
    	} else {
    	    $this->view->print_error("Application error", "You do not access to activate a application");
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
    	$title = 'Delete Permission';
    	$errors = array();
    	$AdminName = $_SESSION["WhoName"];
    	$reason = '';
    	if (isset($_POST['form-submitted'])){
    		try {
    		    $reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
    			$this->accessService->delete($id, $reason, $AdminName);
    			 $this->redirect("Permission.php");
                return;
    		} catch (ValidationException $e){
    		    $errors = $e->getErrors();
    		} catch (PDOException $ex){
    		    $this->view->print_error("Database exception",$ex);
    		}
    	}
    	$rows = $this->accessService->getByID($id);
    	$this->view->print_delete($title, $errors, $rows, $reason);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::edit()
	 */
    public function edit() {
    	$id = isset($_GET['id'])?$_GET['id']:NULL;
    	if ( !$id ) {
    		throw new Exception('Internal error.');
    	}
    	$AdminName = $_SESSION["WhoName"];
    	$title = "Update permission";
    	$errors = array();
    	$UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
    	if ( isset($_POST['form-submitted'])) {
    	    $Level = isset($_POST['Level']) ? $_POST['Level'] :NULL;
    	    $Menu = isset($_POST['menu']) ? $_POST['menu'] :NULL;
    	    $Perm = isset($_POST['permission']) ? $_POST['permission'] :NULL;
    	    //TODO: Implement!! Do not forget the wrights
    	}else{
    	   $rows = $this->accessService->getByID($id);
    	   foreach ($rows as $row){
    	       $Level = $row["Level"];
    	       $Menu = $row["Menu_id"];
    	       $Perm= $row["perm_id"];
    	   }
    	}
    	$Menus = $this->accessService->listSecondLevel();
    	$Perms = $this->accessService->listAllPermissions();
    	$Levels = $this->accessService->listAllLevels();	
    	$this->view->print_Update($UpdateAccess, $title, $errors, $Level, $Levels, $Menu, $Menus, $Perm, $Perms);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::listAll()
	 */
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignLevel");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->accessService->getAll($orderby);
        $this->view->print_All($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $InfoAccess,$AssignAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $AdminName = $_SESSION["WhoName"];
        $title = "Create Permission";
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Level = isset($_POST['Level']) ? $_POST['Level'] :NULL;
            $menu = isset($_POST['menu']) ? $_POST['menu'] :NULL;
            $permission = isset($_POST['permission']) ? $_POST['permission'] :NULL;
            try {
                $this->accessService->create($Level,$menu,$permission,$AdminName);
                $this->redirect("Permission.php");
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $Menus = $this->accessService->listSecondLevel();
        $Perms = $this->accessService->listAllPermissions();
        $Levels = $this->accessService->listAllLevels();
        $this->view->printCreateForm($title, $AddAccess, $errors, $Levels, $Menus, $Perms);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::show()
	 */
    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $rows = $this->accessService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('role_perm', $id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_details($ViewAccess, $AddAccess, $rows, $logrows, $LogDateFormat);
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
            $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
            $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignLevel");
            $rows = $this->accessService->search($search);
            $this->view->print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $InfoAccess, $search,$AssignAccess);
        }
    }
}
