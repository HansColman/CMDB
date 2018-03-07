<?php
require_once 'Service/IdentityService.php';
require_once 'IdentityTypeController.php';
//require_once 'AccountController.php';
require_once 'Controller.php';

class IdentityController extends Controller{
    private $identityService = NULL;
    private $identityTypeController = NULL;
    private $Level;
    private static $sitePart = "Identity";

    public function __construct() {
        $this->identityService = new IdentityService();
        $this->identityTypeController = new IdentityTypeController();
        $this->Level = $_SESSION["Level"];
        parent::__construct();
    }
    
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
     * @see Controller::edit()
     */
    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
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
                $this->identityService->updateIdentity($id,$FristName,$LastName,$company,$Language,$userid,$type,$EMail,$AdminName);
                $this->redirect('Identity.php');
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            }catch (PDOException $e){
            	$this->showError("Database exception",$e);
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
        include 'view/updateIdentity_form.php';
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
        //$orderby = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->identityService->getAll($orderby);
        include 'view/identities.php';
    }
    /**
     * {@inheritDoc}
     * @see Controller::save()
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
                 $this->showError("Database exception",$ex);
            }
        }
        $types = $this->identityTypeController->listAllType();
        include 'view/newIdentity_form.php';
    }
    /**
     * {@inheritDoc}
     * @see Controller::delete()
     */
    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
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
            	$this->showError("Database exception",$ex);
            }
        } 
        $rows = $this->identityService->getByID($id);
        foreach($rows as $row){
            $name = $row["Name"];
            $userid = $row["UserID"];
            $type = $row["Type_ID"];
            $company = $row["Company"];
            $Language = $row["Language"];
            $EMail = $row["E_Mail"];
        }
        include 'view/deleteIdentity_form.php';
    }
    /**
     * {@inheritDoc}
     * @see Controller::activate()
     */
    public function activate(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
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
        	$this->showError("Application error", "You do not access to activate a Identity");
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
        $AccAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AccountOverview");
        $DevAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "DeviceOverview");
        $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignDevice");
        $ReleaseAccountAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseAccount");
        $ReleaseDeviceAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseDevice");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->identityService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('identity', $id);
        $accrows = $this->identityService->listAssignedAccount($id);
        $devicerows = $this->identityService->listAssignedDevices($id);
        include 'view/identity_overview.php';
    }
    /**
     * @throws Exception
     */
    public function assign(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
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
                $this->showError("Database exception",$e);
            } 
        }
        $rows = $this->identityService->getByID($id);
        foreach($rows as $row){
            $name = $row["Name"];
            $userid = $row["UserID"];
            $type = $row["Type_ID"];
            $company = $row["Company"];
            $Language = $row["Language"];
            $EMail = $row["E_Mail"];
        }
        $accounts = $this->identityService->listAllAccounts();
        include 'view/assignAccount.php';
    }
    /**
     * {@inheritDoc}
     * @see Controller::search()
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
            include 'view/searched_identities.php';
        }
    }
    /**
     * This function will assign the correct device to the identity
     */
    public function assignDevice(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
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
        $Mobilie= "";
        if ( isset($_POST['form-submitted'])) {
            //print_r($_POST);
            $Laptop = $_POST["Laptop"];
            $Desktop = $_POST["Desktop"];
            $Screen = $_POST["Screen"];
            $Internet = $_POST["Internet"];
            $Token= $_POST["Token"];
            $Mobilie = $_POST["Mobile"];
            try {
                $this->identityService->AssigDevices($id,$Laptop,$Desktop,$Screen,$Internet,$Token,$Mobilie,$AdminName);
                $this->redirect('Identity.php?op=assignform&id='.$id);
                return ;
            } catch (ValidationException $exc) {
                $errors = $exc->getErrors();
            } catch (PDOException $e){
                $this->showError("Database exception",$e);
            } 
        }
        $idenrows = $this->identityService->getByID($id);
        $Mobilerows = $this->identityService->listAllDevices("2");
        $Internetrows = $this->identityService->listAllDevices("4");
        $Laptoprows = $this->identityService->listAllDevices("5");
        $Desktoprows = $this->identityService->listAllDevices("6");
        $Tokenrows = $this->identityService->listAllDevices("7");
        $Monitorrows = $this->identityService->listAllDevices("8");
        include 'view/assignDevice.php';
    }
    /**
     * This function will generate the PDF form
     */
    public function assignForm(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Assign form';
        $AdminName = $_SESSION["WhoName"];
        $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignDevice");
        if ( isset($_POST['form-submitted'])) {
            $Employee = $_POST["Employee"];
            $ITEmployee = $_POST["ITEmp"];
            try{
                $this->deviceService->createPDF($id, $Employee, $ITEmployee);
                $this->redirect('Devices.php?Category='.$this->Category);
                return;
            }catch (Exception $e){
                print "something whent wrong: ".$e->getMessage();
            }
        }
        $idenrows = $this->identityService->getByID($id);
        $rows = $this->identityService->getAllAssingedDevices($id);
        include 'view/assignForm.php';
    }
    /**
     * This function will release an account
     */
    public function releaseAccount(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Release Account form';
        $AdminName = $_SESSION["WhoName"];
    }
}
