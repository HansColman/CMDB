<?php
require_once 'view.php';

class AdminView extends \View
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Tis function will print the delte Form
     * @param bool $DeleteAccess
     * @param string $title
     * @param bool $errors
     * @param bool $rows
     * @param string $Reason
     */
    public function print_deteteForm($DeleteAccess,$title,$errors,$rows,$Reason){
        print "<h2>".htmlentities($title)."</h2>";
        if ($DeleteAccess){
            $this->print_ValistationErrors($errors);
            $this->print_table();
            echo "<tr>";
            echo "<th>UserID of administrator</th>";
            echo "<th>Level</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Account'])."</td>";
                echo "<td>".htmlentities($row['Level'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $this->deleteform($Reason, "Admin.php");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the Create Form
     * @param string $title
     * @param array $AddAccess
     * @param array $errors
     * @param array $Accounts
     * @param array $Levels
     */
    public function print_CreateForm($title,$AddAccess,$errors,$Accounts,$Levels) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
        	echo "<div class=\"form-group\">";
        	echo "<label class=\"control-label\">Admin <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Admin\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Admin"])){
                foreach ($Accounts as $Account){
                    echo "<option value=\"".$Account["Acc_ID"]."\">".$Account["UserID"]."</option>";
                }
            }  else {
                foreach ($Accounts as $Account){
                    if ($_POST["Admin"] == $Account["Acc_ID"]){
                        echo "<option value=\"".$Account["Acc_ID"]."\" selected>".$Account["UserID"]."</option>";
                    }else{
                        echo "<option value=\"".$Account["Acc_ID"]."\">".$Account["UserID"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Level <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Level\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Level"])){
                foreach ($Levels as $level){
                    echo "<option value=\"".$level["Level"]."\">".$level["Level"]."</option>";
                }
            }  else {
                foreach ($Levels as $level){
                    if ($_POST["Level"] == $level["Level"]){
                        echo "<option value=\"".$level["Level"]."\" selected>".$level["Level"]."</option>";
                    }else{
                        echo "<option value=\"".$level["Level"]."\">".$level["Level"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"Admin.php\">Back</a>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
        }  else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the Update Form
     * @param string $title
     * @param bool $UpdateAccess
     * @param array $errors
     * @param int $Admin
     * @param array $Accounts
     * @param int $Level
     * @param array $Levels
     */
    public function print_UpdateForm($title,$UpdateAccess,$errors,$Admin,$Accounts,$Level,$Levels) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Admin <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Admin\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Admin)){
                foreach ($Accounts as $Account){
                    echo "<option value=\"".$Account["Acc_ID"]."\">".$Account["UserID"]."</option>";
                }
            }  else {
                foreach ($Accounts as $Account){
                    if ($Admin == $Account["Acc_ID"]){
                        echo "<option value=\"".$Account["Acc_ID"]."\" selected>".$Account["UserID"]."</option>";
                    }else{
                        echo "<option value=\"".$Account["Acc_ID"]."\">".$Account["UserID"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Level <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Level\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Level)){
                foreach ($Levels as $level){
                    echo "<option value=\"".$level["Level"]."\">".$level["Level"]."</option>";
                }
            }  else {
                foreach ($Levels as $level){
                    if ($Level == $level["Level"]){
                        echo "<option value=\"".$level["Level"]."\" selected>".$level["Level"]."</option>";
                    }else{
                        echo "<option value=\"".$level["Level"]."\">".$level["Level"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"Admin.php\">Back</a>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
        }  else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the List
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     */
    public function print_ListAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess) {
        echo "<h2>Admin</h2>";
        echo "<div class=\"row\">";
        $Url = "Admin.php?op=new";
        $this->print_add($AddAccess, $Url);
        $this->SearchForm("Admin.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"Admin.php?orderby=Account\">Account</a></th>";
            echo "<th><a href=\"Admin.php?orderby=Level\">Level</a></th>";
            echo "<th><a href=\"Admin.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Account'])."</a></td>";
                echo "<td>".htmlentities($row['Level'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>";
                if ($row["Admin_id"]>1){
                    if ($UpdateAccess){
                        echo "<a class=\"btn btn-primary\" href=\"Admin.php?op=edit&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                        echo self::$EditIcon."</a>";
                    }
                    if ($row["Active"] == "Active" and $DeleteAccess){
                        echo "<a class=\"btn btn-danger\" href=\"Admin.php?op=delete&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                        echo self::$DeactivateIcon."</a>";
                    }elseif ($ActiveAccess){
                        echo "<a class=\"btn btn-glyphicon\" href=\"Admin.php?op=activate&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                        echo self::$ActivateIcon."</a>";
                    }
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Admin.php?op=show&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will print the searched overview
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     * @param string $search
     */
    public function print_searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$search){
        echo "<h2>Admin</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        $Url = "Admin.php?op=new";
        $this->print_add($AddAccess, $Url);
        $this->SearchForm("Admin.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th>Account</th>";
            echo "<th>Level</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Account'])."</td>";
            echo "<td>".htmlentities($row['Level'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            if ($row["Admin_id"]>1){
                if ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Admin.php?op=edit&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo self::$EditIcon."</a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Admin.php?op=delete&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo self::$DeactivateIcon."</a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Admin.php?op=activate&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo self::$ActivateIcon."</a>";
                }
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Admin.php?op=show&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will pritn the details of an Admin
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_details($ViewAccess,$AddAccess,$rows,$logrows,$LogDateFormat) {
        echo "<H2>CMDB Administrator overview";
        echo "<a href=\"Admin.php\" class=\"btn btn-default float-right\">".self::$BackIcon." Back</a></H2>";
        if ($ViewAccess){
            echo "<p></p>";
            $this->print_table();
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Account'])."</td>";
            echo "<td>".htmlentities($row['Level'])."</td>";
            echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            if ($AddAccess){
                echo "<a class=\"btn icon-btn btn-success\" href=\"Admin.php?op=new\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Create\">";
                echo "<span class=\"fas fa-plus-circle\"></span> </a>";
            }
            //Log Overvieuw
            $this->print_loglines($logrows, $LogDateFormat, "Admin");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
}

