<?php
require_once 'Controller.php';
require_once 'Service/KensingtonService.php';
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
     * Constroctor
     */
    public function __construct() {        
        $this->kensingtoneService = new KensingtonService();
        $this->Level = $_SESSION["Level"];
        parent::__construct();
    }
    /**
     * {@inheritDoc}
     */
    public function activate() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AdminName = $_SESSION["WhoName"];
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        if ($ActiveAccess){
        	try{
        		$this->kensingtoneService->activate($id,$AdminName);
        		$this->redirect('Kensington.php');
        	}catch (PDOException $e){
        		$this->showError("Database exception",$e);
        	}
        }else {
        	$this->showError("Application error", "You do not access to activate a kensington");
        }
        
    }
	/**
	 * {@inheritDoc}
	 * @uses view/deleteKensington_form.php
	 */
    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
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
            	$this->showError("Database exception",$e);
            }
        }
        $rows = $this->kensingtoneService->getByID($id);
        include 'view/deleteKensington_form.php';
    }
	/**
	 * {@inheritDoc}
	 * @uses view/updateKensington_form.php
	 */
    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
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
                $this->showError("Database exception",$e);
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
        include 'view/updateKensington_form.php';
    }
	/**
	 * {@inheritDoc}
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
                $this->showError("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        }
    }
	/**
	 * {@inheritDoc}
	 * @uses view/kensingtons.php
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
        include 'view/kensingtons.php';
    }
	/**
	 * {@inheritDoc}
	 * 
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
                $this->showError("Database exception",$e);
            }
        }
        $types = $this->kensingtoneService->listAllTypes();
        include 'view/newKensington_form.php';
    }
	/**
	 * {@inheritDoc}
	 * @uses view/searched_kensingtons.php
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
            include 'view/searched_kensingtons.php';
        }
    }
	/**
	 * {@inheritDoc}
	 * @uses view/kensington_overview.php
	 */
    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $IdenViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "DeviceOverview");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->kensingtoneService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('kensington', $id);
        $idenrows = $this->kensingtoneService->listAssets($id);
        include 'view/kensington_overview.php';
    }
}
