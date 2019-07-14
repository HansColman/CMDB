<?php
require_once 'Controller.php';
require_once 'Service/DeviceService.php';
require_once 'view/DevicesView.php';
/**
 * This is the Controller class for Devices
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class DeviceController extends Controller{
    /**
     * @var string The Category of the Device
     */
    private $Category = NULL;
    /**
     * @var DeviceService The DeviceService
     */
    private $deviceService = NULL;
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart = "Devices";
    /**
     * This is the DevicesView
     * @var DevicesView
     */
    private $view;
    /**
     * The default contructor
     */
    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->deviceService = new DeviceService();
        $this->Category = $_SESSION["Category"];
        $this->deviceService->setCategory($this->Category);
        $this->view = new DevicesView($this->Category);
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
            }  elseif ($op == "assignform") {
                $this->assignform();
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
        $ActiveAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Activate");
        if ($ActiveAccess){
        	try{
            	$this->deviceService->activate($id,$AdminName);
            	$this->redirect('Devices.php?Category='.$this->Category);
        	}catch (PDOException $e){
        	    $this->view->print_error("Database exception",$e);
        	}
        }  else {
            $this->view->print_error("Application error", "You do not access to activate a ".$this->Category);
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
        $title = 'Delete '.$this->Category;
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->deviceService->delete($id,$Reason,$AdminName);
                $this->redirect('Devices.php?Category='.$this->Category);
                return;
            }  catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        } 
        $rows = $this->deviceService->getByID($id);
        $this->view->print_deleteForm($title, $errors, $rows, $Reason);
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
        $title = 'Update '.$this->Category;
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        $UpdateAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Update");
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            print_r($_POST);
            $AssetTag = isset($_POST['AssetTag']) ? $_POST['AssetTag'] :NULL;
            $SerialNumber = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $Type = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $RAM = isset($_POST['RAM']) ? $_POST['RAM'] :NULL;
            $IP = isset($_POST['IP']) ? $_POST['IP'] :NULL;
            $Name = isset($_POST['Name']) ? $_POST['Name'] :NULL;
            $MAC = isset($_POST['MAC']) ? $_POST['MAC'] :NULL;
            try{
                $this->deviceService->update($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC, $AdminName);
                $this->redirect('Devices.php?Category='.$this->Category);
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }else{
            $rows = $this->deviceService->getByID($id);
            foreach ($rows as $row){
                $AssetTag = $row["AssetTag"];
                $SerialNumber = $row["SerialNumber"];
                $Type = $row["Type_ID"];
                $RAM = $row["RAM"];
                $IP = $row["IP_Adress"];
                $Name = $row["Name"];
                $MAC = $row["MAC"];
            }
        }
        $typerows = $this->deviceService->listAllTypes($this->Category);
        $Ramrows = $this->deviceService->listAllRams();
        $this->view->print_UpdateForm($title, $errors, $UpdateAccess, $AssetTag, $SerialNumber, $Type, $typerows, $Name, $MAC, $IP, $RAM, $Ramrows);
    }
    /**
     * {@inheritDoc}
     * @see Controller::listAll()
     */
    public function listAll() {
        $title = $this->Category."s";
        $AddAccess= $this->accessService->hasAccess($this->Level, $this->Category , "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Update");
        $AssignAccess= $this->accessService->hasAccess($this->Level, $this->Category, "AssignIdentity");
        //$orderby = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->deviceService->getAllPerCategory($orderby,$this->Category);
        $this->view->print_ListAll($title, $AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignAccess, $InfoAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new '.$this->Category;
        $AddAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Add");
        $AdminName = $_SESSION["WhoName"];
        $AssetTag = '';
        $SerialNumber = '';
        $Type = '';
        $RAM = '';
        $IP = '';
        $Name = '';
        $MAC = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $AssetTag = isset($_POST['AssetTag']) ? $_POST['AssetTag'] :NULL;
            $SerialNumber = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $Type = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $RAM = isset($_POST['RAM']) ? $_POST['RAM'] :NULL;
            $IP = isset($_POST['IP']) ? $_POST['IP'] :NULL;
            $Name = isset($_POST['Name']) ? $_POST['Name'] :NULL;
            $MAC = isset($_POST['MAC']) ? $_POST['MAC'] :NULL;
            try {
                $this->deviceService->create($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC, $AdminName);
                $this->redirect('Devices.php?Category='.$this->Category);
                return;
            } catch (ValidationException $ex) {
               $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $typerows = $this->deviceService->listAllTypes($this->Category);
        $Ramrows = $this->deviceService->listAllRams();
        $this->view->print_CreateForm($title, $AddAccess, $errors, $typerows, $AssetTag, $SerialNumber, $Name, $MAC, $IP, $Ramrows);
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
        $AddAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, $this->Category, "Read");
        $IdenViewAccess = $this->accessService->hasAccess($this->Level, $this->Category, "IdentityOverview");
        $rows = $this->deviceService->getByID($id);
        $idenrows = $this->deviceService->ListAssignedIdentities($id);
        $logrows = $this->loggerController->listAllLogs('devices', $id);
        $title = $this->Category. ' Overview';
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_overview($title, $ViewAccess, $AddAccess, $rows, $IdenViewAccess, $idenrows, $logrows, $LogDateFormat);
    }
    /**
     * This function will Assign a Device to an Identity
     * @throws Exception
     */
    public function assign(){
    	$id = isset($_GET['id'])?$_GET['id']:NULL;
    	if ( !$id ) {
    	    $this->view->print_error("Application error","Required field is not set!");
    	}
    	$AssignAccess= $this->accessService->hasAccess($this->Level, $this->Category, "AssignIdentity");
    	$AdminName = $_SESSION["WhoName"];
    	$Identity ="";
    	$title = 'Assign ' .$this->Category;
    	$errors = array();
    	if ( isset($_POST['form-submitted'])) {
    	    $Identity = isset($_POST['Identity']) ? $_POST['Identity'] :NULL;
    	    $this->deviceService->assign2Identity($id, $Identity,$AdminName);
    	    $this->redirect('Devices.php?Category='.$this->Category.'&op=assignform&id='.$id);
    	    return ;
    	}
    	$rows = $this->deviceService->getByID($id);
    	$identities = $this->deviceService->listAllIdentities($id);
    	$this->view->print_assignDeviceForm($title, $errors, $AssignAccess, $rows, $identities);
    }
    /**
     * This function will generate the assign Form
     * @throws Exception
     */
    public function assignform(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AdminName = $_SESSION["WhoName"];
        $title = "Assign Form";
        $AssignAccess= $this->accessService->hasAccess($this->Level, $this->Category, "AssignIdentity");
        if ( isset($_POST['form-submitted'])) {
            $Employee = $_POST["Employee"];
            $ITEmployee = $_POST["ITEmp"];
            try{
                $this->deviceService->createPDF($id, $Employee, $ITEmployee);
                $this->redirect('Devices.php?Category='.$this->Category);
                return;
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $idenrows = $this->deviceService->ListAssignedIdentities($id);
        $rows = $this->deviceService->getByID($id);
        $this->view->print_assignForm($title, $AssignAccess, $idenrows, $rows, $AdminName);
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
            $title = $this->Category."s";
            $AddAccess= $this->accessService->hasAccess($this->Level, $this->Category , "Add");
            $InfoAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Read");
            $DeleteAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Delete");
            $ActiveAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Activate");
            $UpdateAccess= $this->accessService->hasAccess($this->Level, $this->Category, "Update");
            $AssignAccess= $this->accessService->hasAccess($this->Level, $this->Category, "AssignIdentity");
            $rows = $this->deviceService->searchByCategory($search,$this->Category);
            $this->view->print_searched($title, $AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignAccess, $InfoAccess, $search);
        }
    }
}
