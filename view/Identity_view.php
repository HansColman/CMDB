<?php
require_once 'view.php';
class Identity_view extends view
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * this function will print the info of the Identity
     * @param boolean $ViewAccess
     * @param boolean $AddAccess
     * @param Array $rows
     * @param boolean $AssignAccess
     * @param int $id
     * @param boolean $AccAccess
     * @param boolean $ReleaseAccountAccess
     * @param Array $accrows
     * @param boolean $DevAccess
     * @param Array $devicerows
     * @param boolean $ReleaseDeviceAccess
     * @param Array $logrows
     * @param string $LogDateFormat
     * @param string $DateFormat
     */
    public function print_info($ViewAccess,$AddAccess,$rows,$AssignAccess, $id,$AccAccess, $ReleaseAccountAccess, $accrows, $DevAccess, $devicerows, $ReleaseDeviceAccess,$logrows, $LogDateFormat, $DateFormat){
        echo "<h2>Identity details</h2>";
        if ($ViewAccess){
            if ($AddAccess){
                echo "<a class=\"btn icon-btn btn-success\" href=\"identity.php?op=new\">";
                echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span> Add</a>";
            }
            echo " <a href=\"Identity.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
            echo "<p></p>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>UserID</th>";
            echo "<th>E Mail</th>";
            echo "<th>Language</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['Name'])."</td>";
                echo "<td>".htmlentities($row['UserID'])."</td>";
                echo "<td>".htmlentities($row['E_Mail'])."</td>";
                echo "<td>".htmlentities($row['Language'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            if ($AssignAccess and $id >1){
                echo "<a class=\"btn icon-btn btn-success\" href=\"identity.php?op=assignDevice&id=".$id."\">";
                echo "<span class=\"fa fa-laptop\"></span> Assign device</a>";
            }
            if ($AccAccess){
                echo "<H3>Account overview</H3>";
                if (!empty($accrows)){
                    echo "<table class=\"table table-striped table-bordered\">";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>UserID</th>";
                    echo "<th>Application</th>";
                    echo "<th>From</th>";
                    echo "<th>Until</th>";
                    if ($ReleaseAccountAccess and $id >1){
                        echo "<th>Action</th>";
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($accrows as $account){
                        echo "<tr>";
                        echo "<td class=\"small\">".htmlentities($account["UserID"])."</td>";
                        echo "<td class=\"small\">".htmlentities($account["Application"])."</td>";
                        echo "<td class=\"small\">".htmlentities(date($DateFormat, strtotime($account["ValidFrom"])))."</td>";
                        if (!empty($account["ValidEnd"])){
                            echo "<td class=\"small\">".htmlentities(date($DateFormat, strtotime($account["ValidEnd"])))."</td>";
                        }else{
                            echo "<td class=\"small\">".date($DateFormat,strtotime("now +5 year"))."</td>";
                        }
                        if ($ReleaseAccountAccess and $id >1 and (empty($account["ValidEnd"]) or $account["ValidEnd"] <= date('Y-m-d'))){
                            echo "<td class=\"small\"><a class=\"btn btn-danger\" href=\"identity.php?op=releaseAccount&id=".$id."&accountId=".$account['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Deactivate\"><span class=\"fa fa-user-plus\"></span></a></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }else {
                    echo "No Accounts assigned to this Identity";
                }
            }
            if ($DevAccess){
                echo "<H3>Device overview</H3>";
                if (!empty($devicerows)){
                    echo "<table class=\"table table-striped table-bordered\">";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Category</th>";
                    echo "<th>Type</th>";
                    echo "<th>AssetTag</th>";
                    echo "<th>SerialNumber</th>";
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
                        if ($ReleaseDeviceAccess){
                            echo "<td class=\"small\"><a class=\"btn btn-danger\" href=\"identity.php?op=releaseDevice&id=".$id."&AssetTag=".$device["AssetTag"]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Release\"><span class=\"fa fa-laptop\"></span></a></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }else{
                    echo "No Devices assigned to this Identity";
                }
            }
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
                echo "No Log entries found for this Identity";
            }
        } else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the create form
     * @param string $title
     * @param boolean $AddAccess
     * @param array $errors
     * @param string $FristName
     * @param string $LastName
     * @param string $userid
     * @param string $company
     * @param string $EMail
     * @param string $Language
     * @param array $types
     */
    public function print_create($title,$AddAccess,$errors,$FristName,$LastName,$userid,$company,$EMail,$Language, $types){
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            if ( $errors ) {
                print '<ul class="list-group">';
                foreach ( $errors as $field => $error ) {
                    print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
                }
                print '</ul>';
            }
            echo"<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo"<label class=\"control-label\">First Name <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"FirstName\" type=\"text\" class=\"form-control\" placeholder=\"FirstName\" value=\"".$FristName."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Last Name <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"LastName\" type=\"text\" class=\"form-control\" placeholder=\"LastName\" value=\"".$LastName."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">UserID <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"UserID\" type=\"text\" class=\"form-control\" placeholder=\"UserID\" value=\"".$userid."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Company</label>";
            echo "<input name=\"Company\" type=\"text\" class=\"form-control\" placeholder=\"Company\" value=\"".$company."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">E-Mail Address <span style=\"color:red;\">*</span></label>";
            echo "<div class=\"controls\">";
            echo "<input name=\"EMail\" type=\"text\" class=\"form-control\" placeholder=\"E-Mail Address\" value=\"".$EMail."\">";
            echo "</div>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Language <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Language\" class=\"form-control\">";
            if(empty($Language)){
            	echo "<option value=\"\"></option>";
                echo "<option value=\"NL\">Dutch</option>";
                echo "<option value=\"FR\">French</option>";
                echo "<option value=\"EN\">English</option>";
            } elseif ($Language == "NL") {
            	echo "<option value=\"\"></option>";
                echo "<option value=\"NL\" selected>Dutch</option>";
                echo "<option value=\"FR\">French</option>";
                echo "<option value=\"EN\">English</option>";
            } elseif ($Language == "FR") {
            	echo "<option value=\"\"></option>";
                echo "<option value=\"NL\">Dutch</option>";
                echo "<option value=\"FR\" selected>French</option>";
                echo "<option value=\"EN\">English</option>";
            } elseif ($Language == "EN"){
               	echo "<option value=\"\"></option>";
                echo "<option value=\"NL\">Dutch</option>";
                echo "<option value=\"FR\" >French</option>";
                echo "<option value=\"EN\" selected>English</option>";
           	}
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"type\" class=\"form-control\">";
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
            echo "<a class=\"btn\" href=\"identity.php\">Back</a>";
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
     * This function will print the update form
     * @param string $title
     * @param array $errors
     * @param string $FristName
     * @param string $LastName
     * @param string $userid
     * @param string $company
     * @param string $EMail
     * @param string $Language
     * @param array $types
     */
    public function print_update($title,$errors,$FristName,$LastName,$userid,$company,$EMail,$Language, $types,$type){
        if ( $errors ) {
            print '<ul class="list-group">';
            foreach ( $errors as $field => $error ) {
                print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
            }
            print '</ul>';
        }
        echo"<form class=\"form-horizontal\" action=\"\" method=\"post\">";
        echo "<div class=\"form-group\">";
        echo"<label class=\"control-label\">First Name <span style=\"color:red;\">*</span></label>";
        echo "<input name=\"FirstName\" type=\"text\" class=\"form-control\" placeholder=\"FirstName\" value=\"".$FristName."\">";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<label class=\"control-label\">Last Name <span style=\"color:red;\">*</span></label>";
        echo "<input name=\"LastName\" type=\"text\" class=\"form-control\" placeholder=\"LastName\" value=\"".$LastName."\">";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<label class=\"control-label\">UserID <span style=\"color:red;\">*</span></label>";
        echo "<input name=\"UserID\" type=\"text\" class=\"form-control\" placeholder=\"UserID\" value=\"".$userid."\">";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<label class=\"control-label\">Company</label>";
        echo "<input name=\"Company\" type=\"text\" class=\"form-control\" placeholder=\"Company\" value=\"".$company."\">";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<label class=\"control-label\">E-Mail Address <span style=\"color:red;\">*</span></label>";
        echo "<div class=\"controls\">";
        echo "<input name=\"EMail\" type=\"text\" class=\"form-control\" placeholder=\"E-Mail Address\" value=\"".$EMail."\">";
        echo "</div>";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<label class=\"control-label\">Language <span style=\"color:red;\">*</span></label>";
        echo "<select name=\"Language\" class=\"form-control\">";
        if(empty($Language)){
            echo "<option value=\"\"></option>";
            echo "<option value=\"NL\">Dutch</option>";
            echo "<option value=\"FR\">French</option>";
            echo "<option value=\"EN\">English</option>";
        } elseif ($Language == "NL") {
            echo "<option value=\"\"></option>";
            echo "<option value=\"NL\" selected>Dutch</option>";
            echo "<option value=\"FR\">French</option>";
            echo "<option value=\"EN\">English</option>";
        } elseif ($Language == "FR") {
            echo "<option value=\"\"></option>";
            echo "<option value=\"NL\">Dutch</option>";
            echo "<option value=\"FR\" selected>French</option>";
            echo "<option value=\"EN\">English</option>";
        } elseif ($Language == "EN"){
            echo "<option value=\"\"></option>";
            echo "<option value=\"NL\">Dutch</option>";
            echo "<option value=\"FR\" >French</option>";
            echo "<option value=\"EN\" selected>English</option>";
        }
        echo "</select>";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
        echo "<select name=\"type\" class=\"form-control\">";
        echo "<option value=\"\"></option>";
        if (empty($type)){
            foreach ($types as $row){
                echo "<option value=\"".$row["Type_ID"]."\">".$row["Type"]." ".$row["Description"]."</option>";
            }
        }  else {
            foreach ($types as $row){
                if ($type == $row["Type_ID"]){
                    echo "<option value=\"".$row["Type_ID"]."\" selected>".$row["Type"]." ".$row["Description"]."</option>";
                }else{
                    echo "<option value=\"".$row["Type_ID"]."\">".$row["Type"]." ".$row["Description"]."</option>";
                }
            }
        }
        echo "</select>";
        echo "</div>";
        echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
        echo "<div class=\"form-actions\">";
        echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
        echo "<a class=\"btn\" href=\"identity.php\">Back</a>";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
        echo "</div>";
        echo "</form>";
    }
    /**
     * This function will print the complete list of Identies
     * @param boolean $AddAccess
     * @param array $rows
     * @param boolean $UpdateAccess
     * @param boolean $DeleteAccess
     * @param boolean $ActiveAccess
     * @param boolean $AssignDeviceAccess
     * @param boolean $AssignAccess
     * @param boolean $InfoAccess
     */
    public function print_all($AddAccess, $rows, $UpdateAccess,$DeleteAccess, $ActiveAccess,$AssignDeviceAccess,$AssignAccess, $InfoAccess) 
    {
        echo "<h2>Identities</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"identity.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        echo "<div class=\"col-md-6 text-right\">";
        ?>
        <form class="form-inline" role="search" action="identity.php?op=search" method="post">
            <div class="form-group">
               <input name="search" type="text" class="form-control" placeholder="Search">
            </div>
               <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
        </form>
        <?php
        echo "</div>";
        echo "</div>";
        echo "<table class=\"table table-striped table-bordered\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th><a href=\"identity.php?orderby=Name\">Name</a></th>";
        echo "<th><a href=\"identity.php?orderby=UserID\">UserID</a></th>";
        echo "<th><a href=\"identity.php?orderby=E_Mail\">E Mail</a></th>";
        echo "<th><a href=\"identity.php?orderby=Language\">Language</a></th>";
        echo "<th><a href=\"identity.php?orderby=Type\">Type</a></th>";
        echo "<th><a href=\"identity.php?orderby=Active\">Active</a></th>";
        echo "<th>Actions</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Name'])."</a></td>";
            echo "<td>".htmlentities($row['UserID'])."</td>";
            echo "<td>".htmlentities($row['E_Mail'])."</td>";
            echo "<td>".htmlentities($row['Language'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            if ($row['Iden_Id'] >1){
                IF ($UpdateAccess){
                    echo "<a class=\"btn btn-primary\" href=\"identity.php?op=edit&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo "<span class=\"fa fa-pencil\"></span></a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"identity.php?op=delete&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Deactivate\">";
                    echo "<span class=\"fa fa fa-toggle-off\"></span></a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"identity.php?op=activate&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo "<span class=\"fa fa fa-toggle-on\"></span></a>";
                }
                if ($row["Active"] == "Active" and $AssignDeviceAccess){
                    echo "<a class=\"btn btn-success\" href=\"identity.php?op=assignDevice&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Devices\">";
                    echo "<span class=\"fa fa-laptop\"></span></a>";
                }
                if ($row["Active"] == "Active" and $AssignAccess){
                    echo "<a class=\"btn btn-success\" href=\"identity.php?op=assign&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Account\">";
                    echo "<span class=\"fa fa-user-plus\"></span></a>";
                }
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"identity.php?op=show&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\" id=\"info".$row['Iden_Id']."\">";
                echo "<span class=\"fa fa-info\"></span></a>";
            }    
            echo "</td>"; 
            echo "</tr>";     
        endforeach;
        echo "</tbody>";
        echo "</table>";
    }
    
}

