<?php
require_once 'view/view.php';

class IdentityTypeView extends \View
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This funcrion will print the delete form
     * @param string $title
     * @param array $errors
     * @param array $rows
     * @param string $Reason
     */
    public function print_deleteForm($title,$errors,$rows,$Reason){
        print "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        echo "<table class=\"table table-striped table-bordered\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Type</th>";
        echo "<th>Description</th>";
        echo "<th>Active</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Description'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "</tr>";
        endforeach;
        echo "</tbody>";
        echo "</table>";
        $this->deleteform($Reason, "identitytype.php");   
    }
    /**
     * This function will ptint the detail
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_overview($ViewAccess,$AddAccess,$rows,$logrows,$LogDateFormat) {
        echo "<h2>Identity Type Details</h2>";
        if ($ViewAccess){
            if ($AddAccess){
                echo "<a class=\"btn icon-btn btn-success\" href=\"IdentityType.php?op=new\">";
                echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            }
            echo " <a href=\"IdentityType.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
            echo "<p></p>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Description</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Description'])."</td>";
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
                echo "No Log entries found for this IdentityType";
            }
        }else {
            $this->showError("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the create
     * @param string $title
     * @param bool $AddAccess
     * @param array $errors
     * @param string $Type
     * @param string $Description
     */
    public function print_Create($title,$AddAccess,$errors,$Type,$Description){
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Type\" type=\"text\" class=\"form-control\" placeholder=\"Please insert Type\" value=\"".$Type."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Description <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Description\" type=\"text\" class=\"form-control\" placeholder=\"Please enter description\" value=\"".$Description."\">";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"IdentityType.php\">Back</a>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
        } else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the Update
     * @param string $title
     * @param bool $UpdateAccess
     * @param array $errors
     * @param string $Type
     * @param string $Description
     */
    public function print_Update($title,$UpdateAccess,$errors,$Type,$Description){
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Type\" type=\"text\" class=\"form-control\" placeholder=\"Please insert Type\" value=\"".$Type."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Description <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Description\" type=\"text\" class=\"form-control\" placeholder=\"Please enter description\" value=\"".$Description."\">";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"IdentityType.php\">Back</a>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
        } else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print all
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     */
    public function print_ListAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess) {
        echo "<h2>Identity Types</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"IdentityType.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("IdentityType.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th><a href=\"identityType.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"identityType.php?orderby=Description\">Description</a></th>";
            echo "<th><a href=\"identityType.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Type'])."</a></td>";
            echo "<td>".htmlentities($row['Description'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"identitytype.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo "<span class=\"fa fa-pencil\"></span></a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"identitytype.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo "<span class=\"fa fa-lock\"></span></a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"identitytype.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo "<span class=\"fa fa-unlock\"></span></a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"identitytype.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
    /**
     * This function will print the searched
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     * @param string $search
     */
    public function print_searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$search) {
        echo "<h2>Identity Types</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"IdentityType.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("IdentityType.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Description</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Type'])."</a></td>";
            echo "<td>".htmlentities($row['Description'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"identitytype.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo "<span class=\"fa fa-pencil\"></span></a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"identitytype.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo "<span class=\"fa fa-lock\"></span></a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"identitytype.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo "<span class=\"fa fa-unlock\"></span></a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"identitytype.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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