<?php
require_once 'Controller.php';
require_once 'Service/MobileService.php';
require_once 'view/MobileView.php';
/**
 * This is the Controller class for Devices
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class MobileController extends Controller{
    
    /**
     * @var MobileService The MobileService
     */
    private $service = NULL;
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart = "Mobile";
    /**
     * This is the MobileView
     * @var MobileView
     */
    private $view;
    /**
     * The default contructor
     */
    public function __construct() {
        parent::__construct();
        $this->service = new MobileService();
        $this->Level = $_SESSION["Level"];
        $this->view = new MobileView();
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
            }  elseif ($op == "assignSubscription") {
                $this->assignSubscription();
            }elseif ($op == "releaseIdentity") {
                $this->releaseIdentity();
            }elseif ($op == "releaseSubscription") {
                $this->releaseSubscription();
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
        $ActiveAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        if ($ActiveAccess){
            try {
                $this->service->activate($id, $AdminName);
                $this->redirect("Mobile.php");
            } catch (PDOException $e) {
                $this->view->print_error("Database exception",$e);
            }
        }else{
            $this->view->print_error("Application error","You do not access to this page");
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
        $DeleteAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $title = 'Delete Mobile';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if(isset($_POST['form-submitted'])){
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try{
                $this->service->delete($id, $Reason, $AdminName);
                $this->redirect("Mobile.php");
                return;
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $rows = $this->service->getByID($id);
        $this->view->print_deleteForm($title, $DeleteAccess, $errors, $rows, $Reason);
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
        $title = 'Update Mobile';
        $AdminName = $_SESSION["WhoName"];
        $IMEI = "";
        $Type = "";
        $errors = array();
        if(isset($_POST['form-submitted'])){
            $IMEI = $_POST["IMEI"];
            $Type = $_POST["Type"];
            try{
                $this->service->edit($IMEI, $Type, $AdminName);
                $this->redirect("Mobile.php");
                return;
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }else{
            $rows = $this->service->getByID($id);
            foreach ($rows as $row){
                $IMEI = $row["IMEI"];
                $Type = $row["Type_ID"];
            }
        }
        $typerows = $this->service->listAllTypes();
        $this->view->print_Update($title, $UpdateAccess, $errors, $IMEI, $Type, $typerows);
    }
    /**
     * {@inheritDoc}
     * @see Controller::listAll()
     */
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $DeleteAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $InfoAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $AssignIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $AssignSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignSubscription");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->service->getAll($orderby);
        $this->view->print_ListAll($AddAccess, $rows, $DeleteAccess, $ActiveAccess, $AssignIdenAccess, $InfoAccess,$AssignSubAccess);
    }    
    /**
     * {@inheritDoc}
     * @see Controller::save()
     */
    public function save() {
        $title = 'Add new Mobile';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $AdminName = $_SESSION["WhoName"];
        $IMEI = "";
        $type = "";
        $errors = array();
        if(isset($_POST['form-submitted'])){
            $IMEI = $_POST["IMEI"];
            $type = $_POST["Type"];
            try{
                $this->service->add($IMEI,$type,$AdminName);
                $this->redirect("Mobile.php");
                return;
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $typerows = $this->service->listAllTypes();
        $this->view->print_Create($title, $AddAccess, $errors, $IMEI, $typerows);
    }
    /**
     * {@inheritDoc}
     * @uses view/mobile_overview.php
     */
    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $AssignIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $IdenOverAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "IdentityOverview");
        $AssignSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignSubscription");
        $SubOverAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "SubscriptionOverview");
        $ReleaseSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseSubscription");
        $ReleaseIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseIdentity");
        $rows = $this->service->getByID($id);
        $logrows = $this->loggerController->listAllLogs('mobile', $id);
        $idenrows = $this->service->getAssignedIdenty($id);
        $subrows = $this->service->getSubsriptions($id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_details($ViewAccess, $AddAccess, $rows, $IdenOverAccess, $idenrows, $AssignIdenAccess, $SubOverAccess, $subrows, $logrows, $LogDateFormat,$AssignSubAccess,$ReleaseSubAccess,$ReleaseIdenAccess);
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
            $DeleteAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
            $ActiveAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
            $InfoAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
            $AssignIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
            $AssignSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignSubscription");
            $ReleaseSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseSubscription");
            $ReleaseIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseIdentity");
            $rows = $this->service->search($search);
            $this->view->print_Searched($AddAccess, $rows, $AddAccess, $DeleteAccess, $ActiveAccess, $AssignIdenAccess, $InfoAccess, $search, $AssignSubAccess, $ReleaseSubAccess, $ReleaseIdenAccess);
        }
    }
    /**
     * This function will do the Assing to Identity
     */
    public function assign() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AssignIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $title = "Assign Identity";
        $AdminName = $_SESSION["WhoName"];
        $rows = $this->service->getByID($id);
        $idenrows = $this->service->listAllIdentities($id);
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $IMEI = $_POST["AssetTag"];
            $Identity = $_POST["Identity"];
            try{
                $this->service->assingIdentity($IMEI,$Identity,$AdminName);
                $this->redirect("Mobile.php?op=assignform&id=".$id);
                return;
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $this->view->print_assignIdentityForm($title, $AssignIdenAccess, $errors, $rows, $idenrows,$AdminName);
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
        $AssignIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        if ( isset($_POST['form-submitted'])) {
            $Employee = $_POST["Employee"];
            $ITEmployee = $_POST["ITEmp"];
            try{
                $this->service->generateAssignPDF($id, $Employee, $ITEmployee);
                $this->redirect("Mobile.php");
                return;
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $idenrows = $this->service->getAssignedIdenty($id);
        $rows = $this->service->getByID($id);
        $this->view->print_assignForm($title, $AssignIdenAccess, $idenrows, $rows, $AdminName);
    }
    /**
     * This function will assign the subscription
     */
    public function assignSubscription(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AdminName = $_SESSION["WhoName"];
        $title = "Assign Subscription Form";
        $AssignSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignSubscription");
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $IMEI = $_POST["AssetTag"];
            $SubId = $_POST["Subscription"];
            try{
                $this->service->assignSubscription($IMEI,$SubId,$AdminName);
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
                $this->redirect("Mobile.php");
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $rows = $this->service->getByID($id);
        $subrows = $this->service->ListAllSubsription();
        $this->view->print_assignSubscriptionForm($title, $AssignSubAccess, $errors, $rows, $subrows);
    }
    /**
     * This function will release the identiy
     */
    public function releaseIdentity() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AdminName = $_SESSION["WhoName"];
        $title = "Release Identity Form";
        $ReleaseIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseIdentity");
        $errors = array();
        $rows = $this->service->getByID($id);
        $idenrows = $this->service->getAssignedIdenty($id);
        $ITEmployee = $AdminName;
        if ( isset($_POST['form-submitted'])) {
            print_r($_POST);
            $Employee = $_POST["Employee"];
            $ITEmployee = $_POST["ITEmp"];
            $IdenId = $_POST["IdenID"];
            try{
                $this->service->releaseIdentity($id, $IdenId, $Employee, $ITEmployee, $AdminName);
                $this->service->generateReleasePdf($idenrows, $rows, $Employee, $ITEmployee);
                $this->redirect("Mobile.php");
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $this->view->print_releaseIdentity($title, $errors, $ReleaseIdenAccess, $rows, $idenrows, $ITEmployee);
    }
    /**
     * This function will release the subscription
     */
    public function releaseSubscription() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AdminName = $_SESSION["WhoName"];
        $title = "Release Subscription Form";
        $errors = array();
        $ReleaseSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseSubscription");
        $rows = $this->service->getByID($id);
    }
}