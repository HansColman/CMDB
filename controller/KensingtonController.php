<?php
require_once 'Controller.php';
require_once 'Service/KensingtonService.php';
require_once 'view/KensingtonView.php';
/**
 * This Class is the Controller for Kensington
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class KensingtonController extends Controller{
    /**
     * @var KensingtonService The KensingtonService
     */
    private $kensingtoneService = NULL;
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart = "Kensington";
    /**
     * The kensingtonView
     * @var KensingtonView
     */
    private $view;
    /**
     * Constroctor
     */
    public function __construct() {        
        $this->kensingtoneService = new KensingtonService();
        $this->Level = $_SESSION["Level"];
        $this->view = new KensingtonView();
        parent::__construct();
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
        	try{
        		$this->kensingtoneService->activate($id,$AdminName);
        		$this->redirect('Kensington.php');
        	}catch (PDOException $e){
        	    $this->view->print_error("Database exception",$e);
        	}
        }else {
            $this->view->print_error("Application error", "You do not access to activate a kensington");
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
        $title = 'Delete Kensington';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->kensingtoneService->delete($id,$Reason,$AdminName);
                $this->redirect('Kensington.php');
                return;
            }  catch (Exception $e){
                $errors = $e->getErrors();
            } catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        }
        $rows = $this->kensingtoneService->getByID($id);
        $this->view->printDelete($title, $errors, $rows, $Reason);
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
        $title = 'Update Kensington';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Serial  = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $NrKeys  = isset($_POST['Keys']) ? $_POST['Keys'] :NULL;
            $hasLock  = isset($_POST['Lock']) ? $_POST['Lock'] :NULL;
            try {
                $this->kensingtoneService->edit($id, $Type, $Serial, $NrKeys, $hasLock, $AdminName);
                $this->redirect("kensington.php");
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }else{
            $rows = $this->kensingtoneService->getByID($id);
            foreach ($rows as $row):
                $Type = $row["Type_ID"];
                $Serial = $row["Serial"];
                $NrKeys = $row["AmountKeys"];
                $hasLock = $row["hasLock"];
            endforeach;
        }
        $types = $this->kensingtoneService->listAllTypes();
        $this->view->print_Update($title, $UpdateAccess, $errors, $Type, $types, $Serial, $NrKeys, $hasLock);
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
            } elseif ($op == "activate") {
                $this->activate();
            } elseif ($op == "assign") {
                $this->assign();
            } elseif ($op == "search") {
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
	 * @see Controller::listAll()
	 */
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
        //$orderby = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->kensingtoneService->getAll($orderby);
        $this->view->print_All($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess,$AssignAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new Kensington';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        $Type ='';
        $Serial = '';
        $NrKeys = '';
        $hasLock = '';
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Serial  = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $NrKeys  = isset($_POST['Keys']) ? $_POST['Keys'] :NULL;
            $hasLock  = isset($_POST['Lock']) ? $_POST['Lock'] :NULL;  
            try {
                $this->kensingtoneService->add($Type,$Serial,$NrKeys,$hasLock,$AdminName);
                $this->redirect("kensington.php");
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $types = $this->kensingtoneService->listAllTypes();
        $this->view->print_Create($title, $AddAccess, $errors, $types, $Serial, $NrKeys, $hasLock);
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
            $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
            $rows = $this->kensingtoneService->search($search);
            $this->view->print_Searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $AssignAccess, $search);
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::show()
	 */
    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $IdenViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "DeviceOverview");
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $rows = $this->kensingtoneService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('kensington', $id);
        $idenrows = $this->kensingtoneService->listAssets($id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_Details($ViewAccess, $AddAccess, $rows, $IdenViewAccess, $idenrows, $logrows, $LogDateFormat);
    }
}
