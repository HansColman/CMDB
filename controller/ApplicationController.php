<?php
require_once 'Controller.php';
require_once 'Service/ApplicationService.php';
require_once 'view/ApplicationView.php';
/**
 * This is the Controller class for Application
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class ApplicationController extends Controller{
    /**
     * @var ApplicationService The ApplicationService
     */
    private $applicationService = NULL;
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level;
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart = "Application";
    /**
     * The ApplicationView
     * @var ApplicationView
     */
    private $view;
    /**
     * Default Contruct function
     */
    public function __construct() {
        parent::__construct();
        $this->applicationService = new ApplicationService();
        $this->Level = $_SESSION["Level"];
        $this->view = new ApplicationView();
    }
    /**
     * This function will return all applications
     * @return array
     */
    public function listAllApplications(){
        return $this->applicationService->listAllApplications();
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
        		$this->applicationService->activate($id, $AdminName);
        		$this->redirect('Application.php');
        	}catch (PDOException $e){
        	    $this->view->print_error("Database exception",$e);
        	}
       	} else {
       	    $this->view->print_error("Application error", "You do not access to activate a application");
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
        $title = 'Delete Application';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->applicationService->delete($id,$Reason,$AdminName);
                $this->redirect('Application.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $rows = $this->applicationService->getByID($id);
        foreach ($rows as $row){
            $Name = $row["Name"];
        }
        $this->view->print_DelteForm($title, $errors, $Reason, $Name);
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
        $title = 'Update Application';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
        	$Name = isset($_POST['Name']) ? $_POST['Name'] :NULL;
        	try{
        		$this->applicationService->edit($id,$Name,$AdminName);
        		$this->redirect('Application.php');
        		return;
        	}catch (ValidationException $e){
        		$errors = $e->getErrors();
        	}catch (PDOException $ex){
        	    $this->view->print_error("Database exception",$ex);
        	}
        }else {
        	$rows = $this->applicationService->getByID($id);
        	foreach ($rows as $row):
        		$Name = $row["Name"];
        	endforeach;
        }
        $this->view->print_UpdateForm($title, $UpdateAccess, $errors, $Name);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::listAll()
	 * @uses view/applications.php
	 */
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->applicationService->getAll($orderby);
        $this->view->print_ListAll($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess,$AssignAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new Application';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Name = "";
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Name = isset($_POST['Name']) ? $_POST['Name'] :NULL;
            try{
                $this->applicationService->create($Name,$AdminName);
                $this->redirect('Application.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
        }
        $this->view->print_createForm($title, $AddAccess, $errors, $Name);
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
        $AccAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AccountOverview");
        $logrows = $this->loggerController->listAllLogs('application', $id);
        $rows = $this->applicationService->getByID($id);
        $accrows = $this->applicationService->listAllAccounts($id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_Overview($ViewAccess, $AddAccess, $rows, $AccAccess, $accrows, $logrows, $LogDateFormat);
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
            $rows = $this->applicationService->search($search);
            $this->view->print_Searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $AssignAccess, $search);
        }
    }

}
