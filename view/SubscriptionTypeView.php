<?php
require_once 'view.php';

class SubscriptionTypeView extends View
{
    /**
     * This function will print the Info details
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_info($ViewAccess,$AddAccess,$rows,$logrows,$LogDateFormat) {
        echo "<H2>Subscription type overview";
        echo "<a href=\"SubscriptionType.php\" class=\"btn btn-default float-right\">".self::$BackIcon." Back</a></H2>";
        if ($ViewAccess){
            echo "<p></p>";
            $this->print_table();
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Description</th>";
            echo "<th>Provider</th>";
            echo "<th>Category</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Description'])."</td>";
                echo "<td>".htmlentities($row['Provider'])."</td>";
                echo "<td>".htmlentities($row['Category'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $Url = "SubscriptionType.php?op=new";
            $this->print_addBelow($AddAccess, $Url);
            //Log Overvieuw
            $this->print_loglines($logrows, $LogDateFormat, "Subscription type");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the overview
     * @param array $rows
     * @param bool $AddAccess
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     */
    public function list_all($rows,$AddAccess,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess) {
        echo "<h2>Supscription type</h2>";
        echo "<div class=\"row\">";
        $Url = "SubscriptionType.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("SubscriptionType.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"SubscriptionType.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"SubscriptionType.php?orderby=Description\">Description</a></th>";
            echo "<th><a href=\"SubscriptionType.php?orderby=Provider\">Provider</a></th>";
            echo "<th><a href=\"SubscriptionType.php?orderby=Category\">Category</a></th>";
            echo "<th><a href=\"SubscriptionType.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Description'])."</td>";
                echo "<td>".htmlentities($row['Provider'])."</td>";
                echo "<td>".htmlentities($row['Category'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>";
                if ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"SubscriptionType.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo self::$EditIcon."</a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"SubscriptionType.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo self::$DeactivateIcon."</a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"SubscriptionType.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo self::$ActivateIcon."</a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"SubscriptionType.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will printed the searched rows
     * @param array $rows
     * @param bool $AddAccess
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param string $InfoAccess
     */
    public function print_search($rows,$AddAccess,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$search) {
        echo "<h2>Supscription type</h2>";
        echo "<div class=\"row\">";
        $Url = "SubscriptionType.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("SubscriptionType.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Description</th>";
            echo "<th>Provider</th>";
            echo "<th>Category</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Description'])."</td>";
            echo "<td>".htmlentities($row['Provider'])."</td>";
            echo "<td>".htmlentities($row['Category'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            if ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"SubscriptionType.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"SubscriptionType.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"SubscriptionType.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"SubscriptionType.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will print the create form
     * @param string $title
     * @param array $errors
     * @param string $AddAccess
     * @param bool $Type
     * @param string $Description
     * @param string $Provider
     * @param int $Category
     */
    public function print_CreateForm($title, $errors,$AddAccess,$Type,$Description,$Provider,$Category) {
        print "<h2>".htmlentities($title)."</h2>";
        if($AddAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            print "<input name=\"Type\" type=\"text\" class=\"form-control\" placeholder=\"Please insert Type\" value=\"".$Type."\">";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Description <span style=\"color:red;\">*</span></label>";
            print "<input name=\"Description\" type=\"text\" class=\"form-control\" placeholder=\"Please insert Description\" value=\"".$Description."\">";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Provider <span style=\"color:red;\">*</span></label>";
            print "<input name=\"Provider\" type=\"text\" class=\"form-control\" placeholder=\"Please insert provider\" value=\"".$Provider."\">";
            print "</div>";
            echo "<label class=\"control-label\">Category <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Category\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Category"])){
                echo "<option value=\"3\">Mobile Subscription</option>";
                echo "<option value=\"4\">Internet Subscription</option>";
            }elseif ($_POST["Category"] == "3"){
                echo "<option value=\"3\" selected>Mobile Subscription</option>";
                echo "<option value=\"4\">Internet Subscription</option>";
            }else {
                echo "<option value=\"3\">Mobile Subscription</option>";
                echo "<option value=\"4\" selected>Internet Subscription</option>";
            }
            echo "</select>";
            echo "</div>";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            print "<a class=\"btn\" href=\"SubscriptionType.php\">".self::$BackIcon." Back</a>";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            print "</div>";
            print "</form>";
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the edit form
     * @param string $title
     * @param array $errors
     * @param bool $EditAccess
     * @param string $Type
     * @param string $Description
     * @param string $Provider
     * @param int $Category
     */
    public function print_edit($title, $errors,$EditAccess,$Type,$Description,$Provider,$Category) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($EditAccess) {
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            print "<input name=\"Type\" type=\"text\" class=\"form-control\" placeholder=\"Please insert Type\" value=\"".$Type."\">";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Description <span style=\"color:red;\">*</span></label>";
            print "<input name=\"Description\" type=\"text\" class=\"form-control\" placeholder=\"Please insert Description\" value=\"".$Description."\">";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Provider <span style=\"color:red;\">*</span></label>";
            print "<input name=\"Provider\" type=\"text\" class=\"form-control\" placeholder=\"Please insert provider\" value=\"".$Provider."\">";
            print "</div>";
            echo "<label class=\"control-label\">Category <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Category\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Category)){
                echo "<option value=\"3\">Mobile Subscription</option>";
                echo "<option value=\"4\">Internet Subscription</option>";
            }elseif ($Category == "3"){
                echo "<option value=\"3\" selected>Mobile Subscription</option>";
                echo "<option value=\"4\">Internet Subscription</option>";
            }else {
                echo "<option value=\"3\">Mobile Subscription</option>";
                echo "<option value=\"4\" selected>Internet Subscription</option>";
            }
            echo "</select>";
            echo "</div>";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            print "<a class=\"btn\" href=\"SubscriptionType.php\">".self::$BackIcon." Back</a>";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            print "</div>";
            print "</form>";
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the delete form
     * @param string $title
     * @param bool $DeleteAccess
     * @param array $errors
     * @param array $rows
     * @param string $reason
     */
    public function print_delete($title,$DeleteAccess,$errors,$rows,$reason) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($DeleteAccess){
            $this->print_ValistationErrors($errors);
            $this->print_table();
            echo "<tr>";
            echo "<th>Type</th>";
            echo "<th>Description</th>";
            echo "<th>Provider</th>";
            echo "<th>Category</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Description'])."</td>";
                echo "<td>".htmlentities($row['Provider'])."</td>";
                echo "<td>".htmlentities($row['Category'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $backUrl = "SubscriptionType.php";
            $this->deleteform($reason, $backUrl);
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
}

