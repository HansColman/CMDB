<?php
require_once 'view/view.php';

class MobileView extends View
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will print the details of the Mobile
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $IdenOverAccess
     * @param array $idenrows
     * @param bool $AssignIdenAccess
     * @param bool $SubOverAccess
     * @param array $subrows
     * @param array $logrows
     * @param string $LogDateFormat
     * @param bool $AssignSubAccess
     * @param bool $ReleaseSubAccess
     * @param bool $ReleaseIdenAccess
     */
    public function print_details($ViewAccess,$AddAccess,$rows,$IdenOverAccess,$idenrows,$AssignIdenAccess,$SubOverAccess,$subrows,$logrows,$LogDateFormat,$AssignSubAccess,$ReleaseSubAccess,$ReleaseIdenAccess) {
        echo "<h2>Mobile details</h2>";
        if ($ViewAccess){
            $Url = "Mobile.php?op=new";
            $this->print_add($AddAccess, $Url);
            echo " <a href=\"Mobile.php\" class=\"btn btn-default\">".self::$BackIcon." Back</a>";
            echo "<p></p>";
            $this->print_table();
            echo "<tr>";
            echo "<th>IMEI</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                $IMEI = $row["IMEI"];
                echo "<tr>";
                echo "<td>".$row["IMEI"]."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            //Identity Overview
            if($IdenOverAccess){
                $this->print_IdentityInfo($idenrows,"Mobile",$ReleaseIdenAccess,"Mobile.php",$IMEI);
            }
            if($AssignIdenAccess){
                echo "<a class=\"btn btn-success\" href=\"Mobile.php?op=assign&id=".$IMEI."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                echo self::$AddIdenttyIcon."</a>";
            }
            // Supscription overview
            if($SubOverAccess){
                echo "<H3>Subsription overview</H3>";
                if(!empty($subrows)){
                    echo "<table class=\"table table-striped table-bordered\">";
                }else {
                    echo "No Supscriptions assigned to this Mobile";
                }
            }
            //LogOverview
            $this->print_loglines($logrows, $LogDateFormat, "Mobile");
        }else {
            $this->showError("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print all Mobiles
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $AssignIdenAccess
     * @param bool $InfoAccess
     */
    public function print_ListAll($AddAccess,$rows,$DeleteAccess,$ActiveAccess,$AssignIdenAccess,$InfoAccess,$AssignSubAccesss) {
        echo "<h2>Mobiles</h2>";
        echo "<div class=\"row\">";
        $Url = "Mobile.php?op=new";
        $this->print_add($AddAccess, $Url);
        $this->SearchForm("Kensington.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"Mobile.php?orderby=IMEI\">IMEI</a></th>";
            echo "<th><a href=\"Mobile.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"Mobile.php?orderby=ussage\">Ussage</a></th>";
            echo "<th><a href=\"Mobile.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['IMEI'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['ussage'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>";
                IF ($AddAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Mobile.php?op=edit&id=".htmlentities($row["IMEI"])."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo self::$EditIcon."<</a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Mobile.php?op=delete&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo self::$DeactivateIcon."</a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Mobile.php?op=activate&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo self::$ActivateIcon."</a>";
                }
                if ($row["Active"] == "Active" and $AssignIdenAccess){
                    echo "<a class=\"btn btn-success\" href=\"Mobile.php?op=assign&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                    echo self::$AddIdenttyIcon."</a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Mobile.php?op=show&id=".$row["IMEI"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo self::$InfoIcon."</a>";
                }
                echo "</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
        }else {
            echo "<div class=\"alert alert-danger\">No rows found, please add a new record</div>";
        }
    }
    /**
     * This function will print teh searched Mobiles
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $AssignIdenAccess
     * @param bool $InfoAccess
     * @param string $search
     */
    public function print_Searched($AddAccess,$rows,$DeleteAccess,$ActiveAccess,$AssignIdenAccess,$InfoAccess,$search,$AssignSubAccess,$ReleaseSubAccess,$ReleaseIdenAccess) {
        echo "<h2>Mobiles</h2>";
        echo "<div class=\"row\">";
        $Url = "Mobile.php?op=new";
        $this->print_add($AddAccess, $Url);
        $this->SearchForm("Kensington.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th>IMEI</th>";
            echo "<th>Type</th>";
            echo "<th>Ussage</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['IMEI'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['ussage'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($AddAccess){
                echo "<a class=\"btn btn-primary\" href=\"Mobile.php?op=edit&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Mobile.php?op=delete&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Mobile.php?op=activate&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($row["Active"] == "Active" and $AssignIdenAccess){
                echo "<a class=\"btn btn-success\" href=\"Mobile.php?op=assign&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                echo self::$AddIdenttyIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Mobile.php?op=show&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                echo self::$InfoIcon."</a>";
            }
            echo "</td>";
            echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
        }else {
            echo "<div class=\"alert alert-danger\">No rows returned with the search criteria: ".htmlentities($search)."</div>";
        }
    }
    /**
     * This function will print the Create Form
     * @param string $title
     * @param bool $AddAccess
     * @param array $errors
     * @param int $IMEI
     * @param array $typerows
     */
    public function print_Create($title,$AddAccess,$errors,$IMEI,$typerows) {
        echo "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">IMEI <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"IMEI\" type=\"text\" class=\"form-control\" placeholder=\"Please insert a IMEI\" value=\"".$IMEI."\">";
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
                    if ($_POST["Type"] == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"Mobile.php\">".self::$BackIcon." Back</a>";
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
     * This function will print the update form
     * @param string $title
     * @param bool $UpdateAccess
     * @param array $errors
     * @param int $IMEI
     * @param int $Type
     * @param array $typerows
     */
    public function print_Update($title,$UpdateAccess,$errors,$IMEI,$Type,$typerows) {
        echo "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">IMEI <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"IMEI\" type=\"text\" class=\"form-control\" placeholder=\"Please insert a IMEI\" value=\"".$IMEI."\">";
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
                    if ($Type == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"Mobile.php\">".self::$BackIcon." Back</a>";
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
     * This function will print the delete form
     * @param string $title
     * @param array $DeleteAccess
     * @param bool $errors
     * @param array $rows
     * @param string $Reason
     */
    public function print_deleteForm($title,$DeleteAccess,$errors,$rows,$Reason) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($DeleteAccess){
            $this->print_ValistationErrors($errors);
            $this->print_table();
            echo "<tr>";
            echo "<th>IMEI</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['IMEI'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $this->deleteform($Reason, "Mobile.php");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    
    public function print_assignIdentityForm($title,$AssignAccess,$errors,$rows,$idenrows){
        print "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        if ($AssignAccess){
            $this->print_table();
            echo "<tr>";
            echo "<th>IMEI</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                $IMEI = $row["IMEI"];
                echo "<tr>";
                echo "<td>".$row["IMEI"]."</td>";
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
                foreach ($idenrows as $type){
                    echo "<option value=\"".$type["Iden_ID"]."\">Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                }
            }  else {
                foreach ($idenrows as $type){
                    if ($_POST["Identity"] == $type["Iden_ID"]){
                        echo "<option value=\"".$type["Iden_ID"]."\" selected>Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Iden_ID"]."\">Name: ".$type["Name"].", UserID: ".$type["UserID"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"AssetTag\" value=\"".$IMEI."\" /><br>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Assign</button>";
            echo "<a class=\"btn\" href=\"Mobile.php\">".self::$BackIcon." Back</a>";
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

