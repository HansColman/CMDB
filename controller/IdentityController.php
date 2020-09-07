<?php
require_once 'Service/IdentityService.php';
require_once 'IdentityTypeController.php';
require_once 'view/Identity_view.php';
require_once 'Controller.php';
/**
 * This Class is the Controller for Identity
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class IdentityController extends Controller{
    /**
     * @var IdentityService The IdentityService
     */
    private $identityService = NULL;
    /**
     * @var IdentityTypeController The IdentityTypeController
     */
    private $identityTypeController = NULL;
    /**
     * @var int The Level of the Admininstator that is doing the changes
     */
    private $Level;
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart = "Identity";
    /**
     * The view
     * @var identityView
     */
    private $view = NULL;
    /**
     * Constructor
     */
    public function __construct() {
        $this->identityService = new IdentityService();
        $this->identityTypeController = new IdentityTypeController();
        $this->Level = $_SESSION["Level"];
        $this->view = new IdentityView();
        parent::__construct();
    }
    /**
     * This function will return all Identies
     * @return array
     */    
    public function listAllIdenties() {
        return $this->identityService->listAllIdentities();
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
            }elseif ($op == "assignDevice"){
                $this->assignDevice();
            }elseif ($op == "assignform"){
                $this->assignForm();
            }elseif ($op == "releaseAccount"){
                $this->releaseAccount();
            }elseif ($op == "releaseDevice"){
                $this->releaseDevice();
            }else {
                $this->view->print_error("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->view->print_error("Application error", $e->getMessage());
        }
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
        $title = 'Update Identity';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $FristName = '';
            $LastName = '';
            $userid = '';
            $type = '';
            $company = '';
            $Language = '';
            $EMail = '';
            
            $FristName  = isset($_POST['FirstName']) ? $_POST['FirstName']:NULL;
            $LastName   = isset($_POST['LastName'])?  $_POST['LastName'] :NULL;
            $userid     = isset($_POST['UserID'])? $_POST['UserID'] :NULL;
            $type       = isset($_POST['type'])? $_POST['type']:NULL;
            $company    = isset($_POST['Company'])? $_POST['Company']:NULL;
            $Language   = isset($_POST['Language'])? $_POST['Language']:NULL;
            $EMail    = isset($_POST['EMail'])? $_POST['EMail']:NULL;

            try {
                $this->identityService->update($id,$FristName,$LastName,$company,$Language,$userid,$type,$EMail,$AdminName);
                $this->redirect('Identity.php');
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            }catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }  else {
            $rows = $this->identityService->getByID($id);
            foreach($rows as $row){
                if ($id == 1){
                    $FristName =$row["Name"];
                    $LastName ="";
                }else{
                    $Name = explode(", ", $row["Name"]);
                    $FristName = $Name[0];
                    $LastName = $Name[1];
                }
                $userid = $row["UserID"];
                $type = $row["Type_ID"];
                $company = $row["Company"];
                $Language = $row["Language"];
                $EMail = $row["E_Mail"];
            }
        }
        $types = $this->identityTypeController->listAllType();
        $this->view->print_update($title, $errors, $FristName, $LastName, $userid, $company, $EMail, $Language, $types,$type);
    }
    /**
     * {@inheritDoc}
     * @see Controller::listAll()
     */
    public function listAll() {
        $action = "Add";
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, $action);
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
        $AssignDeviceAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignDevice");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->identityService->getAll($orderby);
        $this->view->print_all($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignDeviceAccess, $AssignAccess, $InfoAccess);
    }
    /**
     * {@inheritDoc}
     */
    public function save() {
        $title = 'Add new Identity';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $FristName = '';
        $LastName = '';
        $userid = '';
        $type = '';
        $company = '';
        $Language = '';
        $EMail = '';

        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $FristName  = isset($_POST['FirstName']) ? $_POST['FirstName'] :NULL;
            $LastName   = isset($_POST['LastName'])?  $_POST['LastName'] :NULL;
            $userid     = isset($_POST['UserID'])? $_POST['UserID'] :NULL;
            $type       = isset($_POST['type'])? $_POST['type']:NULL;
            $company    = isset($_POST['Company'])? $_POST['Company']:NULL;
            $Language    = isset($_POST['Language'])? $_POST['Language']:NULL;
            $EMail    = isset($_POST['EMail'])? $_POST['EMail']:NULL;
            
            try {
                $this->identityService->create($FristName,$LastName,$company,$Language, $userid,$type,$EMail,$AdminName);
                $this->redirect('Identity.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        }
        $types = $this->identityTypeController->listAllType();
        $this->view->print_create($title, $AddAccess, $errors, $FristName, $LastName, $userid, $company, $EMail, $Language, $types);
    }
    /**
     * {@inheritDoc}
     */
    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Delete Identity';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->identityService->delete($id,$Reason,$AdminName);
                $this->redirect('Identity.php');
                return;
            }  catch (Exception $e){
                $errors = $e->getErrors();
            } catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        } 
        $rows = $this->identityService->getByID($id);
        $this->view->print_delete($title,$rows, $Reason, $errors);
    }
    /**
     * {@inheritDoc}
     */
    public function activate(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AdminName = $_SESSION["WhoName"];
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        if ($ActiveAccess){
        	try{
       			$this->identityService->activate($id,$AdminName);
        		$this->redirect('Identity.php');
        	} catch (PDOException $e){
        		$this->showError("Database exception",$e);
        	}
        }else{
        	$this->view->print_error("Application error", "You do not access to activate a Identity");
        }
    }
    /**
     * {@inheritDoc}
     */
    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $AccAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AccountOverview");
        $DevAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "DeviceOverview");
        $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignDevice");
        $AssignAccountAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
        $ReleaseAccountAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseAccount");
        $ReleaseDeviceAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseDevice");
        $LogDateFormat = $this->getLogDateFormat();
        $DateFormat = $this->getDateFormat();
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $rows = $this->identityService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('identity', $id);
        $accrows = $this->identityService->listAssignedAccount($id);
        $devicerows = $this->identityService->getAllAssingedDevices($id);
        $this->view->print_info($ViewAccess, $AddAccess, $rows, $AssignAccess, $id, $AccAccess, $ReleaseAccountAccess, $accrows, $DevAccess, $devicerows, $ReleaseDeviceAccess, $AssignAccountAccess,$logrows, $LogDateFormat, $DateFormat);
    }
    /**
     * This function will assign an Account to an Identity
     * @throws Exception
     */
    public function assign(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Assign Identity';
        $AdminName = $_SESSION["WhoName"];
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Account = isset($_POST['account']) ? $_POST['account'] :NULL;
            $start = isset($_POST['start']) ? $_POST['start'] :NULL;
            $end = isset($_POST['end']) ? $_POST['end'] :NULL;
            try {
                $this->identityService->AssignAccount($id, $Account, $start, $end, $AdminName);
                $this->redirect('Identity.php');
                return;
            } catch (ValidationException $exc) {
                $errors = $exc->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            } 
        }
        $rows = $this->identityService->getByID($id);
        $accounts = $this->identityService->listAllAccounts();
        $this->view->print_assignAccount($title,$AssignAccess, $errors, $rows, $accounts);
    }
    /**
     * {@inheritDoc}
     */
    public function search(){
        //print_r($_POST);
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
            $AssignDeviceAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignDevice");
            $rows = $this->identityService->search($search);
            $this->view->print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignDeviceAccess, $AssignAccess, $InfoAccess, $search);
        }
    }
    /**
     * This function will assign the correct device to the identity
     * @throws Exception
     */
    public function assignDevice(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Assign Device';
        $AdminName = $_SESSION["WhoName"];
        $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignDevice");
        $errors = array();
        $Laptop = "";
        $Desktop = "";
        $Screen = "";
        $Internet ="";
        $Token ="";
        $Mobile= "";
        if ( isset($_POST['form-submitted'])) {
            //print_r($_POST);
            $Laptop = $_POST["Laptop"];
            $Desktop = $_POST["Desktop"];
            $Screen = $_POST["Screen"];
            $Internet = $_POST["Internet"];
            $Token= $_POST["Token"];
            $Mobile = $_POST["Mobile"];
            try {
                $this->identityService->AssigDevices($id,$Laptop,$Desktop,$Screen,$Internet,$Token,$Mobile,$AdminName);
                $this->redirect('Identity.php?op=assignform&id='.$id);
                return ;
            } catch (ValidationException $exc) {
                $errors = $exc->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            } 
        }
        $idenrows = $this->identityService->getByID($id);
        $Mobilerows = $this->identityService->listAllDevices("2");
        $Internetrows = $this->identityService->listAllDevices("4");
        $Laptoprows = $this->identityService->listAllDevices("5");
        $Desktoprows = $this->identityService->listAllDevices("6");
        $Tokenrows = $this->identityService->listAllDevices("7");
        $Monitorrows = $this->identityService->listAllDevices("8");
        $this->view->print_assignDevice($title, $errors, $AssignAccess, $idenrows, $Laptoprows, $Laptop, $Monitorrows, $Screen, $Tokenrows, $Token, $Desktoprows, $Desktop, $Mobilerows, $Mobile, $Internetrows, $Internet);
    }
    /**
     * This function will generate the PDF form
     * @throws Exception
     */
    public function assignForm(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Assign form';
        $AdminName = $_SESSION["WhoName"];
        $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignDevice");
        $idenrows = $this->identityService->getByID($id);
        $rows = $this->identityService->getAllAssingedDevices($id);
        if ( isset($_POST['form-submitted'])) {
            $Employee = $_POST["Employee"];
            $ITEmployee = $_POST["ITEmp"];
            try{
                $this->identityService->generateAssignPDF($idenrows,$rows,$Employee,$ITEmployee);
                $this->redirect('Identity.php');
                return;
            }catch (PDOException $e){
                $this->showError("Database exception",$e);
            } 
        }
        $this->view->print_assignForm($title, $AssignAccess, $idenrows, $rows, $AdminName);
    }
    /**
     * This function will release an account
     * @throws Exception
     */
    public function releaseAccount(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Release Account form';
        $AdminName = $_SESSION["WhoName"];
        $account = $_GET["accountId"];
        $ReleaseAccountAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseAccount");
        $_SESSION["Class"] = "Identity";
        $errors = array();
        $idenrows = $this->identityService->getByID($id);
        $accounts = $this->identityService->getAccountInfo($account);
        foreach($accounts as $row){
            $from = $row["ValidFrom"];
        }
        if ( isset($_POST['form-submitted'])) {
            $Employee = $_POST["Employee"];
            $ITEmployee = $_POST["ITEmp"];
            try{
                $this->identityService->releaseAccount($id, $account, $from, $Employee, $ITEmployee, $AdminName);
                $this->identityService->createReleaseAccountPDF($id, $account, $Employee, $ITEmployee);
                $this->redirect('Identity.php');
                return;
            } catch (ValidationException $exc){
                $errors = $exc->getErrors();
            }catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            } 
        }
        $this->view->print_releaseAccount($title, $errors, $ReleaseAccountAccess, $idenrows, $accounts, $AdminName);
    }
    /**
     * This function will release a Device
     * @throws Exception
     */
    public function releaseDevice(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Release Device';
        $AdminName = $_SESSION["WhoName"];
        $AssetTag = $_GET["AssetTag"];
        $idenrows = $this->identityService->getByID($id);
        $devrows = $this->identityService->getAssetInfo($AssetTag);
        $Devices = $this->identityService->getAllAssingedDevices($id);
        $_SESSION["Class"] = "Identity";
        if ( isset($_POST['form-submitted'])) {
            $Employee = $_POST["Employee"];
            $ITEmployee = $_POST["ITEmp"];
            try{
                $amount = 1;
                foreach ($Devices as $device){
                    if(isset($_POST[$device["Category"].$amount])){
                        switch ($device["Category"]){
                            case "Desktop":
                                $this->identityService->releaseDevice($id, $AdminName,$_POST[$device["Category"].$amount]);
                                $Devrows = $this->identityService->getAssetInfo($_POST[$device["Category"].$amount]);
                                foreach ($Devrows as $device){
                                    $this->identityService->createReleasePDF($id, $device["Category"], $device["AssetTag"], $device["Type"], $device["SerialNumber"], $Employee, $ITEmployee);
                                }
                                $this->redirect('Identity.php');
                                return;
                                break;
                            case "Laptop":
                                $this->identityService->releaseDevice($id,$AdminName, $_POST[$device["Category"].$amount]);
                                $Devrows = $this->identityService->getAssetInfo($_POST[$device["Category"].$amount]);
                                foreach ($Devrows as $device){
                                    $this->identityService->createReleasePDF($id, $device["Category"], $device["AssetTag"], $device["Type"], $device["SerialNumber"], $Employee, $ITEmployee);
                                }
                                $this->redirect('Identity.php');
                                return;
                                break;
                            case "Monitor":
                                $this->identityService->releaseDevice($id,$AdminName, $_POST[$device["Category"].$amount]);
                                $Devrows = $this->identityService->getAssetInfo($_POST[$device["Category"].$amount]);
                                foreach ($Devrows as $device){
                                    $this->identityService->createReleasePDF($id, $device["Category"], $device["AssetTag"], $device["Type"], $device["SerialNumber"], $Employee, $ITEmployee);
                                }
                                $this->redirect('Identity.php');
                                return;
                                break;
                            case "Token":
                                $this->identityService->releaseDevice($id,$AdminName,$_POST[$device["Category"].$amount]);
                                $Devrows = $this->identityService->getAssetInfo($_POST[$device["Category"].$amount]);
                                foreach ($Devrows as $device){
                                    $this->identityService->createReleasePDF($id, $device["Category"], $device["AssetTag"], $device["Type"], $device["SerialNumber"], $Employee, $ITEmployee);
                                }
                                $this->redirect('Identity.php');
                                return;
                                break;
                            case "Mobile":
                                $this->identityService->releaseDevice($id,$AdminName,NULL,$_POST[$device["Category"].$amount]);
                                $Devrows = $this->identityService->getMobileInfo($_POST[$device["Category"].$amount]);
                                foreach ($Devrows as $device){
                                    $this->identityService->createReleasePDF($id, "Mobile", $device["IMEI"], $device["Type"], $device["IMEI"], $Employee, $ITEmployee);
                                }
                                $this->redirect('Identity.php');
                                return;
                                break;
                        }
                    }elseif (isset($_POST["Internet_Subscription".$amount])){
                        $subId = $_POST["Internet_Subscription".$amount];
                        $this->identityService->releaseDevice($id,$AdminName,NULL,NULL,$subId);
                        $Devrows = $this->identityService->getSubscriptionInfo($_POST["Internet_Subscription".$amount]);
                        foreach ($Devrows as $device){
                            $this->identityService->createReleasePDF($id, $device["Category"], $device["PhoneNumber"], $device["Type"], $device["PhoneNumber"], $Employee, $ITEmployee);
                        }
                        $this->redirect('Identity.php');
                        return;
                    }
                    $amount += 1;
                }
            }catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $errors = array();
        $DeallocateAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseDevice");
        $this->view->print_releaseDevice($title, $errors, $DeallocateAccess, $idenrows, $devrows, $Devices, $AdminName);
    }
}
