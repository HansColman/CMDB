<?php
require_once 'Controller.php';
require_once 'Service/AssetTypeService.php';
class AssetTypeController extends Controller{
    private $assetTypeService = NULL;
    private $Level;
    private static $sitePart = "Asset Type";

    public function __construct() {
        $this->assetTypeService = new AssetTypeService();
        $this->Level = $_SESSION["Level"];
        parent::__construct();
    }
    
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
                $this->showError("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        }
    }
    
    public function activate() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $AdminName = $_SESSION["WhoName"];
        if ($ActiveAccess){
            $this->assetTypeService->activate($id, $AdminName);
            $this->redirect('AssetType.php');
        }
    }

    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Delete Asset Type';
        $AdminName = $_SESSION["WhoName"];
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->assetTypeService->delete($id,$Reason,$AdminName);
                $this->redirect('AssetType.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        $rows = $this->assetTypeService->getByID($id);
        foreach ($rows as $row) {
            $Vendor = $row["Vendor"];
            $Type = $row["Type"];
            $Category = $row["Category"];
        }
        include 'view/deleteAssetType_form.php';
    }

    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AdminName = $_SESSION["WhoName"];
        $title = 'Update Account Type';
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Vendor  = isset($_POST['Vendor']) ? $_POST['Vendor'] :NULL;
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Category = isset($_POST['Category'])?  $_POST['Category'] :NULL;
            try {
                $this->assetTypeService->update($id,$Category, $Vendor, $Type, $AdminName);
                $this->redirect('AssetType.php');
                return;           
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }  else {
            $rows = $this->assetTypeService->getByID($id);
            foreach ($rows as $row) {
                $Vendor = $row["Vendor"];
                $Type = $row["Type"];
                $Category = $row["Cat_ID"];
            }
        }
        $catrows = $this->assetTypeService->listAllCategories();
        include 'view/updateAssetType_form.php';
    }

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
        $rows = $this->assetTypeService->getAll($orderby);
        include 'view/assetTypes.php';
    }

    public function save() {
        $title = 'Add new Asset Type';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Vendor = '';
        $Type = '';
        $Category = '';
        
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Vendor  = isset($_POST['Vendor']) ? $_POST['Vendor'] :NULL;
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Category = isset($_POST['Category'])?  $_POST['Category'] :NULL;
            try {
                $this->assetTypeService->create($Category, $Vendor, $Type, $AdminName);
                $this->redirect('AssetType.php');
                return;           
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        $catrows = $this->assetTypeService->listAllCategories();
        include 'view/newAssetType_form.php';
    }

    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $rows = $this->assetTypeService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('assettype', $id);
        
        include 'view/assetType_overview.php';
    }

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
            $rows = $this->assetTypeService->search($search);
            include 'view/searched_assetTypes.php';
        }
    }

}