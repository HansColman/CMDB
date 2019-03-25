<?php
require_once 'Controller.php';
require_once 'Service/AccountTypeService.php';
require_once 'view/AccountTypeView.php';
/**
 * This is the Controller class for Account Type
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class AccountTypeController extends Controller{
    /**
     * @var AccountTypeService The AccountService
     */
    private $accountTypeService = NULL;
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart ="Account Type";
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * The AccountType View
     * @var AccountTypeView
     */
    private $view;
    /**
     * The default constructor
     */
    public function __construct() {
        parent::__construct();
        $this->accountTypeService = new AccountTypeService();
        $this->Level = $_SESSION["Level"];
        $this->view = new AccountTypeView();
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
	 * @see Controller::activate()
	 */
    public function activate() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $AdminName = $_SESSION["WhoName"];
        if ($ActiveAccess){
        	try{
            	$this->accountTypeService->activate($id, $AdminName);
            	$this->redirect('AccountType.php');
        	}catch (PDOException $e){
        		$this->view->print_error("Database exception",$e);
        	}
        } else {
            $this->view->print_error("Application error", "You do not access to activate a account type");
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::delete()
	 * @uses view/deleteAccountType_form.php
	 */
    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $title = 'Delete Account Type';
        $AdminName = $_SESSION["WhoName"];
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->accountTypeService->delete($id,$Reason,$AdminName);
                $this->redirect('AccountType.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $rows = $this->accountTypeService->getByID($id);
        $this->view->print_deleteForm($title, $errors, $rows, $Reason,$DeleteAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::edit()
	 * @uses view/updateAccountType_form.php
	 */
    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $AdminName = $_SESSION["WhoName"];
        $title = 'Update Account Type';
        $errors = array();
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        if ( isset($_POST['form-submitted'])) {
            $Type = '';
            $Description = '';
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try{
                $this->accountTypeService->uppdateIdentityType($id, $Type, $Description, $AdminName);
                $this->redirect('AccountType.php');
                return;   
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->showError("Database exception",$e);
            }
        }else {
            $rows = $this->accountTypeService->getByID($id);
            foreach($rows as $row){
                $Type = $row["Type"];
                $Description = $row["Description"];
            }
        }
        $this->view->print_updateForm($title, $errors, $Type, $Description,$UpdateAccess);
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
        $rows = $this->accountTypeService->getAll($orderby);
        $this->view->print_overview($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 * @uses view/newAccountType_form.php
	 */
    public function save() {
        $title = 'Add new Account';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Type = '';
        $Description = '';
        
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try {
                
                $this->accountTypeService->create($Type, $Description, $AdminName);
                $this->redirect('accounttype.php');
                return;
                
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $this->view->print_CreateForm($title, $errors, $Type, $Description,$AddAccess);
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
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $rows = $this->accountTypeService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('accounttype', $id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_detail($ViewAccess, $AddAccess, $rows, $logrows, $LogDateFormat);
    }
    /**
     * This function will be used to return all Account Types
     * @return array
     */
    public function listAllTypes(){
        return $this->accountTypeService->listAllTypes();
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
            $rows = $this->accountTypeService->search($search);
            $this->view->print_serached($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $search);
        }
    }

}
