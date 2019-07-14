<?php
require_once ('view/view.php');

class AssetTypeView extends View
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will print the overview
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_Overview($ViewAccess,$AddAccess,$rows,$logrows,$LogDateFormat) {
        echo "<h2>Asset Type Details</h2>";
        if ($ViewAccess){
            if ($AddAccess){
                echo "<a class=\"btn icon-btn btn-success\" href=\"AssetType.php?op=new\">";
                echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            }
            echo "<a href=\"AssetType.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
            echo "<p></p>";
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Category</th>";
            echo "<th>Vendor</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row){
                echo "<tr>";
                echo "<td>".htmlentities($row['Category'])."</td>";
                echo "<td>".htmlentities($row['Vendor'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['Active'])."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            $this->print_loglines($logrows, $LogDateFormat, "Asset Type");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the List All
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     */
    public function print_ListAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess){
        echo "<h2>Asset Types</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"AssetType.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("AssetType.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th><a href=\"AssetType.php?orderby=Category\">Category</th>";
            echo "<th><a href=\"AssetType.php?orderby=Vendor\">Vendor</a></th>";
            echo "<th><a href=\"AssetType.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"AssetType.php?orderby=Active\">Active</a></th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Category'])."</a></td>";
            echo "<td>".htmlentities($row['Vendor'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"AssetType.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo "<span class=\"fa fa-pencil\"></span></a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"AssetType.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo "<span class=\"fa fa-toggle-off\"></span></a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"AssetType.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo "<span class=\"fa fa-toggle-on\"></span></a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"AssetType.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This will print the Delete Form
     * @param string $title
     * @param array $errors
     * @param bool $DeleteAccess
     * @param array $rows
     * @param string $reason
     */
    public function print_DeleteForm($title,$errors,$DeleteAccess,$rows,$reason) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($DeleteAccess){
            $this->print_ValistationErrors($errors);
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Category</th>";
            echo "<th>Vendor</th>";
            echo "<th>Type</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row) {
                echo "<td>".htmlentities($row["Category"])."</td>";
                echo "<td>".htmlentities($row["Vendor"])."</td>";
                echo "<td>".htmlentities($row["Type"])."</td>";
            }
            echo "<tr>";
            echo "</tr>";
        	echo "</tbody>";
    		echo "</table>";
    		$this->deleteform($reason, "AssetType.php");
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the Create Form
     * @param string $title
     * @param array $errors
     * @param bool $AddAccess
     * @param string $Vendor
     * @param string $Type
     * @param array $catrows
     */
    public function print_CreateForm($title,$errors,$AddAccess,$Vendor,$Type,$catrows) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($AddAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Category <span style=\"color:red;\">*</span></label>";
            print "<select name=\"Category\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($_POST["Category"])){
                foreach ($catrows as $type){
                    echo "<option value=\"".$type["ID"]."\">".$type["Category"]."</option>";
                }
            }  else {
                foreach ($catrows as $type){
                    if ($_POST["Category"] == $type["ID"]){
                        echo "<option value=\"".$type["ID"]."\" selected>".$type["Category"]."</option>";
                    }else{
                        echo "<option value=\"".$type["ID"]."\">".$type["Category"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Vendor <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Vendor\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a Vendor\" value=\"".$Vendor."\">";
        	echo "</div>";
        	echo "<div class=\"form-group\">";
          	echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
          	echo "<input name=\"Type\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a Type\" value=\"".$Type."\">";
        	echo "</div>";
        	echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
        	echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            echo "<a class=\"btn\" href=\"AssetType.php\">Back</a>";
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
     * This function will print the List All
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $InfoAccess
     */
    public function print_ListSearched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$search){
        echo "<h2>Asset Types</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"AssetType.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("AssetType.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Category</th>";
            echo "<th>Vendor</th>";
            echo "<th>Type</th>";
            echo "<th>Active</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($rows as $row):
            echo "<tr>";
            echo "<td>".htmlentities($row['Category'])."</td>";
            echo "<td>".htmlentities($row['Vendor'])."</td>";
            echo "<td>".htmlentities($row['Type'])."</td>";
            echo "<td>".htmlentities($row['Active'])."</td>";
            echo "<td>";
            IF ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"AssetType.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo "<span class=\"fa fa-pencil\"></span></a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"AssetType.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo "<span class=\"fa fa-toggle-off\"></span></a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"AssetType.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo "<span class=\"fa fa-toggle-on\"></span></a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"AssetType.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
    /**
     * This function will print the Create Form
     * @param string $title
     * @param array $errors
     * @param bool $UpdateAccess
     * @param string $Vendor
     * @param string $Type
     * @param int $Category
     * @param array $catrows
     */
    public function print_UpdateForm($title,$errors,$UpdateAccess,$Vendor,$Type,$Category,$catrows) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
            $this->print_ValistationErrors($errors);
            print "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
            print "<div class=\"form-group\">";
            print "<label class=\"control-label\">Category <span style=\"color:red;\">*</span></label>";
            print "<select name=\"Category\" class=\"form-control\">";
            echo "<option value=\"\"></option>";
            if (empty($Category)){
                foreach ($catrows as $type){
                    echo "<option value=\"".$type["ID"]."\">".$type["Category"]."</option>";
                }
            }  else {
                foreach ($catrows as $type){
                    if ($Category == $type["ID"]){
                        echo "<option value=\"".$type["ID"]."\" selected>".$type["Category"]."</option>";
                    }else{
                        echo "<option value=\"".$type["ID"]."\">".$type["Category"]."</option>";
                    }
                }
            }
            echo "</select>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Vendor <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Vendor\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a Vendor\" value=\"".$Vendor."\">";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<label class=\"control-label\">Type <span style=\"color:red;\">*</span></label>";
            echo "<input name=\"Type\" type=\"text\" class=\"form-control\" placeholder=\"Please enter a Type\" value=\"".$Type."\">";
            echo "</div>";
            echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            echo "<div class=\"form-actions\">";
            echo "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            echo "<a class=\"btn\" href=\"AssetType.php\">Back</a>";
            echo "</div>";
            echo "<div class=\"form-group\">";
            echo "<span class=\"text-muted\"><em><span style=\"color:red;\">*</span> Indicates required field</em></span>";
            echo "</div>";
            echo "</form>";
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
}

