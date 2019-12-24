<?php
require_once 'view.php';

class SubscriptionView extends View
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will print the overview
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $AssignAccess
     * @param bool $InfoAccess
     */
    public function print_listAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$AssignAccess,$InfoAccess){
        echo "<h2>Subscriptions</h2>";
        echo "<div class=\"row\">";
        $Url = "Subscription.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Subscription.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"Subscription.php?orderby=phonenumber\">Phonenumber</a></th>";
            echo "<th><a href=\"Subscription.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"Subscription.php?orderby=Category\">Category</a></th>";
            echo "<th><a href=\"Subscription.php?orderby=Name\">Ussage</a></th>";
            echo "<th><a href=\"Subscription.php?orderby=IMEI\">IMEI</a></th>";
            echo "<th><a href=\"Subscription.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['PhoneNumber'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Category'])."</td>";
                echo "<td>".htmlentities($row['ussage'])."</td>";
                echo "<td>".htmlentities($row['IMEI'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>";
                if ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Subscription.php?op=edit&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo self::$EditIcon."</a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Subscription.php?op=delete&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo self::$DeactivateIcon."</a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Subscription.php?op=activate&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo self::$ActivateIcon."</a>";
                }
                if ($row["Active"] == "Active" and $AssignAccess){
                    echo "<a class=\"btn btn-success\" href=\"Subscription.php?op=assign&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                    echo self::$AddIdenttyIcon."</a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Subscription.php?op=show&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will print the Searched overview
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $AssignAccess
     * @param bool $InfoAccess
     * @param string $search
     */
    public function print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignAccess, $InfoAccess, $search){
        echo "<h2>Subscriptions</h2>";
        echo "<div class=\"row\">";
        $Url = "Subscription.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Subscription.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th>Phonenumber</th>";
            echo "<th>Type</th>";
            echo "<th>Category</th>";
            echo "<th>Ussage</th>";
            echo "<th>IMEI</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['PhoneNumber'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Category'])."</td>";
            echo "<td>".htmlentities($row['ussage'])."</td>";
            echo "<td>".htmlentities($row['IMEI'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            if ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Subscription.php?op=edit&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Subscription.php?op=delete&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Subscription.php?op=activate&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($row["Active"] == "Active" and $AssignAccess){
                echo "<a class=\"btn btn-success\" href=\"Subscription.php?op=assign&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                echo self::$AddIdenttyIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Subscription.php?op=show&id=".$row['Sub_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This funcion will print the create from
     * @param string $title
     * @param bool $addAccess
     * @param array $errors
     * @param string $phoneNumber
     * @param array $types
     */
    public function print_create($title,$addAccess,$errors,$phoneNumber,$types){
        print "<h2>".htmlentities($title)."</h2>";
        if ($addAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">UserID <span style=\"color:red;\">*</span></label>";
            print "<input class=\"form-control\" name=\"PhoneNumber\" type=\"text\" placeholder=\"Please insert PhoneNumber\" value=\"".$phoneNumber."\">";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            print "<select name=\"type\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["type"])){
                foreach ($types as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Description"]." ".$type["Category"]."</option>";
                }
            }  else {
                foreach ($types as $type){
                    if ($_POST["type"] == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Description"]." ".$type["Category"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Description"]." ".$type["Category"]."</option>";
                    }
                }
            }
            print "</select>";
            print "</div>";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            print "<a class=\"btn\" href=\"Subscription.php\">".self::$BackIcon." Back</a>";
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
     * This function will print the update form
     * @param string $title
     * @param bool $EditAccess
     * @param array $errors
     * @param string $phoneNumber
     * @param array $types
     */
    public function print_update($title,$EditAccess,$errors,$phoneNumber,$types,$type){
        print "<h2>".htmlentities($title)."</h2>";
        if ($EditAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">UserID <span style=\"color:red;\">*</span></label>";
            print "<input class=\"form-control\" name=\"PhoneNumber\" type=\"text\" placeholder=\"Please insert PhoneNumber\" value=\"".$phoneNumber."\">";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            print "<select name=\"type\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($type)){
                foreach ($types as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Description"]." ".$type["Category"]."</option>";
                }
            }  else {
                foreach ($types as $type){
                    if ($type == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Description"]." ".$type["Category"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Description"]." ".$type["Category"]."</option>";
                    }
                }
            }
            print "</select>";
            print "</div>";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            print "<a class=\"btn\" href=\"Subscription.php\">".self::$BackIcon." Back</a>";
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
     * This function will print the delete form
     * @param bool $deleteAccess
     * @param array $rows
     * @param string $title
     * @param array $errors
     * @param string $reason
     */
    public function print_delete($deleteAccess,$rows,$title,$errors,$reason){
        print "<h2>".htmlentities($title)."</h2>";
        if ($deleteAccess){
            $this->print_ValistationErrors($errors);
            $this->print_table();
            echo "<tr>";
            echo "<th>PhoneNumber</th>";
            echo "<th>Type</th>";
            echo "<th>Category</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
                echo "<td>".htmlentities($row['PhoneNumber'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Category'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $this->deleteform($reason,"Subscription.php");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
}