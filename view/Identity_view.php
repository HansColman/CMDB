<?php
require_once 'view.php';
class identityView extends View
{
    /**
     * This function will print the info
     * @param boolean $ViewAccess
     * @param boolean $AddAccess
     * @param array $rows
     * @param boolean $AssignAccess
     * @param int $id
     * @param boolean $AccAccess
     * @param boolean $ReleaseAccountAccess
     * @param array $accrows
     * @param boolean $DevAccess
     * @param boolean $devicerows
     * @param boolean $ReleaseDeviceAccess
     * @param array $logrows
     * @param string $LogDateFormat
     * @param string $DateFormat
     */
    public function print_info($ViewAccess,$AddAccess,$rows,$AssignAccess, $id,$AccAccess, $ReleaseAccountAccess, $accrows, $DevAccess, $devicerows, $ReleaseDeviceAccess,$logrows, $LogDateFormat, $DateFormat)
    {
        echo "<h2>Identity details</h2>";
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
        foreach ($rows as $row){
            echo "<tr>";
            echo "<td>".htmlentities($row['Name'])."</td>";
            echo "<td>".htmlentities($row['UserID'])."</td>";
            echo "<td>".htmlentities($row['E_Mail'])."</td>";
            echo "<td>".htmlentities($row['Language'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "</tr>";
        }
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
                    if ($ReleaseAccountAccess and $id >1){
                        if (empty($account["ValidEnd"]) or date($DateFormat,strtotime($account["ValidEnd"])) >= date('Y-m-d')){
                            echo "<td class=\"small\"><a class=\"btn btn-danger\" href=\"identity.php?op=releaseAccount&id=".$id."&accountId=".$account['Acc_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Deactivate\"><span class=\"fa fa-user-plus\"></span></a></td>";
                        }else{
                            echo "<td class=\"small\"></td>";
                        }
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
    public function print_create($title,$AddAccess,$errors,$FristName,$LastName,$userid,$company,$EMail,$Language, $types)
    {
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
    public function print_update($title,$errors,$FristName,$LastName,$userid,$company,$EMail,$Language, $types,$type)
    {
        print "<h2>".htmlentities($title)."</h2>";
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
        echo "<form class=\"form-inline\" role=\"search\" action=\"identity.php?op=search\" method=\"post\">";
        echo "<div class=\"form-group\">";
        echo "<input name=\"search\" type=\"text\" class=\"form-control\" placeholder=\"Search\">";
        echo "</div>";
        echo "<button type=\"submit\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-search\"></i></button>";
        echo "</form>";
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
    /**
     * This function will print the delete Form
     * @param string $title
     * @param array $rows
     * @param string $Reason
     * @param array $errors
     */
    public function print_delete($title,$rows,$Reason,$errors) 
    {
        print "<h2>".htmlentities($title)."</h2>";
        if ( $errors ) {
            print '<ul class="list-group">';
            foreach ( $errors as $field => $error ) {
                print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
            }
            print '</ul>';
        }
        print "<table class=\"table table-striped table-bordered\">";
        print "<thead>";
        print "<tr>";
        print "<th>Name</th>";
        print "<th>UserID</th>";
        print "<th>E Mail</th>";
        print "<th>Language</th>";
        print "<th>Type</th>";
        print "</tr>";
        print "</thead>";
        print "<tbody>";
        foreach($rows as $row){
            print "<tr>";
            print "<td>".htmlentities($row["Name"])."</td>";
            print "<td>".htmlentities($row["UserID"])."</td>";
            print "<td>".htmlentities($row["Type_ID"])."</td>";
            print "<td>".htmlentities($row["Company"])."</td>";
            print "<td>".htmlentities($row["Language"])."</td>";
            print "<td>".htmlentities($row["E_Mail"])."</td>";
            print "</tr>";
        }       
        print "</tbody>";
        print "</table>";
        $this->deleteform($Reason,"identity.php");
    }
    /**
     * This function will print the assign defice form
     * @param string $title
     * @param array $errors
     * @param boolean $AssignAccess
     * @param array $idenrows
     * @param array $Laptoprows
     * @param string $Laptop
     * @param array $Monitorrows
     * @param string $Screen
     * @param array $Tokenrows
     * @param string $Token
     * @param array $Desktoprows
     * @param string $Desktop
     * @param array $Mobilerows
     * @param int $Mobilie
     * @param array $Internetrows
     * @param int $Internet
     */
    public function print_assignDevice($title,$errors,$AssignAccess,$idenrows,$Laptoprows,$Laptop,$Monitorrows,$Screen,$Tokenrows,$Token,$Desktoprows,$Desktop,$Mobilerows,$Mobilie,$Internetrows,$Internet) {
        echo "<H2>".htmlentities($title)."</H2>";
        if ( $errors ) {
            print '<ul class="list-group">';
            foreach ( $errors as $field => $error ) {
                print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
            }
            print '</ul>';
        }
        if ($AssignAccess){
            
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
            foreach ($idenrows as $row):
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
            echo "<p style=\"color:red;\">Please select at least on of the options</p>";
            echo "<form action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Laptop</label>";
            echo "<select name=\"Laptop\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Laptop)){
                foreach ($Laptoprows as $type){
                    echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                }
            }  else {
                foreach ($Laptoprows as $type){
                    if ($Laptop == $type["AssetTag"]){
                        echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }else{
                        echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Desktop</label>";
            echo "<select name=\"Desktop\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Desktop)){
                foreach ($Desktoprows as $type){
                    echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                }
            }  else {
                foreach ($Desktoprows as $type){
                    if (Desktop == $type["AssetTag"]){
                        echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }else{
                        echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Screen</label>";
            echo "<select name=\"Screen\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Screen)){
                foreach ($Monitorrows as $type){
                    echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                }
            }  else {
                foreach ($Monitorrows as $type){
                    if ($Screen == $type["AssetTag"]){
                        echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }else{
                        echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Token</label>";
            echo "<select name=\"Token\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Token)){
                foreach ($Tokenrows as $type){
                    echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                }
            }  else {
                foreach ($Tokenrows as $type){
                    if ($Token == $type["AssetTag"]){
                        echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }else{
                        echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }
                }
            }
            echo " </select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Mobile</label>";
            echo "<select name=\"Mobile\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Mobilie)){
                var_dump($Mobilerows);
                foreach ($Mobilerows as $type){
                    echo "<option value=\"".$type["IMEI"]."\">".$type["IMEI"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                }
            }  else {
                foreach ($Mobilerows as $type){
                    if ($Mobilie == $type["IMEI"]){
                        echo "<option value=\"".$type["IMEI"]."\" selected>".$type["IMEI"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }else{
                        echo "<option value=\"".$type["IMEI"]."\">".$type["IMEI"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Internet</label>";
            echo "<select name=\"Internet\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Internet)){
                foreach ($Internetrows as $type){
                    echo "<option value=\"".$type["PhoneNumber"]."\">".$type["PhoneNumber"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                }
            }  else {
                foreach ($Internetrows as $type){
                    if ($Internet == $type["PhoneNumber"]){
                        echo "<option value=\"".$type["PhoneNumber"]."\" selected>".$type["PhoneNumber"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }else{
                        echo "<option value=\"".$type["PhoneNumber"]."\">".$type["PhoneNumber"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Assign</button>";
            echo " <a href=\"Identity.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
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
     * This function will print the fsearch results
     * @param boolean $AddAccess
     * @param array $rows
     * @param boolean $UpdateAccess
     * @param boolean $DeleteAccess
     * @param boolean $ActiveAccess
     * @param boolean $AssignDeviceAccess
     * @param boolean $AssignAccess
     * @param boolean $InfoAccess
     * @param string $search
     */
    public function print_searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$AssignDeviceAccess,$AssignAccess,$InfoAccess,$search) {
        echo "<h2>Identities</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6\"><a class=\"btn icon-btn btn-success\" href=\"identity.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo " <a href=\"Identity.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
            echo "</div>";
        }
        echo "<div class=\"col-md-6 text-right\">";
        echo "<form class=\"form-inline\" role=\"search\" action=\"identity.php?op=search\" method=\"post\">";
        echo "<div class=\"form-group\">";
        echo "<input name=\"search\" type=\"text\" class=\"form-control\" placeholder=\"Search\">";
        echo "</div>";
        echo "<button type=\"submit\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-search\"></i></button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>UserID</th>";
            echo "<th>E Mail</th>";
            echo "<th>Language</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
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
                    echo "<a class=\"btn btn-info\" href=\"identity.php?op=show&id=".$row['Iden_Id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
                    echo "<span class=\"fa fa-info\"></span></a>";
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
     * This function wiil print the release device form
     * @param string $title
     * @param array $errors
     * @param boolean $DeallocateAccess
     * @param array $idenrows
     * @param array $devrows
     * @param array $devicerows
     * @param string $AdminName
     */
    public function print_releaseDevice($title,$errors,$DeallocateAccess,$idenrows,$devrows,$devicerows,$AdminName){
        echo "<H2>".htmlentities($title)."</H2>";
        if ( $errors ) {
            print '<ul class="list-group">';
            foreach ( $errors as $field => $error ) {
                print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
            }
            print '</ul>';
        }
        if ($DeallocateAccess){
            $Name = "";
            echo "<h3>Person info</h3>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>UserID</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($idenrows as $identity){
                $Name = htmlentities($identity["Name"]);
                echo "<tr>";
                echo "<td class=\"small\">".htmlentities($identity["Name"])."</td>";
                echo "<td class=\"small\">".htmlentities($identity["UserID"])."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            if (empty($devrows)){
                echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
        	    echo "<div class=\"form-group\">";
        	    echo "<div class=\"form-check form-check-inline\">";
        		$amount = 1;
        		foreach($devicerows as $device) :
                    echo "<label class=\"checkbox-inline\">";
                    echo "<input type=\"checkbox\" name=\"".$device["Category"].$amount."\" value=\"".$device["AssetTag"]."\">".$device["AssetTag"]." ".$device["Type"];
                    echo "</label>";
                    $amount ++;
        		endforeach;
        		echo "</div>";
                echo "</div>";
            }else{
                echo "<h3>Device info</h3>";
                echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
                echo "<table class=\"table table-striped table-bordered\">";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Category</th>";
                echo "<th>Type</th>";
                echo "<th>AssetTag</th>";
                echo "<th>SerialNumber</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $amount = 1;
                foreach ($devrows as $device){
                    echo "<tr>";
                    echo "<td class=\"small\">".htmlentities($device["Category"])."</td>";
                    echo "<td class=\"small\">".htmlentities($device["Type"])."</td>";
                    echo "<td class=\"small\">".htmlentities($device["AssetTag"])."</td>";
                    echo "<td class=\"small\">".htmlentities($device["SerialNumber"])."</td>";
                    echo "</tr>";
                    echo "<input type=\"hidden\" name=\"".$device["Category"].$amount."\" value=\"".$device["AssetTag"]."\" /><br>";
                }
                echo "</tbody>";
                echo "</table>";
            }
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\" for=\"Employee\">Employee</label>";
            echo "<input name=\"Employee\" type=\"text\" id=\"Employee\" class=\"form-control\" placeholder=\"Please insert name of person\" value=\"".$Name."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\" for=\"ITEmp\">IT Employee</label>";
            echo "<input name=\"ITEmp\" type=\"text\" id=\"ITEmp\" class=\"form-control\"  placeholder=\"Please insert reason\" value=\"".$AdminName."\">";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create PDF</button>";
          	if($_SESSION["Class"] == "Device"){
            	echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">Back</a>";
            }else{
            	echo "<a class=\"btn\" href=\"Identity.php\">Back</a>";
           	}
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
     * This function will print the assign account form
     * @param string $title The tile of the form
     * @param boolean $AssignAccess
     * @param array $errors List of erros
     * @param array $rows The identity info
     * @param array $accounts The list of accounts
     */
    public function print_assignAccount($title,$AssignAccess,$errors,$rows,$accounts) {
        echo "<H2>".htmlentities($title)."</H2>";
        if ($AssignAccess){
            if ( $errors ) {
                print '<ul class="list-group">';
                foreach ( $errors as $field => $error ) {
                    print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
                }
                print '</ul>';
            }
            
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
            echo "<p></p>";
            echo "<form role=\"form\" class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label for=\"account\">Account <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"account\" id=\"account\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["account"])){
                foreach ($accounts as $account){
                    echo "<option value=\"".$account["Acc_ID"]."\">".$account["UserID"]." ".$account["Application"]."</option>";
                }
            }  else {
                foreach ($accounts as $account){
                    if ($_POST["account"] == $account["Acc_ID"]){
                        echo "<option value=\"".$account["Acc_ID"]."\" selected>".$account["UserID"]." ".$account["Application"]."</option>";
                    }else{
                        echo "<option value=\"".$account["Acc_ID"]."\">".$account["UserID"]." ".$account["Application"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group has-feedback\">";
            echo "<label class=\"control-label\">From <span style=\"color:red;\">*</span></label>";
            echo "<input type=\"text\" class=\"form-control\" placeholder=\"DD/MM/YYYY\" name=\"start\" id=\"start-date\"/>";
            echo "<i class=\"glyphicon glyphicon-calendar form-control-feedback\"></i>";
            echo "</div>";
            echo "<div class=\"form-group has-feedback\">";
            echo "<label class=\"control-label\">Until</label>";
            echo "<input type=\"text\" class=\"form-control\" placeholder=\"DD/MM/YYYY\" name=\"end\" id=\"end-date\"/>";
            echo "<i class=\"glyphicon glyphicon-calendar form-control-feedback\"></i>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Assign</button>";
            echo "<a class=\"btn\" href=\"identity.php\">Back</a>";
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
    
}
?>