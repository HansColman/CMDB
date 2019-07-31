<?php
require_once 'View.php';
class AccountView extends View
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will print the info of an account
     * @param boolean $ViewAccess
     * @param boolean $AddAccess
     * @param array $rows
     * @param boolean $IdenOverAccess
     * @param boolean $ReleaseIdenAcces
     * @param array $Idenrows
     * @param array $logrows
     * @param string $LogDateFormat
     * @param string $DateFormat
     */
    public function print_info($ViewAccess,$AddAccess,$rows,$IdenOverAccess,$ReleaseIdenAcces,$AssignAccess,$id,$Idenrows,$logrows,$LogDateFormat,$DateFormat){
        echo "<h2>Account Details";
        echo " <a href=\"Account.php\" class=\"btn btn-default float-right\">".self::$BackIcon." Back</a></h2>";
        if ($ViewAccess){
            echo "<p></p>";
            $this->print_table();
            echo "<tr>";
            echo "<th>UserID</th>";
            echo "<th>Application</th>";
            echo "<th>Type</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['UserID'])."</td>";
            echo "<td>".htmlentities($row['Application'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $Url = "Account.php?op=new";
            $this->print_addBelow($AddAccess, $Url);
            if ($AssignAccess and $id >1){
                echo "<a class=\"btn btn-success\" href=\"Account.php?op=assign&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\" id=\"Assign\">";
                echo self::$AddIdenttyIcon." </a>";
            }
            if ($IdenOverAccess){
                echo "<H3>Identity overview</H3>";
                if (!empty($Idenrows)){
                    $this->print_table();
                    echo "<tr>";
                    echo "<th>Name</th>";
                    echo "<th>UserID</th>";
                    echo "<th>From</th>";
                    echo "<th>Until</th>";
                    if ($ReleaseIdenAcces and $row["Acc_ID"] >1){
                        echo "<th>Action</th>";
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($Idenrows as $account){
                        echo "<tr>";
                        echo "<td class=\"small\">".htmlentities($account["Name"])."</td>";
                        echo "<td class=\"small\">".htmlentities($account["UserID"])."</td>";
                        echo "<td class=\"small\">".htmlentities(date($DateFormat, strtotime($account["ValidFrom"])))."</td>";
                        if (!empty($account["ValidEnd"])){
                            echo "<td class=\"small\">".htmlentities(date($DateFormat, strtotime($account["ValidEnd"])))."</td>";
                        }else{
                            echo "<td class=\"small\">".date($DateFormat,strtotime("now +1 year"))."</td>";
                        }
                        if ($ReleaseIdenAcces and $row["Acc_ID"] >1){
                            if (empty($account["ValidEnd"]) or date($DateFormat,strtotime($account["ValidEnd"])) >= date('Y-m-d')){
                                echo "<td class=\"small\"><a class=\"btn btn-danger\" id=\"ReleaseIdentity".$account["UserID"]."\" href=\"Account.php?op=releaseIdentity&id=".$row["Acc_ID"]."&idenId=".$account["Iden_ID"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Release Identity\">".self::$ReleaseIdenIcon."</a></td>";
                            }else{
                                echo "<td class=\"small\"></td>";
                            }
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }else {
                    echo "No Identities assigned to this Account";
                }
            }
            $this->print_loglines($logrows, $LogDateFormat, "Account");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print all accounts
     * @param boolean $AddAccess
     * @param array $rows
     * @param boolean $UpdateAccess
     * @param boolean $DeleteAccess
     * @param boolean $ActiveAccess
     * @param boolean $AssignAccess
     * @param boolean $InfoAccess
     */
    public function print_listAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$AssignAccess,$InfoAccess){
        echo "<h2>Accounts</h2>";
        echo "<div class=\"row\">";
        $Url = "Account.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("account.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"Account.php?orderby=UserID\">UserID</a></th>";
            echo "<th><a href=\"Account.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"Account.php?orderby=Application\">Application</a></th>";
            echo "<th><a href=\"Account.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['UserID'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Application'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>";
                if ($row['Acc_ID'] >1){
                    IF ($UpdateAccess){
                        echo "<a class=\"btn btn-primary\" href=\"Account.php?op=edit&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                        echo self::$EditIcon."</a>";
                    }
                    if ($row["Active"] == "Active" and $DeleteAccess){
                        echo "<a class=\"btn btn-danger\" href=\"Account.php?op=delete&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                        echo self::$DeactivateIcon."</a>";
                    }elseif ($ActiveAccess){
                        echo "<a class=\"btn btn-glyphicon\" href=\"Account.php?op=activate&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                        echo self::$ActivateIcon."</a>";
                    }
                    if ($row["Active"] == "Active" and $AssignAccess){
                        echo "<a class=\"btn btn-success\" href=\"Account.php?op=assign&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                        echo self::$AddIdenttyIcon."</a>";
                    }
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Account.php?op=show&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will print the new Account form
     * @param string $title
     * @param boolean $AddAccess
     * @param boolean $errors
     * @param string $UserID
     * @param array $types
     * @param boolean $applications
     */
    public function print_create($title,$AddAccess,$errors,$UserID,$types,$applications) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">UserID <span style=\"color:red;\">*</span></label>";
            print "<input class=\"form-control\" name=\"UserID\" type=\"text\" placeholder=\"Please insert UserID\" value=\"".$UserID."\">";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            print "<select name=\"type\" class=\"form-control\">";
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
            print "</select>";
            print "</div>";
            print "<div class=\"Form-group\">";
            print "<label class=\"control-label\">Application <span style=\"color:red;\">*</span></label>";
            print "<select name=\"Application\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Application"])){
                foreach ($applications as $application){
                    echo "<option value=\"".$application["App_ID"]."\">".$application["Name"]."</option>";
                }
            }  else {
                foreach ($applications as $application){
                    if ($_POST["Application"] == $application["App_ID"]){
                        echo "<option value=\"".$application["App_ID"]."\" selected>".$application["Name"]."</option>";
                    }else{
                        echo "<option value=\"".$application["App_ID"]."\">".$application["Name"]."</option>";
                    }
                }
            }
            print "</select>";
            print "</div>";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            print "<a class=\"btn\" href=\"account.php\">".self::$BackIcon." Back</a>";
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
     * @param array $UpdateAccess
     * @param array $errors
     * @param string $UserID
     * @param int $Type
     * @param array $types
     * @param int $Application
     * @param string $applications
     */
    public function print_update($title,$UpdateAccess,$errors,$UserID,$Type,$types,$Application,$applications) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">UserID <span style=\"color:red;\">*</span></label>";
            print "<input name=\"UserID\" type=\"text\" placeholder=\"UserID\" value=\"".$UserID."\">";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            print "<select name=\"type\" class=\"form-control\">";
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
            print "</select>";
            print "</div>";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Application <span style=\"color:red;\">*</span></label>";
            print "<select name=\"Application\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Application)){
                foreach ($applications as $application){
                    echo "<option value=\"".$application["App_ID"]."\">".$application["Name"]."</option>";
                }
            }  else {
                foreach ($applications as $application){
                    if ($Application == $application["App_ID"]){
                        echo "<option value=\"".$application["App_ID"]."\" selected>".$application["Name"]."</option>";
                    }else{
                        echo "<option value=\"".$application["App_ID"]."\">".$application["Name"]."</option>";
                    }
                }
            }
            print "</select>";
            print "</div> ";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            print "<a class=\"btn\" href=\"account.php\">".self::$BackIcon." Back</a>";
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
     * This function will print the delte form
     * @param string $title
     * @param array $errors
     * @param array $rows
     * @param string $Reason
     */
    public function print_delete($title,$errors,$rows,$Reason,$DeleteAccess) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($DeleteAccess){
            $this->print_ValistationErrors($errors);
            $this->print_table();
            echo "<tr>";
            echo "<th>UserID</th>";
            echo "<th>Application</th>";
            echo "<th>Type</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['UserID'])."</td>";
                echo "<td>".htmlentities($row['Application'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $this->deleteform($Reason,"Account.php");
        }  else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the assing to Identity Form
     * @param string $title
     * @param boolean $AssignAccess
     * @param array $errors
     * @param array $rows
     * @param array $identities
     */
    public function print_assignIdenity($title,$AssignAccess,$errors,$rows,$identities) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AssignAccess){
            $this->print_ValistationErrors($errors);
            $this->print_table();
            echo "<tr>";
            echo "<th>UserID</th>";
            echo "<th>Application</th>";
            echo "<th>Type</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['UserID'])."</td>";
                echo "<td>".htmlentities($row['Application'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            echo "<p></p>";
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label for=\"Identity\">Identity <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"identity\" id=\"Identity\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["identity"])){
                foreach ($identities as $identity){
                    echo "<option value=\"".$identity["Iden_ID"]."\">".$identity["Name"]." ".$identity["UserID"]."</option>";
                }
            }  else {
                foreach ($identities as $identity){
                    if ($_POST["account"] == $identity["Iden_ID"]){
                        echo "<option value=\"".$identity["Iden_ID"]."\" selected>".$identity["Name"]." ".$identity["UserID"]."</option>";
                    }else{
                        echo "<option value=\"".$identity["Iden_ID"]."\">".$identity["Name"]." ".$identity["UserID"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group has-feedback\">";
            echo "<label class=\"control-label\">From <span style=\"color:red;\">*</span></label>";
            echo "<input type=\"text\" class=\"form-control\" placeholder=\"DD/MM/YYYY\" name=\"start\" id=\"start-date\"/>";
            echo "<i class=\"glyphicon glyphicon-calendar form-control-feedback date-pick\"></i>";
            echo "</div>";
            echo "<div class=\"form-group has-feedback\">";
            echo "<label class=\"control-label\">Until</label>";
            echo "<input type=\"text\" class=\"form-control\" placeholder=\"DD/MM/YYYY\" name=\"end\" id=\"end-date\"/>";
            echo "<i class=\"glyphicon glyphicon-calendar form-control-feedback\"></i>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Assign</button>";
            echo "<a class=\"btn\" href=\"Account.php\">".self::$BackIcon." Back</a>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
            include 'script.php';
    	}else{
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the searched accounts
     * @param boolean $AddAccess
     * @param array $rows
     * @param boolean $UpdateAccess
     * @param boolean $DeleteAccess
     * @param boolean $ActiveAccess
     * @param boolean $AssignAccess
     * @param boolean $InfoAccess
     * @param string $search
     */
    public function print_searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$AssignAccess,$InfoAccess,$search) {
        echo "<h2>Accounts</h2>";
        echo "<div class=\"row\">";
        $Url = "Account.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Account.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th>UserID</th>";
            echo "<th>Type</th>";
            echo "<th>Application</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['UserID'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Application'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "<td>";
                IF ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"Account.php?op=edit&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo self::$EditIcon."</a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"Account.php?op=delete&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo self::$DeactivateIcon."</a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"Account.php?op=activate&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo self::$ActivateIcon."</a>";
                }
                if ($row["Active"] == "Active" and $AssignAccess){
                    echo "<a class=\"btn btn-success\" href=\"Account.php?op=assign&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
                    echo self::$AddIdenttyIcon."</a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"Account.php?op=show&id=".$row['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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