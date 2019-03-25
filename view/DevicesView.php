<?php
require_once ('view/view.php');

class DevicesView extends View
{
    private $Category = "";    
    
    public function __construct($Category)
    {
        parent::__construct();
        $this->Category = $Category;
    }
    /**
     * This function will print the Overview
     * @param string $title
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $AssignAccess
     * @param bool $InfoAccess
     */
    public function print_ListAll($title, $AddAccess, $rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$AssignAccess,$InfoAccess) {
        echo "<h2>".htmlentities($title)."</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        echo "<div class=\"col-md-6 text-right\">";
        echo "<form class=\"form-inline\" role=\"search\" action=\"Devices.php?Category=".$this->Category."&op=search\" method=\"post\">";
        echo "<div class=\"form-group\">";
        echo "<input name=\"search\" type=\"text\" class=\"form-control\" placeholder=\"Search\">";
        echo "</div>";
        echo "<button type=\"submit\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-search\"></i></button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=AssetTag\">AssetTag</a></th>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=SerialNumber\">SerialNumber</a></th>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=Type\">Type</a></th>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=Active\">Active</a></th>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=ussage\">ussage</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row){
                echo "<tr>";
                echo "<td>".htmlentities($row['AssetTag'])."</td>";
                echo "<td>".htmlentities($row['SerialNumber'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>".htmlentities($row['ussage'])."</td>";
                echo "<td>";
                if ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Devices.php?Category=".$this->Category."&op=edit&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo "<span class=\"fa fa-pencil\"></span></a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Devices.php?Category=".$this->Category."&op=delete&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo "<span class=\"fa fa-toggle-off\"></span></a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Devices.php?Category=".$this->Category."&op=activate&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo "<span class=\"fa fa-toggle-on\"></span></a>";
                }
                if ($row["Active"] == "Active" and $AssignAccess){
                    echo "<a class=\"btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=assign&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                    echo "<span class=\"fa fa-user-plus\"></span></a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Devices.php?Category=".$this->Category."&op=show&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo "<span class=\"fa fa-info\"></span></a>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }  else {
            echo "<div class=\"alert alert-danger\">No rows found, please add a new record</div>";
        }
    }
    /**
     * This function will print the DeleteFrom
     * @param string $title
     * @param array $errors
     * @param array $rows
     * @param string $Reason
     */
    public function print_deleteForm($title,$errors,$rows,$Reason){
        print "<h2>".htmlentities($title)."</h2>";
        if ( $errors ) {
            print '<ul class="list-group">';
            foreach ( $errors as $field => $error ) {
                print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
            }
            print '</ul>';
        }
        echo "<table class=\"table table-striped table-bordered\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>AssetTag</th>";
        echo "<th>SerialNumber</th>";
        echo "<th>Type</th>";
        echo "<th>Active</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['AssetTag'])."</td>";
            echo "<td>".htmlentities($row['SerialNumber'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "</tr>";
        endforeach;
        echo "</tbody>";
        echo "</table>";
        $Return = "Devices.php?Category=".$this->Category;
        $this->deleteform($Reason, $Return);
    } 
    /**
     * This function will print The Create Form
     * @param string $title
     * @param bool $AddAccess
     * @param array $errors
     * @param array $typerows
     * @param string $AssetTag
     * @param string $SerialNumber
     * @param string $Name
     * @param string $MAC
     * @param string $IP
     * @param array $Ramrows
     */
    public function print_CreateForm($title,$AddAccess,$errors,$typerows,$AssetTag,$SerialNumber,$Name,$MAC,$IP,$Ramrows){
        echo "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            if ( $errors ) {
                echo '<ul class="list-group">';
                foreach ( $errors as $field => $error ) {
                    echo "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
                }
                echo '</ul>';
            }
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">AssetTag <span style=\"color:red;\">*</span></label>";
            echo "<input type=\"text\" class=\"form-control\" name=\"AssetTag\" placeholder=\"Please insert a AssetTag\" value=\"".$AssetTag."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Serial Number <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"SerialNumber\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a SerialNumber\" value=\"".$SerialNumber."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Type\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Type"])){
                foreach ($typerows as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                }
            }  else {
                foreach ($typerows as $type){
                    if ($_POST["Type"] ==$type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Name </label>";
            echo "<input name=\"Name\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a name\" value=\"".$Name."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">MAC Address </label>";
            echo "<input name=\"MAC\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a MAC Address\" value=\"".$MAC."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">IP Address </label>";
            echo "<input name=\"IP\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a IP Address\" value=\"".$IP."\">";
            echo "</div>";
            if ($this->Category == "Laptop" or $this->Category == "Desktop"){
                echo "<div class=\"form-group\">";
                echo "<label class=\"control-label\">RAM <span style=\"color:red;\">*</span></label>";
                echo "<select name=\"RAM\" class=\"form-control\">";
                echo "<option value=\"\"></option>";
                if (empty($_POST["RAM"])){
                    foreach ($Ramrows as $ram){
                        echo "<option value=\"".$ram["Text"]."\">".$ram["Text"]."</option>";
                    }
                }  else {
                    foreach ($Ramrows as $ram){
                        if ($_POST["RAM"] == $ram["Text"]){
                            echo "<option value=\"".$ram["Text"]."\" selected>".$ram["Text"]."</option>";
                        }else{
                            echo "<option value=\"".$ram["Text"]."\">".$ram["Text"]."</option>";
                        }
                    }
                }
                echo "</select>";
                echo "</div>";
            }
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">Back</a>";
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
     * THis function will print the Update Device Form
     * @param string $title
     * @param array $errors
     * @param bool $UpdateAccess
     * @param string $AssetTag
     * @param string $SerialNumber
     * @param int $Type
     * @param array $typerows
     * @param string $Name
     * @param string $MAC
     * @param string $IP
     * @param string $RAM
     * @param array $Ramrows
     */
    public function print_UpdateForm($title, $errors, $UpdateAccess, $AssetTag, $SerialNumber, $Type, $typerows, $Name, $MAC, $IP, $RAM, $Ramrows){
        echo "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            if ( $errors ) {
                echo '<ul class="list-group">';
                foreach ( $errors as $field => $error ) {
                    echo "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
                }
                echo '</ul>';
            }
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">AssetTag <span style=\"color:red;\">*</span></label>";
            echo "<input type=\"text\" class=\"form-control\" name=\"AssetTag\" placeholder=\"Please insert a AssetTag\" value=\"".$AssetTag."\" disabled>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Serial Number <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"SerialNumber\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a SerialNumber\" value=\"".$SerialNumber."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Type\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Type)){
                foreach ($typerows as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                }
            }  else {
                foreach ($typerows as $type){
                    if ($Type ==$type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Name </label>";
            echo "<input name=\"Name\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a name\" value=\"".$Name."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">MAC Address </label>";
            echo "<input name=\"MAC\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a MAC Address\" value=\"".$MAC."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">IP Address </label>";
            echo "<input name=\"IP\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a IP Address\" value=\"".$IP."\">";
            echo "</div>";
            if ($this->Category == "Laptop" or $this->Category == "Desktop"){
                echo "<div class=\"form-group\">";
                echo "<label class=\"control-label\">RAM <span style=\"color:red;\">*</span></label>";
                echo "<select name=\"RAM\" class=\"form-control\">";
                echo "<option value=\"\"></option>";
                if (empty($RAM)){
                    foreach ($Ramrows as $ram){
                        echo "<option value=\"".$ram["Text"]."\">".$ram["Text"]."</option>";
                    }
                }  else {
                    foreach ($Ramrows as $ram){
                        if ($RAM == $ram["Text"]){
                            echo "<option value=\"".$ram["Text"]."\" selected>".$ram["Text"]."</option>";
                        }else{
                            echo "<option value=\"".$ram["Text"]."\">".$ram["Text"]."</option>";
                        }
                    }
                }
                echo "</select>";
                echo "</div>";
            }
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<input type=\"hidden\" name=\"AssetTag\" value=\"".$AssetTag."\"/>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">Back</a>";
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
     * This function will print the overview of the Device
     * @param string $title
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $IdenViewAccess
     * @param array $idenrows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_overview($title,$ViewAccess,$AddAccess,$rows,$IdenViewAccess,$idenrows,$logrows,$LogDateFormat) {
        echo "<H2>".htmlentities($title)."</H2>";
        if ($ViewAccess){
            if ($AddAccess){
                echo "<a class=\"btn icon-btn btn-success\" href=\"AssetType.php?op=new\">";
                echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            }
            echo " <a href=\"Devices.php?Category=".$this->Category."\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
            echo "<p></p>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>AssetTag</th>";
            echo "<th>SerialNumber</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['AssetTag'])."</td>";
            echo "<td>".htmlentities($row['SerialNumber'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            if ($IdenViewAccess){
                echo "<H3>Identity overview</H3>";
                if (!empty($idenrows)){
                    echo "<table class=\"table table-striped table-bordered\">";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Name</th>";
                    echo "<th>UserID</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($idenrows as $identity){
                        echo "<tr>";
                        echo "<td class=\"small\">".htmlentities($identity["Name"])."</td>";
                        echo "<td class=\"small\">".htmlentities($identity["UserID"])."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }else{
                    echo "No Identity assigned to this Device";
                }
            }
            echo "<H3>Log overview</H3>";
            if (!empty($logrows)){
                echo "<table class=\"table table-striped table-bordered\">";
                echo "<thead>";
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
                echo "No Log entries found for this Asset Type";
            }
        }else {
            $this->showError("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the Assign Form
     * @param string $title
     * @param array $errors
     * @param bool $AssignAccess
     * @param array $rows
     * @param array $identities
     */
    public function print_assignDeviceForm($title,$errors,$AssignAccess,$rows,$identities) {
        echo "<H2>".htmlentities($title)."</H2>";
        if ( $errors ) {
            print '<ul class="list-group">';
            foreach ( $errors as $field => $error ) {
                print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
            }
            print '</ul>';
        }
        if ($AssignAccess){
            echo " <a href=\"Devices.php?Category=".$this->Category."\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
            echo "<p></p>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>AssetTag</th>";
            echo "<th>SerialNumber</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['AssetTag'])."</td>";
                echo "<td>".htmlentities($row['SerialNumber'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Identity <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Identity\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Identity"])){
                foreach ($identities as $type){
                    echo "<option value=\"".$type["Iden_ID"]."\">Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                }
            }  else {
                foreach ($identities as $type){
                    if ($_POST["Identity"] == $type["Iden_ID"]){
                        echo "<option value=\"".$type["Iden_ID"]."\" selected>Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Iden_ID"]."\">Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"AssetTag\" value=\"".$row['AssetTag']."\" /><br>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Assign</button>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
        }else {
            $this->showError("Application error", "You do not access to this page");
        }
    }
    public function print_searched($title, $AddAccess, $rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$AssignAccess,$InfoAccess,$search){
        echo "<h2>".htmlentities($title)."</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        echo "<div class=\"col-md-6 text-right\">";
        echo "<form class=\"form-inline\" role=\"search\" action=\"Devices.php?Category=".$this->Category."&op=search\" method=\"post\">";
        echo "<div class=\"form-group\">";
        echo "<input name=\"search\" type=\"text\" class=\"form-control\" placeholder=\"Search\">";
        echo "</div>";
        echo "<button type=\"submit\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-search\"></i></button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=AssetTag\">AssetTag</a></th>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=SerialNumber\">SerialNumber</a></th>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=Type\">Type</a></th>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=Active\">Active</a></th>";
            echo "<th><a href=\"Devices.php?Category=".$this->Category."&orderby=ussage\">ussage</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row){
                echo "<tr>";
                echo "<td>".htmlentities($row['AssetTag'])."</td>";
                echo "<td>".htmlentities($row['SerialNumber'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>".htmlentities($row['ussage'])."</td>";
                echo "<td>";
                if ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Devices.php?Category=".$this->Category."&op=edit&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo "<span class=\"fa fa-pencil\"></span></a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Devices.php?Category=".$this->Category."&op=delete&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo "<span class=\"fa fa-toggle-off\"></span></a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Devices.php?Category=".$this->Category."&op=activate&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo "<span class=\"fa fa-toggle-on\"></span></a>";
                }
                if ($row["Active"] == "Active" and $AssignAccess){
                    echo "<a class=\"btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=assign&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                    echo "<span class=\"fa fa-user-plus\"></span></a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Devices.php?Category=".$this->Category."&op=show&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo "<span class=\"fa fa-info\"></span></a>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }  else {
            echo "<div class=\"alert alert-danger\">No rows returned with the search criteria: ".htmlentities($search)."</div>";
        }
    }
}

