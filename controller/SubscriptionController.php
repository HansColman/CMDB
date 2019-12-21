<?php
require_once 'controller.php';
require_once 'Service/SubscriptionService.php';
require_once 'view/SubscriptionView.php';

class SubscriptionController extends Controller
{
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
            $rows = $this->service->search($search);
            $this->view->print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignAccess, $InfoAccess, $search);
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
    }
    /**
     * {@inheritDoc}
     * @see Controller::search()
     */
    public function show()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
    }

    public function activate()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
    }
    /**
     * {@inheritDoc}
     * @see Controller::search()
     */
    public function save()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
    }
    /**
     * {@inheritDoc}
     * @see Controller::search()
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
            }elseif ($op == "assignIden"){
                $this->assign();
            }elseif ($op == "releaseIdentity"){
                $this->releaseIdentity();
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
     * @see Controller::search()
     */
    public function listAll()
    {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->service->getAll($orderby);
        $this->view->print_listAll($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignAccess, $InfoAccess);
    }
    /**
     * {@inheritDoc}
     * @see Controller::search()
     */
    public function delete()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            $this->view->print_error("Application error","Required field is not set!");
        }
    }
}

