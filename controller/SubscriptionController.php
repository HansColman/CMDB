<?php
require_once 'controller.php';
require_once 'Service/SubscriptionService.php';
require_once 'view/SubscriptionView.php';

class SubscriptionController extends Controller
{
    /**
     * @var SubscriptionService The service
     */
    private $service;
    /**
     * @var string The name of the application
     */
    private static $sitePart ="Subscription";
    /**
     * 
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * @var SubscriptionView $view The view
     */
    private $view = NULL;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->service = new SubscriptionService();
        $this->view = new SubscriptionView();
    }
    /**
     * {@inheritDoc}
     * @see Controller::search()
     */
    public function search()
    {
        $search = isset($_POST['search']) ? $_POST['search'] :NULL;
        if (empty($search)){
            $this->listAll();
        }  else {
            $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
            $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
            $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
            $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
            $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
            $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
            $AssignMobileAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignMobile");
            $rows = $this->service->search($search);
            $this->view->print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignAccess,$AssignMobileAccess, $InfoAccess, $search);
        }
    }
    /**
     * {@inheritDoc}
     * @see Controller::edit()
     */
    public function edit()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = "Update subscription";
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        $phoneNumber = "";
        $Type = "";
        $subtypes = $this->service->getAllSubscriptions();
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        if (isset($_POST['form-submitted'])) {
            $phoneNumber = $_POST["PhoneNumber"];
            $Type = $_POST["type"];
            try {
                $this->service->edit($id,$phoneNumber,$Type,$AdminName);
                $this->redirect('Subscription.php');
                return;
            }catch (ValidationException $e){
                $errors = $e->getErrors();
            }catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        }else{
            $rows = $this->service->getByID($id);
            foreach ($rows as $row){
                $phoneNumber = htmlentities($row['PhoneNumber']);
                $Type = htmlentities($row['Type']);
            }
        }
        $this->view->print_update($title,$UpdateAccess,$errors,$phoneNumber,$subtypes,$Type);
    }
    /**
     * {@inheritDoc}
     * @see Controller::show()
     */
    public function show()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $AssignIdenAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $AssignMobileAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignMobile");
        $IdenOverAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "IdentityOverview");
        $MobOverAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "MobileOverview");
        $ReleaseIdenAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseIdentity");
        $ReleaseMobAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseMobile");
        $rows = $this->service->getByID($id);
        $LogDateFormat = $this->getLogDateFormat();
        $DateFormat = $this->getDateFormat();
        $logrows = $this->loggerController->listAllLogs('subscription', $id);
        $identiyrows = $this->service->getAssignedIdenity($id);
        $mobileRows = $this->service->getAssignedMobile($id);
        $this->view->print_info($ViewAccess, $AddAccess, $rows, $identiyrows, $mobileRows, $IdenOverAccess, $MobOverAccess, $AssignIdenAccess, $AssignMobileAccess,$ReleaseIdenAccess,$ReleaseMobAccess, $logrows, $LogDateFormat, $DateFormat);
    }
    /**
     * {@inheritDoc}
     * @see Controller::activate()
     */
    public function activate()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $AdminName = $_SESSION["WhoName"];
        if($ActiveAccess){
            try {
                $this->service->activate($id, $AdminName);
                $this->redirect('Subscription.php');
                return;
            } catch (PDOException $ex) {
                $this->view->print_error("Database exception",$ex);
            }
        }
    }
    /**
     * {@inheritDoc}
     * @see Controller::save()
     */
    public function save()
    {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $subtypes = $this->service->getAllSubscriptions();
        $title = "Create subscription";
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        $phoneNumber = "";
        if (isset($_POST['form-submitted'])) {
            $phoneNumber = $_POST["PhoneNumber"];
            $Type = $_POST["type"];
            try {
                $this->service->create($phoneNumber, $Type, $AdminName);
                $this->redirect('Subscription.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        }
        $this->view->print_create($title, $AddAccess, $errors, $phoneNumber, $subtypes);
    }
    /**
     * {@inheritDoc}
     * @see Controller::handleRequest()
     */
    public function handleRequest()
    {
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
            } elseif ($op == "search") {
                $this->search();
            }elseif ($op == "assignIdentity"){
                $this->assign();
            }elseif ($op == "releaseIdentity"){
                $this->releaseIdentity();
            }elseif ($op == "assignMobile"){
                $this->assign();
            }elseif ($op == "releaseMobile"){
                $this->releaseMobile();
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
    public function listAll()
    {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $AssignMobileAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignMobile");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->service->getAll($orderby);
        $this->view->print_listAll($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignAccess,$AssignMobileAccess,$InfoAccess);
    }
    /**
     * {@inheritDoc}
     * @see Controller::delete()
     */
    public function delete()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Delete subscription';
        $AdminName = $_SESSION["WhoName"];
        $reason = "";
        $errors = array();
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        if(isset($_POST['form-submitted'])){
            $reason = $_POST['reason'];
            try {
                $this->service->delete($id, $reason, $AdminName);
                $this->redirect('Subscription.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        }
        $rows  = $this->service->getByID($id);
        $this->view->print_delete($DeleteAccess, $rows, $title, $errors, $reason);
    }
    /**
     * This function will assing the subscription to Identiy or a Mobile
     */
    public function assign(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Assign subscription';
        $rows  = $this->service->getByID($id);
        $idenrows = $this->service->listAllIdentities($id);
        $mobilerows = $this->service->listAllMobiles($id);
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $AssignMobileAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignMobile");
        $Identity = "";
        $IMEI = "";
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if(isset($_POST['form-submitted'])){
            print_r($_POST);
            $Identity = isset($_POST['identity'])?$_POST['identity']:0;
            $IMEI = isset($_POST['Mobile'])?$_POST['Mobile']:0;
            $cat = $_POST['category'];
            try{
                $this->service->assign($id,$cat,$Identity,$IMEI,$AdminName);
                $this->redirect('Subscription.php');
                return;
            }catch (ValidationException $e){
                $errors = $e->getErrors();
            }catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        }
        $this->view->printAssign($title,$rows,$errors,$idenrows,$mobilerows,$AssignAccess,$AssignMobileAccess);
    }
    /**
     * This will release Identity
     */
    public function releaseIdentity(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Release Identity';
        $rows  = $this->service->getByID($id);
        $idenrows = $this->service->listAllIdentities($id);
        $ReleaseIdenAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseIdentity");
        $errors = array();
    }
    /**
     * This will release Mobile
     */
    public function releaseMobile() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Release Mobile';
        $rows  = $this->service->getByID($id);
        $mobilerows = $this->service->listAllMobiles($id);
        $ReleaseMobAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseMobile");
        $errors = array();
    }
}

