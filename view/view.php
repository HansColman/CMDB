<?php
/**
 * This is the view class
 * @author Hans Colman
 *
 */
class View
{    
    public function __construct()
    {
        
    }
    /**
     * This will print somthing when there are errors
     * @param string $title
     * @param string $message
     */
    public function print_error($title,$message){
        print "<h1>".htmlentities($title)."</h1>";
        print "<p>".htmlentities($message)."</p>";
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
        if ( $errors ) {
            print '<ul class="list-group">';
            foreach ( $errors as $field => $error ) {
                print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
            }
            print '</ul>';
        }
        if($ReleaseAccountAccess){
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
}

