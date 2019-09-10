<?php
require_once 'controller.php';
require_once 'view/SubscriptionTypeView.php';
require_once 'Service/SubscriptionTypeService.php';
class SubscriptionTypeController extends Controller
{
    /**
     * @var SubscriptionTypeView $view The view
     */
    private $view;
    /**
     * @var string The name of the application
     */
    private static $sitePart ="Subscription Type";
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * This is the serive Class
     * @var SubscriptionTypeService
     */
    private $Service;
    
    public function __construct(){
        parent::__construct();
        $this->view = new SubscriptionTypeView();
        $this->Service = new SubscriptionTypeService();
        $this->Level = $_SESSION["Level"];
    }
    /**
     * {@inheritDoc}
     * @see Controller::search()
     */
    public function search(){
        $search = isset($_POST['search']) ? $_POST['search'] :NULL;
        if (empty($search)){
            $this->listAll();
        }  else {
            $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
            $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
            $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
            $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
            $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
            $rows = $this->Service->search($search);
            $this->view->print_search($rows, $AddAccess, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $search);
        }
    }
    /**
     * {@inheritDoc}
     * @see Controller::edit()
     */
    public function edit(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $rows = $this->Service->getByID($id);
        $title = 'Add new subscription type';
        $AdminName = $_SESSION["WhoName"];
        $Type = '';
        $Description = '';
        $Provider = '';
        $Category = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type = $_POST["Type"];
            $Description =$_POST["Description"];
            $Provider =$_POST["Provider"];
            $Category = $_POST["Category"];
            try{
                $this->Service->Edit($id,$Type,$Description,$Provider,$Category,$AdminName);
                $this->redirect('SubscriptionType.php');
                return;
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }else{
            foreach ($rows as $row){
                $Type = $row['Type'];
                $Description = $row['Description'];
                $Provider = $row['Provider'];
                $Category = $row['Category'];
            }
        }
        $this->view->print_edit($title, $errors, $UpdateAccess, $Type, $Description, $Provider, $Category);
    }
    /**
     * {@inheritDoc}
     * @see Controller::show()
     */
    public function show(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $rows = $this->Service->getByID($id);
        $logrows = $this->loggerController->listAllLogs('subscriptiontype', $id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_info($InfoAccess, $AddAccess, $rows, $logrows, $LogDateFormat);
    }
    /**
     * {@inheritDoc}
     * @see Controller::activate()
     */
    public function activate(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $AdminName = $_SESSION["WhoName"];
        if ($ActiveAccess){
            $this->Service->activate($id, $AdminName);
            $this->redirect('SubscriptionType.php');
            return;
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * {@inheritDoc}
     * @see Controller::save()
     */
    public function save(){
        $title = 'Add new subscription type';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $AdminName = $_SESSION["WhoName"];
        $Type = '';
        $Description = '';
        $Provider = '';
        $Category = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type = $_POST["Type"];
            $Description =$_POST["Description"];
            $Provider =$_POST["Provider"];
            $Category = $_POST["Category"];
            try {
                $this->Service->create($Type, $Description, $Provider, $Category, $AdminName);
                $this->redirect('SubscriptionType.php');
                return;
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $this->view->print_CreateForm($title, $errors,$AddAccess,$Type,$Description,$Provider,$Category);
    }
    /**
     * {@inheritDoc}
     * @see Controller::handleRequest()
     */
    public function handleRequest(){
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
    public function listAll(){
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
        $rows = $this->Service->getAll($orderby);
        $this->view->list_all($rows, $AddAccess, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess);
    }
    /**
     * {@inheritDoc}
     * @see Controller::delete()
     */
    public function delete(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $rows = $this->Service->getByID($id);
        $AdminName = $_SESSION["WhoName"];
        $title = 'Remove Subscription type';
        $reason = "";
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            try{
                $reason = $_POST["reason"];
                $this->Service->delete($id, $reason, $AdminName);
                $this->redirect('SubscriptionType.php');
                return;
            }catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $this->view->print_delete($title, $DeleteAccess, $errors, $rows, $reason);
    }
}

