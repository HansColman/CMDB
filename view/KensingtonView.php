<?php
require_once 'view/view.php';

class KensingtonView extends View
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will print the delete From
     * @param string $title
     * @param array $errors
     * @param array $rows
     * @param string $Reason
     */
    public function printDelete($title,$errors,$rows,$Reason) {
        print "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        $this->print_table();
        echo "<tr>";
        echo "<th>Type</th>";
        echo "<th>Serialnumber</th>";
        echo "<th># Keys</th>";
        echo "<th>has Lock</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Serial'])."</td>";
            echo "<td>".htmlentities($row['AmountKeys'])."</td>";
            echo "<td>".htmlentities($row['hasLock'] == 1 ? "Yes" : "No")."</td>";
            echo "</tr>";
        endforeach;
        echo "</tbody>";
        echo "</table>";
        $this->deleteform($Reason, "Kensington.php");
    }
    /**
     * This function will print the details of the kensington
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $DeviceViewAccess
     * @param bool $ReleaseDeviceAccess
     * @param array $devicerows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_Details($ViewAccess,$AddAccess,$rows,$DeviceViewAccess,$ReleaseDeviceAccess,$devicerows,$logrows,$LogDateFormat) {
        echo "<h2>Kensington details";
        echo "<a href=\"Kensington.php\" class=\"btn btn-default float-right\">".self::$BackIcon." Back</a></H2>";
        if ($ViewAccess){
            echo "<p></p>";
            $this->print_table();
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Serialnumber</th>";
            echo "<th># Keys</th>";
            echo "<th>Lock</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                $id = $row['Key_Id'];
                echo "<tr>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Serial'])."</td>";
                echo "<td>".htmlentities($row['AmountKeys'])."</td>";
                echo "<td>".htmlentities($row['hasLock'] == 1 ? "Yes" : "No")."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $Url = "Kensington.php?op=new";
            $this->print_addBelow($AddAccess, $Url);
            if ($DeviceViewAccess){
                //Device Overview
                echo "<H3>Device overview</H3>";
                if (!empty($devicerows)){
                    $this->print_table();
                    echo "<tr>";
                    echo "<th>Category</th>";
                    echo "<th>Type</th>";
                    echo "<th>AssetTag</th>";
                    echo "<th>SerialNumber</th>";
                    echo "<th>Ussage</th>";
                    if ($ReleaseDeviceAccess){
                        echo "<th>Action</th>";
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($devicerows as $device){
                        echo "<tr>";
                        echo "<td class=\"small\">".htmlentities($device["Category"])."</td>";
                        echo "<td class=\"small\">".htmlentities($device["Type"])."</td>";
                        echo "<td class=\"small\">".htmlentities($device["AssetTag"])."</td>";
                        echo "<td class=\"small\">".htmlentities($device["SerialNumber"])."</td>";
                        echo "<td class=\"small\">".htmlentities($device["ussage"])."</td>";
                        if ($ReleaseDeviceAccess and $device["ussage"] != "Stock"){
                            echo "<td class=\"small\">";
                            echo "<a class=\"btn btn-danger\""
                                . "href=\"Kensington.php?op=releaseDevice&id=".$id."&AssetTag=".$device["AssetTag"]."\"" 
                                . "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Release\" id=\"Release".$device["AssetTag"]."\">".self::$AddIdenttyIcon."</a></td>";
                        }elseif ($ReleaseDeviceAccess){
                            echo "<td></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }else{
                    echo "No Devices assigned to this key";
                }
            }
            //LogOverview
            $this->print_loglines($logrows, $LogDateFormat, "Kensington");
        }else {
            $this->showError("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will Print All
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     * @param bool $AssignAccess
     * @param bool $ReleaseDevAccess
     */
    public function print_All($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$AssignAccess,$ReleaseDevAccess){
        echo "<h2>Kensington</h2>";
        echo "<div class=\"row\">";
        $Url = "Kensington.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Kensington.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"Kensington.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"Kensington.php?orderby=Serial\">Serialnumber</a></th>";
            echo "<th><a href=\"Kensington.php?orderby=AmountKeys\"># Keys</a></th>";
            echo "<th><a href=\"Kensington.php?orderby=hasLock\">Lock</a></th>";
            echo "<th><a href=\"Kensington.php?orderby=Active\">Active</a></th>";
            echo "<th><a href=\"Kensington.php?orderby=ussage\">Ussage</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Serial'])."</a></td>";
                echo "<td>".htmlentities($row['AmountKeys'])."</td>";
                echo "<td>".htmlentities($row['hasLock'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>".htmlentities($row['ussage'])."</td>";
                echo "<td>";
                IF ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Kensington.php?op=edit&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo self::$EditIcon."</a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Kensington.php?op=delete&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo self::$DeactivateIcon."</a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Kensington.php?op=activate&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo self::$ActivateIcon."</a>";
                }
                if ($row["Active"] == "Active" and $AssignAccess and $row['ussage'] == "Not in use"){
                    echo "<a class=\"btn btn-success\" href=\"Kensington.php?op=Assign&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign\">";
                    echo self::$AddDeviceIcon."</a>";
                }elseif ($row["Active"] == "Active" and $ReleaseDevAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Kensington.php?op=releaseDevice&id=".$row['Key_Id']."&AssetTag=".$row["ussage"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Release\">";
                    echo self::$AddDeviceIcon."</a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Kensington.php?op=show&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo self::$InfoIcon."</a>";
                }
                echo "</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
        }  else {
            echo "<div class=\"alert alert-danger\">No rows found, please add a new record</div>";
        }
    }
    /**
     * This function will print the searched Kensington
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     * @param bool $AssignAccess
     * @param string $search
     */
    public function print_Searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$AssignAccess,$search){
        echo "<h2>Kensington</h2>";
        echo "<div class=\"row\">";
        $Url = "Kensington.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Kensington.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Serialnumber</th>";
            echo "<th># Keys</th>";
            echo "<th>Lock</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Serial'])."</a></td>";
            echo "<td>".htmlentities($row['AmountKeys'])."</td>";
            echo "<td>".htmlentities($row['hasLock'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Kensington.php?op=edit&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Kensington.php?op=delete&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($row["Active"] == "Active" and $AssignAccess){
                echo "<a class=\"btn btn-danger\" href=\"Kensington.php?op=Assign&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign\">";
                echo self::$AddDeviceIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Kensington.php?op=activate&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Kensington.php?op=show&id=".$row['Key_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                echo self::$InfoIcon."</a>";
            }
            echo "</td>";
            echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
        }  else {
            echo "<div class=\"alert alert-danger\">No rows returned with the search criteria: ".htmlentities($search)."</div>";
        }
    }
    /**
     * This function will print the Create Form
     * @param string $title
     * @param bool $AddAccess
     * @param array $errors
     * @param array $types
     * @param string $Serial
     * @param string $NrKeys
     * @param int $hasLock
     */
    public function print_Create($title,$AddAccess,$errors,$types,$Serial,$NrKeys,$hasLock) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Type\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Type"])){
                foreach ($types as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]."</option>";
                }
            }  else {
                foreach ($types as $type){
                    if ($_POST["Type"] == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Serial Number <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"SerialNumber\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a SerialNumber\" value=\"".$Serial."\">";
        	echo "</div>";
        	echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Amount of keys <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Keys\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a amount\" value=\"".$NrKeys."\">";
       	 	echo "</div>";
        	echo "<div class=\"row\"><label class=\"\">Has lock <span style=\"color:red;\">*</span></label></div>";
        	echo "<div class=\"form-group\">";
            if ($hasLock == "No"){
            	echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"Lock\" value=\"Yes\">Yes</label>";
            	echo "<label class=\"radio-inline\"><input type=\"radio\" checked name=\"Lock\" value=\"No\">No</label>";
            }elseif ($hasLock == "Yes"){
            	echo "<label class=\"radio-inline\"><input type=\"radio\" checked name=\"Lock\" value=\"Yes\">Yes</label>";
            	echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"Lock\" value=\"No\">No</label>";
            }else {
            	echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"Lock\" value=\"Yes\">Yes</label>";
            	echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"Lock\" value=\"No\">No</label>";
            }
        	echo "</div>";
        	echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
        	echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"Kensington.php\">".self::$BackIcon." Back</a>";
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
     * This function will print the Update form
     * @param string $title
     * @param bool $UpdateAccess
     * @param array $errors
     * @param string $Type
     * @param array $types
     * @param string $Serial
     * @param string $NrKeys
     * @param string $hasLock
     */
    public function print_Update($title,$UpdateAccess,$errors,$Type,$types,$Serial,$NrKeys,$hasLock) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Type\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Type)){
                foreach ($types as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]."</option>";
                }
            }  else {
                foreach ($types as $type){
                    if ($Type == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Serial Number <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"SerialNumber\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a SerialNumber\" value=\"".$Serial."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Amount of keys <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Keys\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a amount\" value=\"".$NrKeys."\">";
            echo "</div>";
            echo "<div class=\"row\"><label class=\"\">Has lock <span style=\"color:red;\">*</span></label></div>";
            echo "<div class=\"form-group\">";
            if ($hasLock == "No"){
                echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"Lock\" value=\"Yes\">Yes</label>";
                echo "<label class=\"radio-inline\"><input type=\"radio\" checked name=\"Lock\" value=\"No\">No</label>";
            }elseif ($hasLock == "Yes"){
                echo "<label class=\"radio-inline\"><input type=\"radio\" checked name=\"Lock\" value=\"Yes\">Yes</label>";
                echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"Lock\" value=\"No\">No</label>";
            }else {
                echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"Lock\" value=\"Yes\">Yes</label>";
                echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"Lock\" value=\"No\">No</label>";
            }
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"Kensington.php\">".self::$BackIcon." Back</a>";
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
     * This will print the Assign Form
     * @param string $title
     * @param bool $AssignAccess
     * @param array $errors
     * @param array $KeyRows
     * @param array $DeviceRows
     */
    public function print_assign($title,$AssignAccess,$errors,$KeyRows,$DeviceRows){
        print "<h2>".htmlentities($title)."</h2>";
        if ($AssignAccess){
            $this->print_table();
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Serialnumber</th>";
            echo "<th># Keys</th>";
            echo "<th>Lock</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($KeyRows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Serial'])."</td>";
                echo "<td>".htmlentities($row['AmountKeys'])."</td>";
                echo "<td>".htmlentities($row['hasLock'] == 1 ? "Yes" : "No")."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Asset <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Asset\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Asset"])){
                foreach ($DeviceRows as $type){
                    echo "<option value=\"".$type["AssetTag"]."\">".$type["Category"]." ".$type["AssetTag"]." ".$type["Type"]."</option>";
                }
            }  else {
                foreach ($DeviceRows as $type){
                    if ($_POST["Asset"] == $type["AssetTag"]){
                        echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["Category"]." ".$type["AssetTag"]." ".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["AssetTag"]."\">".$type["Category"]." ".$type["AssetTag"]." ".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"Kensington.php\">".self::$BackIcon." Back</a>";
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
     * This function will print the release form
     * @param string $title
     * @param bool $ReleaseDeviceAccess
     * @param array $errors
     * @param array $KeyRows
     * @param array $DeviceRows
     * @param string $AdminName
     */
    public function print_release($title,$ReleaseDeviceAccess,$errors,$KeyRows,$DeviceRows,$AdminName) {
        echo "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        if ($ReleaseDeviceAccess){
            $this->print_table();
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Serialnumber</th>";
            echo "<th># Keys</th>";
            echo "<th>Lock</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($KeyRows as $row):
                $id = $row['Key_Id'];
                echo "<tr>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Serial'])."</td>";
                echo "<td>".htmlentities($row['AmountKeys'])."</td>";
                echo "<td>".htmlentities($row['hasLock'] == 1 ? "Yes" : "No")."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            //Device view
            echo "<H3>Device overview</H3>";
            $this->print_table();
            echo "<tr>";
            echo "<th>Category</th>";
            echo "<th>Type</th>";
            echo "<th>AssetTag</th>";
            echo "<th>SerialNumber</th>";
            echo "<th>Ussage</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($DeviceRows as $device){
                $Name = $device["ussage"];
                $Iden_ID = 1;
                $AssetTag = $device["AssetTag"];
                echo "<tr>";
                echo "<td class=\"small\">".htmlentities($device["Category"])."</td>";
                echo "<td class=\"small\">".htmlentities($device["Type"])."</td>";
                echo "<td class=\"small\">".htmlentities($device["AssetTag"])."</td>";
                echo "<td class=\"small\">".htmlentities($device["SerialNumber"])."</td>";
                echo "<td class=\"small\">".htmlentities($device["ussage"])."</td>";
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
            echo "<input type=\"hidden\" name=\"IdenID\" value=\"".$Iden_ID."\"><br>";
            echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\"><br>";
            echo "<input type=\"hidden\" name=\"AssetTag\" value=\"".$AssetTag."\"><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create PDF</button>";
            echo "<a class=\"btn\" href=\"Kensington.php\">".self::$BackIcon." Back</a>";
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