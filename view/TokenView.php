<?php
require_once 'view/view.php';

class TokenView extends View
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will print the Delete Form
     * @param string $title
     * @param array $errors
     * @param array $rows
     * @param string $reason
     */
    public function print_DeleteForm($title,$errors,$rows,$reason) {
        print "<h2>".htmlentities($title)."</h2>";
        $this->print_ValistationErrors($errors);
        $this->print_table();
        echo "<tr>";
        echo "<th>AssetTag</th>";
        echo "<th>Serialnumber</th>";
        echo "<th>Type</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['AssetTag'])."</td>";
            echo "<td>".htmlentities($row['SerialNumber'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "</tr>";
        endforeach;
        echo "</tbody>";
        echo "</table>";
        $this->deleteform($reason, "Token.php");
    }
    /**
     * This function will print the Create Form
     * @param string $title
     * @param array $errors
     * @param string $AssetTag
     * @param string $SerialNumber
     * @param array $typerows
     * @param bool $AddAccess
     */
    public function print_CreateForm($title,$errors,$AssetTag,$SerialNumber,$typerows,$AddAccess) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">AssetTag <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"AssetTag\" type=\"text\" class=\"form-control\" placeholder=\"Please insert a AssetTag\" value=\"".$AssetTag."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Serial Number <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"SerialNumber\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a SerialNumber\" value=\"".$SerialNumber."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Type\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Type"])){
                foreach ($typerows as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                }
            }  else {
                foreach ($typerows as $type){
                    if ($_POST["Type"] == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"Token.php\">".self::$BackIcon." Back</a>";
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
     * This function will print the Create Form
     * @param string $title
     * @param array $errors
     * @param string $AssetTag
     * @param string $SerialNumber
     * @param int $Type
     * @param array $typerows
     * @param bool $UpdateAccess
     */
    public function print_UpdateForm($title,$errors,$AssetTag,$SerialNumber,$Type,$typerows,$UpdateAccess) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">AssetTag <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"AssetTag\" type=\"text\" class=\"form-control\" placeholder=\"Please insert a AssetTag\" value=\"".$AssetTag."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Serial Number <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"SerialNumber\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a SerialNumber\" value=\"".$SerialNumber."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<select name=\"Type\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Type)){
                foreach ($typerows as $type){
                    echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                }
            }  else {
                foreach ($typerows as $type){
                    if ($Type == $type["Type_ID"]){
                        echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                    }else{
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"Token.php\">".self::$BackIcon." Back</a>";
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
     * This function will print All Tokens
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     */
    public function print_ListAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess) {
        echo "<h2>Tokens</h2>";
        echo "<div class=\"row\">";
        $Url = "Token.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Token.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"Token.php?orderby=AssetTag\">AssetTag</a></th>";
            echo "<th><a href=\"Token.php?orderby=SerialNumber\">Serialnumber</a></th>";
            echo "<th><a href=\"Token.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"Token.php?orderby=ussage\">ussage</a></th>";
            echo "<th><a href=\"Token.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['AssetTag'])."</td>";
            echo "<td>".htmlentities($row['SerialNumber'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['ussage'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Token.php?op=edit&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Token.php?op=delete&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Token.php?op=activate&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Token.php?op=show&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will print the searched
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     */
    public function print_Searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$search) {
        echo "<h2>Tokens</h2>";
        echo "<div class=\"row\">";
        $Url = "Token.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Token.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th>AssetTag</th>";
            echo "<th>Serialnumber</th>";
            echo "<th>Type</th>";
            echo "<th>ussage</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['AssetTag'])."</td>";
            echo "<td>".htmlentities($row['SerialNumber'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['ussage'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Token.php?op=edit&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Token.php?op=delete&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Token.php?op=activate&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"Token.php?op=show&id=".$row['AssetTag']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will print the overview
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $IdenViewAccess
     * @param bool $ReleaseIdenAccess
     * @param array $idenrows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_Overview($ViewAccess,$AddAccess,$rows,$IdenViewAccess,$ReleaseIdenAccess,$idenrows,$logrows,$LogDateFormat) {
        echo "<h2>Token details";
        echo " <a href=\"Token.php\" class=\"btn btn-default float-right\">".self::$BackIcon." Back</a></h2>";
        if ($ViewAccess){
            echo "<p></p>";
            $this->print_table();
            echo "<tr>";
            echo "<th>AssetTag</th>";
            echo "<th>SerialNumber</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $UUID = "";
            foreach ($rows as $row):
                $UUID = $row['AssetTag'];
                echo "<tr>";
                echo "<td>".htmlentities($row['AssetTag'])."</td>";
                echo "<td>".htmlentities($row['SerialNumber'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            endforeach;
            echo "</tbody>";
            echo "</table>";
            $Url = "Token.php?op=new";
            $this->print_addBelow($AddAccess, $Url);
            if ($IdenViewAccess){
                //Identity Overview
                $this->print_IdentityInfo($idenrows,"Token",$ReleaseIdenAccess,"Token.php",$UUID);
            }
            //LogOverview
            $this->print_loglines($logrows, $LogDateFormat, "Token");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
}