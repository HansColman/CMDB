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
        echo "<div class=\"row\">";
        $Url = "Devices.php?Category=".$this->Category."&op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $actionUrl = "Devices.php?Category=".$this->Category;
        $this->SearchForm($actionUrl."&op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
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
                    echo self::$EditIcon."</a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Devices.php?Category=".$this->Category."&op=delete&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo self::$DeactivateIcon."</a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=activate&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo self::$ActivateIcon."</a>";
                }
                if ($row["Active"] == "Active" and $AssignAccess){
                    echo "<a class=\"btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=assign&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                    echo self::$AddIdenttyIcon."</a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Devices.php?Category=".$this->Category."&op=show&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo self::$InfoIcon."</a>";
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
        $this->print_ValistationErrors($errors);
        $this->print_table();
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
            $this->print_ValistationErrors($errors);
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
            echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">".self::$BackIcon." Back</a>";
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
            $this->print_ValistationErrors($errors);
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
            echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">".self::$BackIcon." Back</a>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
            echo "</form>";
        }else {
            $this->print_error("Application error", "You do not access to this page");
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
    public function print_overview($title,$ViewAccess,$AddAccess,$rows,$IdenViewAccess,$IdenReleaseAccess,$AssignAccess,$idenrows,$logrows,$LogDateFormat) {
        echo "<H2>".htmlentities($title);
        echo " <a href=\"Devices.php?Category=".$this->Category."\" class=\"btn btn-default float-right\">".self::$BackIcon." Back</a>";
        echo "</H2>";
        if ($ViewAccess){
            echo "<p></p>";
            $this->print_table();
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
            $Url = "AssetType.php?op=new";
            $this->print_addBelow($AddAccess, $Url);
            if($AssignAccess){
                echo "<a class=\"btn btn-success btn-lg\" href=\"Devices.php?Category=".$this->Category."&op=assign&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                echo self::$AddIdenttyIcon." </a>";
            }
            if ($IdenViewAccess){
                $this->print_IdentityInfo($idenrows,$this->Category,$IdenReleaseAccess,"Devices.php?Category=".$this->Category,$row['AssetTag']);
            }
            $this->print_loglines($logrows, $LogDateFormat,$this->Category);
        }else {
            $this->print_error("Application error", "You do not access to this page");
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
        echo "<H2>".htmlentities($title);
        echo " <a href=\"Devices.php?Category=".$this->Category."\" class=\"btn btn-default float-right\">".self::$BackIcon." Back</a></h2>";
        $this->print_ValistationErrors($errors);
        if ($AssignAccess){
            echo "<p></p>";
            $this->print_table();
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
        echo "<div class=\"row\">";
        $Url = "Devices.php?Category=".$this->Category."&op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $actionUrl = "Devices.php?Category=".$this->Category;
        $this->SearchForm($actionUrl."&op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
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
                    echo self::$EditIcon."</a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Devices.php?Category=".$this->Category."&op=delete&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo self::$DeactivateIcon."</a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Devices.php?Category=".$this->Category."&op=activate&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo self::$ActivateIcon."</a>";
                }
                if ($row["Active"] == "Active" and $AssignAccess){
                    echo "<a class=\"btn btn-success\" href=\"Devices.php?Category=".$this->Category."&op=assign&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                    echo self::$AddIdenttyIcon."</a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Devices.php?Category=".$this->Category."&op=show&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo self::$InfoIcon."</a>";
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
    /**
     * This function will print the release Identity form
     * @param string $title
     * @param array $errors
     * @param bool $IdenReleaseAccess
     * @param array $rows
     * @param array $idenrows
     */
    public function print_deleteIdentity($title,$errors,$IdenReleaseAccess,$rows,$idenrows,$AdminName) {
        echo "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        if ($IdenReleaseAccess){
            echo "<h3>Device info</h3>";
            $this->print_table();
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
            echo "<h3>Person info</h3>";
            $this->print_table();
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
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<div class=\"form-check form-check-inline\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\" for=\"Employee\">Employee</label>";
            echo "<input name=\"Employee\" type=\"text\" id=\"Employee\" class=\"form-control\" placeholder=\"Please insert name of person\" value=\"".$Name."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\" for=\"ITEmp\">IT Employee</label>";
            echo "<input name=\"ITEmp\" type=\"text\" id=\"ITEmp\" class=\"form-control\"  placeholder=\"Please insert reason\" value=\"".$AdminName."\">";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            foreach ($idenrows as $identity){
                echo "<input type=\"hidden\" name=\"IdenID\" value=\"".$identity["Iden_ID"]."\"><br>";
            }
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create PDF</button>";
            if($_SESSION["Class"] == "Device"){
                echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">".self::$BackIcon." Back</a>";
            }else{
                echo "<a class=\"btn\" href=\"Identity.php\">".self::$BackIcon." Back</a>";
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

