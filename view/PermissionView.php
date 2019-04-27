<?php
require_once ('view/view.php');

class PermissionView extends \View
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will print the delete 
     * @param string $title
     * @param array $errors
     * @param array $rows
     * @param string $Reason
     */
    public function print_delete($title,$errors,$rows,$Reason) {
        print "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        echo "<table class=\"table table-striped table-bordered\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Level</th>";
        echo "<th>Menu</th>";
        echo "<th>Permission</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Level'])."</td>";
            echo "<td>".htmlentities($row['Menu'])."</td>";
            echo "<td>".htmlentities($row['Permission'])."</td>";
            echo "</tr>";
        endforeach;
        echo "</tbody>";
        echo "</table>";
        $this->deleteform($Reason, "Permission.php");
    }
    /**
     * This function will print the create
     * @param string $title
     * @param bool $AddAccess
     * @param array $errors
     * @param array $Levels
     * @param array $Menus
     * @param array $Perms
     */
    public function printCreateForm($title,$AddAccess,$errors,$Levels,$Menus,$Perms) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
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
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Menu <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"menu\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["menu"])){
                foreach ($Menus as $type){
                    echo "<option value=\"".$type["Menu_id"]."\">".$type["label"]."</option>";
                }
            }  else {
                foreach ($Menus as $type){
                    if ($_POST["menu"] == $type["Menu_id"]){
                        echo "<option value=\"".$type["Menu_id"]."\" selected>".$type["label"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Menu_id"]."\">".$type["label"]."</option>";
                    }
                }
            }
            echo "</select>";
        	echo "</div>";
        	echo "<div class=\"form-group\">";
        	echo "<label class=\"fomr-control-label\">Permission <span style=\"color:red;\">*</span></label><br>";
            echo "<select name=\"permission\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["permission"])){
                foreach ($Perms as $perm){
                    echo "<option value=\"".$perm["perm_id"]."\">".$perm["permission"]."</option>";
                }
            }  else {
                foreach ($Perms as $perm){
                    if ($_POST["permission"] == $perm["perm_id"]){
                        echo "<option value=\"".$perm["perm_id"]."\" selected>".$perm["permission"]."</option>";
                    }else{
                        echo "<option value=\"".$perm["perm_id"]."\">".$perm["permission"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"Permission.php\">Back</a>";
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
     * THis function will print the UpdateForm
     * @param bool $UpdateAccess
     * @param string $title
     * @param array $errors
     * @param int $Level
     * @param array $Levels
     * @param int $Menu
     * @param array $Menus
     * @param int $Perm
     * @param array $Perms
     */
    public function print_Update($UpdateAccess,$title,$errors,$Level,$Levels,$Menu,$Menus,$Perm,$Perms) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
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
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Menu <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"menu\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Menu)){
                foreach ($Menus as $type){
                    echo "<option value=\"".$type["Menu_id"]."\">".$type["label"]."</option>";
                }
            }  else {
                foreach ($Menus as $type){
                    if ($Menu == $type["Menu_id"]){
                        echo "<option value=\"".$type["Menu_id"]."\" selected>".$type["label"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Menu_id"]."\">".$type["label"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"fomr-control-label\">Permission <span style=\"color:red;\">*</span></label><br>";
            echo "<select name=\"permission\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Perm)){
                foreach ($Perms as $perm){
                    echo "<option value=\"".$perm["perm_id"]."\">".$perm["permission"]."</option>";
                }
            }  else {
                foreach ($Perms as $perm){
                    if ($Perm == $perm["perm_id"]){
                        echo "<option value=\"".$perm["perm_id"]."\" selected>".$perm["permission"]."</option>";
                    }else{
                        echo "<option value=\"".$perm["perm_id"]."\">".$perm["permission"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"Permission.php\">Back</a>";
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
     * This function will print the details
     * @param array $ViewAccess
     * @param array $AddAccess
     * @param array $rows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_details($ViewAccess,$AddAccess,$rows,$logrows,$LogDateFormat){
        echo "<h2>Permission Details</h2>";
        if ($ViewAccess){
            if ($AddAccess){
                echo "<a class=\"btn icon-btn btn-success\" href=\"Permission.php?op=new\">";
                echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            }
            echo " <a href=\"Permission.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
            echo "<p></p>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Level</th>";
            echo "<th>Menu</th>";
            echo "<th>Permission</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Level'])."</td>";
                echo "<td>".htmlentities($row['Menu'])."</td>";
                echo "<td>".htmlentities($row['Permission'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
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
                echo "No Log entries found for this Permission";
            }
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the overview
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $InfoAccess
     * @param bool $AssignAccess
     */
    public function print_All($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$InfoAccess,$AssignAccess) {
        echo "<h2>Permissions</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Permission.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("Permission.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "</div>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th><a href=\"Permission.php?orderby=Level\">Level</a></th>";
            echo "<th><a href=\"Permission.php?orderby=Menu\">Menu</a></th>";
            echo "<th><a href=\"Permission.php?orderby=Permission\">Permission</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Level'])."</td>";
            echo "<td>".htmlentities($row['Menu'])."</td>";
            echo "<td>".htmlentities($row['Permission'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Permission.php?op=edit&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo "<span class=\"fa fa-pencil\"></span></a>";
            }
            if ($DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Permission.php?op=delete&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo "<span class=\"fa fa-trash\"></span></a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Permission.php?op=show&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                echo "<span class=\"fa fa-info\"></span></a>";
            }
            echo "</td>";
            echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
        }else{
            echo "<div class=\"alert alert-danger\">No rows found, please add a new record</div>";
        }
    }
    /**
     * This function will print the searched result
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $InfoAccess
     * @param string $search
     * @param bool $AssignAccess
     */
    public function print_searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$InfoAccess,$search, $AssignAccess) {
        echo "<h2>Permissions</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Permission.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("Permission.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "</div>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Level</th>";
            echo "<th>Menu</th>";
            echo "<th>Permission</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Level'])."</td>";
                echo "<td>".htmlentities($row['Menu'])."</td>";
                echo "<td>".htmlentities($row['Permission'])."</td>";
                echo "<td>";
                IF ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Permission.php?op=edit&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo "<span class=\"fa fa-pencil\"></span></a>";
                }
                if ($DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Permission.php?op=delete&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo "<span class=\"fa fa-trash\"></span></a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Permission.php?op=show&id=".$row["role_perm_id"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo "<span class=\"fa fa-info\"></span></a>";
                }
                echo "</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
        }else{
            echo "<div class=\"alert alert-danger\">No rows returned with the search criteria: ".htmlentities($search)."</div>";
        }
    }
}

