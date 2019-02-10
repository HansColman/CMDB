<?php
require_once 'Controller.php';
require_once 'Service/MobileService.php';
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
     * The default contructor
     */
    public function __construct() {
        parent::__construct();
        $this->service = new MobileService();
        $this->Level = $_SESSION["Level"];
    }
    /**
	 * {@inheritDoc}
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
     */
    public function activate() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AdminName = $_SESSION["WhoName"];
    }
    /**
	 * {@inheritDoc}
	 * @uses view/deleteMobile_form.php
	 */
    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Delete '.$this->Category;
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
    }
    /**
	 * {@inheritDoc}
	 * @uses view/updateMobile_form.php
	 */
    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Update Mobile';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
    }
    /**
     * {@inheritDoc}
     * @uses view/mobiles.php
     */
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $AssignIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $IdenOverAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "IdentityOverview");
        $AssignSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignSubscription");
        $SubOverAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "SubscriptionOverview");
        $ReleaseSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseSubscription");
        $ReleaseIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseIdentity");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->service->getAll($orderby);
        include 'view/mobiles.php';
    }    
    /**
	 * {@inheritDoc}
	 * @uses view/newMobile_form.php
	 */
    public function save() {
        $title = 'Add new Mobile';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $AdminName = $_SESSION["WhoName"];
        if(isset($_POST['form-submitted'])){
            
        }
        $types = $this->service->listAllTypes();
        include 'view/newKensington_form.php';
    }
    /**
     * {@inheritDoc}
     * @uses view/mobile_overview.php
     */
    public function show() {
        /**
         * INSERT INTO role_perm (level, perm_id, menu_id) VALUES
        *   (9, 1, 23),
        *   (9, 2, 23),
        *   (9, 3, 23),
        *   (9, 4, 23),
        *   (9, 5, 23),
        *   (9, 14, 23) => AssignIdentity
        *   (9, 15, 23), =>IdentityOverview
        *   (9, 12, 23), =>AssignSubscription
        *   (9, 13, 23), =>SubscriptionOverview
        *   (9, 29, 23), =>ReleaseSubscription
        *   (9, 30, 23) => ReleaseIdentity;
        */
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $AssignIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $IdenOverAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "IdentityOverview");
        $AssignSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignSubscription");
        $SubOverAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "SubscriptionOverview");
        $ReleaseSubAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseSubscription");
        $ReleaseIdenAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "ReleaseIdentity");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
    }
    /**
	 * {@inheritDoc}
	 * @uses view/searched_mobile.php
	 */
    public function search() {
        $search = isset($_POST['search']) ? $_POST['search'] :NULL;
        if (empty($search)){
            $this->listAll();
        }  else {
        
        }
    }
}