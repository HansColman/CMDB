<?php
require_once 'view/view.php';

class RoleView extends View
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
     * @param string $reason
     */
    public function print_delete($title,$errors,$rows,$reason) {
        print "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        echo "<table class=\"table table-striped table-bordered\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Name</th>";
        echo "<th>Description</th>";
        echo "<th>Type</th>";
        echo "<th>Active</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Name'])."</td>";
            echo "<td>".htmlentities($row['Description'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "</tr>";
        endforeach;
        echo "</tbody>";
        echo "</table>";
        $this->deleteform($reason, "Role.php");
    }
    /**
     * This function will print the Create Form
     * @param string $title
     * @param array $errors
     * @param bool $AddAccess
     * @param string $Name
     * @param string $Description
     * @param array $types
     */
    public function print_CreateForm($title,$errors,$AddAccess,$Name,$Description,$types) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Name <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Name\" type=\"text\" class=\"form-control\" placeholder=\"Pleae insert Name\" value=\"".$Name."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Description</label>";
            echo "<input name=\"Description\" type=\"text\" class=\"form-control\" placeholder=\"Please insert description\" value=\"".$Description."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"type\" class=\"form-Control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["type"])){
                foreach ($types as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                }
            }  else {
                foreach ($types as $type){
                    if ($_POST["type"] == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Type"]." ".$type["Description"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"Role.php\">Back</a>";
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
     * This function will print the Update Form
     * @param string $title
     * @param array $errors
     * @param bool $UpdateAccess
     * @param string $Name
     * @param string $Description
     * @param int $Type
     * @param array $types
     */
    public function print_UpdateForm($title,$errors,$UpdateAccess,$Name,$Description,$Type,$types) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Name <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Name\" type=\"text\" class=\"form-control\" placeholder=\"Pleae insert Name\" value=\"".$Name."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Description</label>";
            echo "<input name=\"Description\" type=\"text\" class=\"form-control\" placeholder=\"Please insert description\" value=\"".$Description."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"type\" class=\"form-Control\">";
            echo "<option value=\"\"></option>";
            if (empty($Type)){
                foreach ($types as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                }
            }  else {
                foreach ($types as $type){
                    if ($Type == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Type"]." ".$type["Description"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"Role.php\">Back</a>";
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
     * This function will print the details of a Role
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_Overview($ViewAccess,$AddAccess,$rows,$logrows,$LogDateFormat) {
        echo"<h2>Role Details</h2>";
        if ($ViewAccess){
            if ($AddAccess){
                echo "<a class=\"btn icon-btn btn-success\" href=\"Role.php?op=new\">";
                echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            }
            echo " <a href=\"Role.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
            echo "<p></p>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Description</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Name'])."</td>";
            echo "<td>".htmlentities($row['Description'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
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
                echo "No Log entries found for this Role";
            }
        }else {
            $this->showError("Application error", "You do not access to this page");
        }
    }
    /**
     * This funtion will print all the Roles
     * @param bool $UpdateAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     */
    public function print_ListAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess) {
        echo "<h2>Roles</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Role.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("Role.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th><a href=\"Role.php?orderby=Name\">Name</a></th>";
            echo "<th><a href=\"Role.php?orderby=Description\">Description</a></th>";
            echo "<th><a href=\"Role.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"Role.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Name'])."</a></td>";
                echo "<td>".htmlentities($row['Description'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>";
                IF ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Role.php?op=edit&id=".$row['Role_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo "<span class=\"fa fa-pencil\"></span></a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Role.php?op=delete&id=".$row['Role_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo "<span class=\"fa fa-toggle-off\"></span></a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Role.php?op=activate&id=".$row['Role_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo "<span class=\"fa fa-toggle-on\"></span></a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Role.php?op=show&id=".$row['Role_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo "<span class=\"fa fa-info\"></span></a>";
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
    
    public function print_searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$search) {
        echo "<h2>Roles</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Role.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("Role.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Naam</th>";
            echo "<th><a href=\"Role.php?orderby=Description\">Description</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Name'])."</a></td>";
            echo "<td>".htmlentities($row['Description'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Role.php?op=edit&id=".$row['Role_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo "<span class=\"fa fa-pencil\"></span></a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Role.php?op=delete&id=".$row['Role_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo "<span class=\"fa fa-toggle-off\"></span></a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Role.php?op=activate&id=".$row['Role_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo "<span class=\"fa fa-toggle-on\"></span></a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Role.php?op=show&id=".$row['Role_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                echo "<span class=\"fa fa-info\"></span></a>";
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
}