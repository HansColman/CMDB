<?php
require_once 'Controller.php';
require_once 'Service/TokenService.php';
require_once 'view/TokenView.php';
/**
 * This Class is the Controller for IdentityType
 * @author Hans Colman
 * @copyright Hans Colman
 * @package controller
 */
class TokenController extends Controller{
    /**
     * @static
     * @var string The name of the application
     */
    private static $sitePart ="Token";
    /**
     * @var int The Level of the Adminintrator that is doing the changes
     */
    private $Level = NULL;
    /**
     * @var TokenService The TokenService
     */
    private $tokenService = NULL;
    /**
     * This is the the TokenView
     * @var TokenView
     */
    private $view;
    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->tokenService = new TokenService();
        $this->view = new TokenView();
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
            }elseif ($op == "assign"){
                $this->assign();
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
        $AdminName = $_SESSION["WhoName"];
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        if ($ActiveAccess){
        	try{
        		$this->tokenService->activate($id,$AdminName);
        		$this->redirect('Token.php');
        	}catch (PDOException $e){
        	    $this->view->print_error("Database exception",$e);
        	}
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
        $title = 'Delete Token';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->tokenService->delete($id, $Reason, $AdminName);
                //$_POST = array();
                $this->redirect('Token.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $rows = $this->tokenService->getByID($id);
        $this->view->print_DeleteForm($title, $errors, $rows, $Reason);
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
        $title = 'Update Token';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $AssetTag = isset($_POST['AssetTag']) ? $_POST['AssetTag'] :NULL;
            $SerialNumber = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $Type = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            try{
                $this->tokenService->update($AssetTag,$SerialNumber,$Type, $AdminName);
                $this->redirect('Token.php');
                return;   
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }  else {
            $rows = $this->tokenService->getByID($id);
            foreach ($rows as $row):
                $AssetTag = $row["AssetTag"];
                $SerialNumber = $row["SerialNumber"];
                $Type = $row["Type_ID"];
            endforeach;
        }
        $typerows = $this->tokenService->listAllTypes();
        $this->view->print_UpdateForm($title, $errors, $AssetTag, $SerialNumber, $Type, $typerows, $UpdateAccess);
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
        $rows = $this->tokenService->getAll($orderby);
        $this->view->print_ListAll($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess);
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new token';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $AdminName = $_SESSION["WhoName"];
        $AssetTag = '';
        $SerialNumber = '';
        $Type = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            //print_r($_POST);
            $AssetTag = isset($_POST['AssetTag']) ? $_POST['AssetTag'] :NULL;
            $SerialNumber = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $Type = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            
            try {
                $this->tokenService->create($AssetTag, $SerialNumber, $Type, $AdminName);
                $this->redirect('Token.php');
                return;
            } catch (ValidationException $ex) {
               $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->view->print_error("Database exception",$e);
            }
        }
        $typerows = $this->tokenService->listAllTypes();
        $this->view->print_CreateForm($title, $errors, $AssetTag, $SerialNumber, $typerows, $AddAccess);
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
            $rows = $this->tokenService->search($search);
            $this->view->print_Searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $InfoAccess, $search);
        }
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
        $IdenViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "IdentityOverview");
        $ReleaseIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseIdentity");
        $rows = $this->tokenService->getByID($id);
        $idenrows = $this->tokenService->listOfAssignedIdentities($id);
        $logrows = $this->loggerController->listAllLogs('token', $id);
        $LogDateFormat = $this->getLogDateFormat();
        $this->view->print_Overview($ViewAccess, $AddAccess, $rows, $IdenViewAccess, $ReleaseIdenAccess,$idenrows, $logrows, $LogDateFormat);
    }
}
