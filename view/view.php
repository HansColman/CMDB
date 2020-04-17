<?php
/**
 * This is the view class
 * @author Hans Colman
 *
 */
class View
{  
    protected static $BackIcon = "<i class=\"fa fa-arrow-left\"></i>";
    /**
     * This will represent the new icon
     * @var string
     */
    protected static $NewIcon = "<span class=\"fas fa-plus\"></span>";
    /**
     * This will represent the Edit Icon
     * @var string
     */
    protected static $EditIcon = "<span class=\"fa fa-pencil\"></span>";
    /**
     * This will represent the Deactivate Icon
     * @var string
     */
    protected static $DeactivateIcon = "<span class=\"fas fa-trash-alt\"></span>";
    /**
     * This will represent the Activate Icon
     * @var string
     */
    protected static $ActivateIcon = "<span class=\"fas fa-plus-circle\"></span>";
    /**
     * This will represent teh Assign Idenity
     * @var string
     */
    protected static $AddIdenttyIcon = "<span class=\"fa fa-user-plus\"></span>";
    /**
     * This will represent the Assign Device Icon
     * @var string
     */
    protected static $AddDeviceIcon = "<span class=\"fa fa-laptop\"></span>";
    /**
     * This will represen the info icon
     * @var string
     */
    protected static $InfoIcon = "<span class=\"fa fa-info\"></span>";
    /**
     * This will represent the release Identity Icon
     * @var string
     */
    protected static $ReleaseIdenIcon = "<span class=\"fa fa-user-minus\"></span>";
    
    protected static $MobileIcon = "<span class=\"fas fa-mobile-alt\"></span>";
    
    public function __construct()
    {
        
    }
    /**
     * This will print somthing when there are errors
     * @param string $title
     * @param string $message
     */
    public function print_error($title,$message,$url =""){
        print "<h1>".htmlentities($title)."</h1>";
        print "<div class=\"alert alert-danger\" role=\"alert\">".htmlentities($message)."</div>";
        if(isset($url)){
            echo "<a class=\"btn\" href=\"".$url."\">Back</a>";
        }
    }
    /**
     * This function will print the assignForm
     * @param string $title The title of the form
     * @param boolean $AssignAccess The indication if a admin can do Assign
     * @param array $idenrows The info of the Idenity
     * @param array $rows The device Info
     * @param string $Category The category of the device
     */
    public function print_assignForm($title,$AssignAccess,$idenrows,$rows,$AdminName,$Category=NULL){
        print "<h2>".htmlentities($title)."</h2>";
        if ($AssignAccess){
            $Name = "";
            echo "<h3>Person info</h3>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>UserID</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($idenrows as $identity){
                $Name = htmlentities($identity["Name"]);
                echo "<tr>";
                echo "<td class=\"small\">".htmlentities($identity["Name"])."</td>";
                echo "<td class=\"small\">".htmlentities($identity["UserID"])."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<h3>Device Info</h3>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Category</th>";
            echo "<th>AssetTag</th>";
            echo "<th>SerialNumber</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Category'])."</td>";
                echo "<td>".htmlentities($row['AssetTag'])."</td>";
                echo "<td>".htmlentities($row['SerialNumber'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            echo "<h3>Sing info</h3>";
            //echo "Category: ".$_SESSION["Category"]."<br>";
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\" for=\"Employee\">Employee</label>";
            echo "<input name=\"Employee\" type=\"text\" id=\"Employee\" class=\"form-control\" placeholder=\"Please insert name of person\" value=\"".$Name."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\" for=\"ITEmp\">IT Employee</label>";
            echo "<input name=\"ITEmp\" type=\"text\" id=\"ITEmp\" class=\"form-control\" placeholder=\"Please insert reason\" value=\"".$AdminName."\">";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create PDF</button>";
            if($_SESSION["Class"] == "Device"){
                echo "<a class=\"btn\" href=\"Devices.php?Category=".$Category."\">Back</a>";
            }elseif ($_SESSION["Class"] == "Kensington") {
                echo "<a class=\"btn\" href=\"Kensington.php\">Back</a>";
            }else{
                echo "<a class=\"btn\" href=\"Identity.php\">Back</a>";
            }
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
        }else {
            $this->showError("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the release account form
     * @param string $title
     * @param string $errors
     * @param boolean $ReleaseAccountAccess
     * @param array $idenrows
     * @param array $accounts
     * @param string $AdminName
     */
    public function print_releaseAccount($title,$errors,$ReleaseAccountAccess,$idenrows,$accounts,$AdminName) {
        echo "<H2>".htmlentities($title)."</H2>";
        $this->print_ValistationErrors($errors);
        if($ReleaseAccountAccess){
            $Name = "";
            if($_SESSION["Class"] == "Account"){
                if (!empty($accounts)){
                    echo "<H3>Account information that will be released</H3>";
                    echo "<table class=\"table table-striped table-bordered\">";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>UserID</th>";
                    echo "<th>Application</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($accounts as $Account){
                        echo "<tr>";
                        echo "<td class=\"small\">".htmlentities($Account["UserID"])."</td>";
                        echo "<td class=\"small\">".htmlentities($Account["Application"])."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "<h3>Person info</h3>";
                    echo "<table class=\"table table-striped table-bordered\">";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Name</th>";
                    echo "<th>UserID</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($idenrows as $identity){
                        $Name = htmlentities($identity["Name"]);
                        echo "<tr>";
                        echo "<td class=\"small\">".htmlentities($identity["Name"])."</td>";
                        echo "<td class=\"small\">".htmlentities($identity["UserID"])."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            }else{
                echo "<h3>Person info</h3>";
                echo "<table class=\"table table-striped table-bordered\">";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Name</th>";
                echo "<th>UserID</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($idenrows as $identity){
                    $Name = htmlentities($identity["Name"]);
                    echo "<tr>";
                    echo "<td class=\"small\">".htmlentities($identity["Name"])."</td>";
                    echo "<td class=\"small\">".htmlentities($identity["UserID"])."</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                if (!empty($accounts)){
                    echo "<H3>Account information that will be released</H3>";
                    echo "<table class=\"table table-striped table-bordered\">";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>UserID</th>";
                    echo "<th>Application</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($accounts as $Account){
                        echo "<tr>";
                        echo "<td class=\"small\">".htmlentities($Account["UserID"])."</td>";
                        echo "<td class=\"small\">".htmlentities($Account["Application"])."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            }
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\" for=\"Employee\">Employee that will sign</label>";
            echo "<input name=\"Employee\" type=\"text\" id=\"Employee\" class=\"form-control\" placeholder=\"Please insert name of person\" value=\"".$Name."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\" for=\"ITEmp\">IT Employee that will sign</label>";
            echo "<input name=\"ITEmp\" type=\"text\" id=\"ITEmp\" class=\"form-control\" placeholder=\"Please insert reason\" value=\"".$AdminName."\">";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create PDF</button>";
            if($_SESSION["Class"] == "Account"){
                echo "<a class=\"btn\" href=\"Account.php\">Back</a>";
            }else{
                echo "<a class=\"btn\" href=\"Identity.php\">Back</a>";
            }
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This wil print the general Delete Part
     * @param string $reason
     * @param string $backUrl
     */
    protected function deleteform($reason,$backUrl){
        echo "<p></p>";
        echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
        echo "<div class=\"form-group\">";
        echo "<label class=\"control-label\" for=\"reason\">Reason <span style=\"color:red;\">*</span></label>";
        echo "<input name=\"reason\" class=\"form-control\" type=\"text\" id=\"reason\" placeholder=\"Please insert reason\" value=\"".$reason."\">";
        echo "</div>";
        echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
        echo "<div class=\"form-actions\">";
        echo "<button type=\"submit\" class=\"btn btn-success\">Delete</button>";
        echo "<a class=\"btn\" href=\"".$backUrl."\">".self::$BackIcon." Back</a>";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
        echo "</div>";
        echo "</form>";
    }
    /**
     * This will print the search form
     * @param string $actionUrl
     */
    protected function SearchForm($actionUrl) {
        echo "<div class=\"col-md-6 text-right\">";
        echo "<form class=\"form-inline float-right\" role=\"search\" action=\"".$actionUrl."\" method=\"post\">";
        echo "<div class=\"form-group\">";
        echo "<input name=\"search\" type=\"text\" class=\"form-control\" placeholder=\"Search\">";
        echo "</div>";
        echo "<button type=\"submit\" class=\"btn btn-default\"><i class=\"fas fa-search\"></i></button>";
        echo "</form>";
        echo "</div>";
    }
    /**
     * This function will print the Add button on top
     * @param bool $AddAccess
     * @param string $Url
     */
    protected function print_addOnTop($AddAccess,$Url) {
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"".$Url."\">";
            echo self::$NewIcon." Add</a>";
            echo "</div>";
        }
    }
    /**
     * This function will print the Add button below a tabel
     * @param bool $AddAccess
     * @param string $Url
     */
    protected function print_addBelow($AddAccess,$Url){
        if ($AddAccess){
            echo "<a class=\"btn icon-btn btn-success\" href=\"".$Url."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Create\">";
            echo self::$NewIcon." </a>";
        }
    }
    /**
     * This will print the Validation errors
     * @param array $errors
     */
    protected function print_ValistationErrors($errors) {
        if ( $errors ) {
            print '<ul class="list-group">';
            foreach ( $errors as $field) {
                print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($field)."</li>";
            }
            print '</ul>';
        }
    }
    /**
     * This function will print the Identity Info
     * @param array $idenrows The info of the Identity
     * @param string $module The module where the info is needed
     * @param bool $ReleaseAccess This indicates if the Admin can Release
     * @param string $url the url to use
     * @param mixed $UUID the unique id of the module
     */
    protected function print_IdentityInfo($idenrows,$module,$ReleaseAccess,$url,$UUID){
        echo "<H3>Identity overview</H3>";
        if (!empty($idenrows)){
            $this->print_table();
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>UserID</th>";
            if($ReleaseAccess){
                echo "<th>Action</th>";
            }
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($idenrows as $identity){
                echo "<tr>";
                echo "<td class=\"small\">".htmlentities($identity["Name"])."</td>";
                echo "<td class=\"small\">".htmlentities($identity["UserID"])."</td>";
                if ($ReleaseAccess and $_SESSION["Class"] == "Device" and $identity['Iden_ID']>1){
                    echo "<td class=\"small\"><a class=\"btn btn-danger\" href=\"".$url."&op=releaseIdentity&id=".$UUID."&Identity=".$identity['Iden_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Release\">".self::$ReleaseIdenIcon."</a></td>";
                }elseif ($ReleaseAccess and $identity['Iden_ID']>1){
                    echo "<td class=\"small\"><a class=\"btn btn-danger\" href=\"".$url."?op=releaseIdentity&id=".$UUID."&Identity=".$identity['Iden_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Release\">".self::$ReleaseIdenIcon."</a></td>";
                }elseif ($identity['Iden_ID']==1){
                    echo "<td></td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }else{
            echo "No Identity assigned to this ".$module;
        }
    }
    /**
     * This function will print the log lines
     * @param array $logrows
     * @param string $LogDateFormat
     * @param string $module
     */
    protected function print_loglines($logrows,$LogDateFormat,$module) {
        echo "<H3>Log overview</H3>";
        if (!empty($logrows)){
            $this->print_table();
            echo "<tr>";
            echo "<th>Date</th>";
            echo "<th>Text</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($logrows as $log){
                echo "<tr>";
                echo "<td class=\"small\">".htmlentities(date($LogDateFormat, strtotime($log["Log_Date"])))."</td>";
                echo "<td class=\"small\">".htmlentities($log["Log_Text"])."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }  else {
            echo "No Log entries found for this ".$module;
        }
    }
    /**
     * This function will print all Table starts and Thead
     */
    protected function print_table(){
        echo "<table class=\"table table-bordered table-hover\">";
        echo "<thead class=\"thead-light\">";
    }
}

