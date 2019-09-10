<?php
require_once 'view/view.php';

class ApplicationView extends View
{
    public function __construct()
    {
        parent::__construct();
    }
    public function print_DelteForm($title,$errors,$Reason,$Name) {
        print "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        $this->print_table();
        echo "<tr>";
        echo "<th>Name</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo "<tr>";
        echo "<td>".htmlentities($Name)."</td>";
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
        $this->deleteform($Reason, "Application.php");
    }
    /**
     * This function will print the Application overview
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $AccAccess
     * @param array $accrows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_Overview($ViewAccess,$AddAccess,$rows,$AccAccess,$accrows,$logrows,$LogDateFormat) {
        echo "<h2>Application Details";
        echo " <a href=\"Application.php\" class=\"btn btn-default float-right\">".self::$BackIcon." Back</a></h2>";
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
            echo "<td>".htmlentities($row['Name'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $Url ="Application.php?op=new";
            $this->print_addBelow($AddAccess, $Url);
            /* Account overview */
            if ($AccAccess){
                echo "<H3>Account overview</H3>";
                if (!empty($accrows)){
                    $this->print_table();
                    echo "<tr>";
                    echo "<th>UserID</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($accrows as $account){
                        echo "<tr>";
                        echo "<td>".htmlentities($account['UserID'])."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }else {
                    echo "No Accounts assigned to this Application";
                }
            }
            $this->print_loglines($logrows, $LogDateFormat, "Application");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the List all
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     * @param bool $AssignAccess
     */
    public function print_ListAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$AssignAccess) {
        echo "<h2>Applications</h2>";
        echo "<div class=\"row\">";
        $Url ="Application.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Application.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"Application.php?orderby=Name\">Name</a></th>";
            echo "<th><a href=\"Application.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Name'])."</a></td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            if ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Application.php?op=edit&id=".$row['App_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Application.php?op=delete&id=".$row['App_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Application.php?op=activate&id=".$row['App_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Application.php?op=show&id=".$row['App_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This print the create form
     * @param string $title
     * @param bool $AddAccess
     * @param array $errors The list of erros
     * @param string $Name
     */
    public function print_createForm($title,$AddAccess,$errors,$Name) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Name <span style=\"color:red;\">*</span></label>";
            print "<input name=\"Name\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a name\" value=\"".$Name."\">";
            print "</div>";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            print "<a class=\"btn\" href=\"Application.php\">".self::$BackIcon." Back</a>";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            print "</div>";
            print "</form>";
        }  else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This print the Update form
     * @param string $title
     * @param bool $AddAccess
     * @param array $errors The list of erros
     * @param string $Name
     */
    public function print_UpdateForm($title,$UpdateAccess,$errors,$Name) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Name <span style=\"color:red;\">*</span></label>";
            print "<input name=\"Name\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a name\" value=\"".$Name."\">";
            print "</div>";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            print "<a class=\"btn\" href=\"Application.php\">".self::$BackIcon." Back</a>";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            print "</div>";
            print "</form>";
        }  else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the List allwhen searched
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     * @param bool $AssignAccess
     * @param string $search
     */
    public function print_Searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$AssignAccess,$search) {
        echo "<h2>Applications</h2>";
        echo "<div class=\"row\">";
        $Url ="Application.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Application.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Name'])."</a></td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Application.php?op=edit&id=".$row['App_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Application.php?op=delete&id=".$row['App_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Application.php?op=activate&id=".$row['App_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Application.php?op=show&id=".$row['App_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
}