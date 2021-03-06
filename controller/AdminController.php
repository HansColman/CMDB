<?php
require_once 'controller.php';
require_once 'Service/AdminService.php';
require_once 'view/AdminView.php';
/**
 * This is the Controller class for Admin
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class AdminController extends Controller{
	/**
	 * @static
	 * @var string The name of the application
	 */
    private static $sitePart ="Admin";
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
	private $Level;
	/**
	 * @var AdminService The AdminService
	 */
	private $adminSerice; 
	/**
	 * This is the AdminView
	 * @var AdminView
	 */
	private $view;
	/**
	 * Constructor
	 */
	public function __construct(){
		parent::__construct();
		$this->adminSerice = new AdminService();
		$this->Level = $_SESSION["Level"];
		$this->view = new AdminView();
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
				$this->adminSerice->activate($id,$AdminName);
				$this->redirect('Admin.php');
			} catch (PDOException $e){
			    $this->view->print_error("Database exception",$e);
			}
		} else {
		    $this->view->print_error("Application error", "You do not access to activate a account");
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
		$title = 'Delete Account Type';
		$AdminName = $_SESSION["WhoName"];
		$DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
		$Reason = '';
		$errors = array();
		if ( isset($_POST['form-submitted'])) {
			$Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
			try {
				$this->adminSerice->delete($id,$Reason,$AdminName);
				$this->redirect('Admin.php');
				return;
			} catch (ValidationException $ex) {
				$errors = $ex->getErrors();
			} catch (PDOException $e){
				$this->view->print_error("Database exception",$e);
			}
		}
		$rows  = $this->adminSerice->getByID($id);
		$this->view->print_deteteForm($DeleteAccess,$title, $errors, $rows, $Reason);
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
		$AdminName = $_SESSION["WhoName"];
		$title = 'Update Administrator';
		$errors = array();
		$UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
		if ( isset($_POST['form-submitted'])) {
			$Level  = isset($_POST['Level']) ? $_POST['Level'] :NULL;
			$Admin  = isset($_POST['Admin']) ? $_POST['Admin'] :NULL;
			print_r($_POST);
			try {
				$this->adminSerice->update($id, $Level, $Admin, $AdminName);
				$this->redirect('Admin.php');
				return;
			} catch (ValidationException $e) {
				$errors = $e->getErrors();	
            } catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
		}else{
			$rows = $this->adminSerice->getByID($id);
			foreach ($rows as $row){
				$Level = $row["Level"];
				$Admin = $row["Acc_ID"];
			}
		}
		$Accounts = $this->adminSerice->getAllAccounts();
		$Levels = $this->adminSerice->getAllLevels();
		$this->view->print_UpdateForm($title, $UpdateAccess, $errors, $Admin, $Accounts, $Level, $Levels);
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
		$rows = $this->adminSerice->getAll($orderby);
		$this->view->print_ListAll($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess);
	}
	/**
	 * {@inheritDoc}
	 * @uses view/newAdmin_form.php
	 */
	public function save() {
		$title = 'Add new Account';
		$AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
	
		$AdminName = $_SESSION["WhoName"];
		$level = '';
		$Admin = '';
	
		$errors = array();
		if ( isset($_POST['form-submitted'])) {
			$level  = isset($_POST['Level']) ? $_POST['Level'] :NULL;
			$Admin  = isset($_POST['Admin']) ? $_POST['Admin'] :NULL;
			try {
				$this->adminSerice->create($level, $Admin, $AdminName);
				$this->redirect('Admin.php');
				return;
			} catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $ex){
                $this->view->print_error("Database exception",$ex);
            }
		}
		$Accounts = $this->adminSerice->getAllAccounts();
		$Levels = $this->adminSerice->getAllLevels();
		$this->view->print_CreateForm($title, $AddAccess, $errors, $Accounts, $Levels);
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
		
		$rows = $this->adminSerice->getByID($id);
		$logrows = $this->loggerController->listAllLogs('admin', $id);
		$LogDateFormat = $this->getLogDateFormat();
		$this->view->print_details($ViewAccess, $AddAccess, $rows, $logrows, $LogDateFormat);
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
			$rows = $this->adminSerice->search($search);
			$this->view->print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $search);
		}
	}
}