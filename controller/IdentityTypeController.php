<?php
require_once 'Service/IdentityTypeService.php';
require_once 'Controller.php';
require_once 'view/TypeView.php';
/**
 * This Class is the Controller for IdentityType
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class IdentityTypeController extends Controller{
    /**
     * @var IdentityTypeService The IdentityTypeService
     */
    private $identityTypeService = NULL;
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart ="Identity Type";
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * This is the IdentityTypeView
     * @var TypeView
     */
    private $view;
    /**
     * Constructor
     */
    public function __construct() {
        $this->identityTypeService = new IdentityTypeService();
        $this->Level = $_SESSION["Level"];
        $this->view = new  TypeView();
        $this->view->setType("Identity");
        parent::__construct();
    }
    /**
     * This function will return all IdentityTypes
     * @return array
     */
    public function listAllType(){
        return $this->identityTypeService->listAllType();
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
        $rows = $this->identityTypeService->getAll($orderby);
        $this->view->print_ListAll($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess);
    }
    /**
     * {@inheritDoc}
     * @see Controller::save()
     */
    public function save(){
        $title = 'Add new Identity Type';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Type = '';
        $Description = '';
        
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try {
                $this->identityTypeService->create($Type, $Description, $AdminName);
                $this->redirect('IdentityType.php');
                return;           
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $this->view->print_CreateForm($title, $errors, $Type, $Description, $AddAccess);
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
        $title = 'Delete Identity Type';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->identityTypeService->delete($id,$Reason,$AdminName);
                //$_POST = array();
                $this->redirect('IdentityType.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $rows = $this->identityTypeService->getByID($id);
        $this->view->print_deleteForm($title, $errors, $rows, $Reason, $DeleteAccess);
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
        $AdminName = $_SESSION["WhoName"];
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        if ($ActiveAccess){
        	try{
        		$this->identityTypeService->activate($id,$AdminName);
        		$this->redirect('IdentityType.php');
        	}catch (PDOException $e){
        	    $this->view->print_error("Database exception",$e);
        	}
        }else {
            $this->view->print_error("Application error", "You do not access to activate a application");
        }
    }
    /**
     * {@inheritDoc}
     * @see Controller::show()
     */
    public function show(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
        $rows = $this->identityTypeService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('identitytype', $id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_overview($ViewAccess, $AddAccess, $rows, $logrows, $LogDateFormat);
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
        $title = 'Update Identity Type';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        if ( isset($_POST['form-submitted'])) {
            $Type = '';
            $Description = '';
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try{
                $this->identityTypeService->uppdate($id, $Type, $Description, $AdminName);
                $this->redirect('IdentityType.php');
                return;
                
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }  else {
            $rows = $this->identityTypeService->getByID($id);
            foreach($rows as $row){
                $Type = $row["Type"];
                $Description = $row["Description"];
            }
        }
        $this->view->print_Update($title, $UpdateAccess, $errors, $Type, $Description);
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
            $rows = $this->identityTypeService->search($search);
            $this->view->print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $search);
        }
    }
}