<?php
require_once ('view/view.php');

class TypeView extends \View
{
    /**
     * The Type op Type
     * @var string
     */
    private $type;
    /**
     * The URI to go back
     * @var string
     */
    private $backUrl;
    private $newUrl;
    private $searchUrl;
    
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will set the Type
     * @param string $type
     */
    public function setType($type){
        $this->type = $type;
        switch ($type) {
            case "Identity":
                $this->backUrl = "identitytype.php";
                break;
            case "Account":
                $this->backUrl ="accounttype.php";
                break;
            case "Role":
                $this->backUrl ="RoleType.php";
                break;
        }
        $this->newUrl = $this->backUrl."?op=new";
        $this->searchUrl = $this->backUrl."?op=search";
    }
    /**
     * This function will print the delte form
     * @param string $title
     * @param array $errors
     * @param array $rows
     * @param string $Reason
     */
    public function print_deleteForm($title,$errors,$rows,$Reason,$DeleteAccess) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($DeleteAccess){
            $this->print_ValistationErrors($errors);
            $this->print_table();
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
            $this->deleteform($Reason,$this->backUrl);
        }else {
            $this->print_error("Application error", "You do not access to this page");
        }
    }
    /**
     * This function will print the create form
     * @param string $title
     * @param array $errors
     * @param string $Type
     * @param string $Description
     * @param boolean $AddAccess
     */
    public function print_CreateForm($title,$errors,$Type,$Description,$AddAccess) {
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
            print "<input name=\"Description\" type=\"text\" class=\"form-control\" placeholder=\"Please enter description\" value=\"".$Description."\">";
            print "</div> ";
            print "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
            print "<div class=\"form-actions\">";
            print "<button type=\"submit\" class=\"btn btn-success\">Create</button>";
            print "<a class=\"btn\" href=\"".$this->backUrl."\">".self::$BackIcon." Back</a>";
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
            echo "<a class=\"btn\" href=\"".$this->backUrl."\">".self::$BackIcon." Back</a>";
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
        echo "<h2>".$this->type." Types</h2>";
        echo "<div class=\"row\">";
        $this->print_addOnTop($AddAccess, $this->newUrl);
        $this->SearchForm($this->searchUrl);
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
            echo "<tr>";
            echo "<th><a href=\"".$this->backUrl."?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"".$this->backUrl."?orderby=Description\">Description</a></th>";
            echo "<th><a href=\"".$this->backUrl."?orderby=Active\">Active</a></th>";
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
                echo "<a class=\"btn btn-primary\" href=\"".$this->backUrl."?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"".$this->backUrl."?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"".$this->backUrl."?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"".$this->backUrl."?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * @param string $search
     */
    public function print_searched($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$search) {
        echo "<h2>".$this->type." Types</h2>";
        echo "<div class=\"row\">";
        $this->print_addOnTop($AddAccess, $this->newUrl);
        $this->SearchForm($this->searchUrl);
        echo "</div>";
        if (count($rows)>0){
            $this->print_table();
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
                echo "<a class=\"btn btn-primary\" href=\"".$this->backUrl."?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo self::$EditIcon."</a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"".$this->backUrl."?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo self::$DeactivateIcon."</a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"".$this->backUrl."?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo self::$ActivateIcon."</a>";
            }
            if ($InfoAccess) {
                echo "<a class=\"btn btn-info\" href=\"".$this->backUrl."?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This function will ptint the detail
     * @param bool $ViewAccess
     * @param bool $AddAccess
     * @param array $rows
     * @param array $logrows
     * @param string $LogDateFormat
     */
    public function print_overview($ViewAccess,$AddAccess,$rows,$logrows,$LogDateFormat) {
        echo "<h2>".$this->type." Types";
        echo " <a href=\"".$this->backUrl."\" class=\"btn btn-default float-right\"><i class=\"fa fa-arrow-left\"></i> Back</a></h2>";
        if ($ViewAccess){
            echo "<p></p>";
            $this->print_table();
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
            $this->print_addBelow($AddAccess, $this->newUrl);
            $this->print_loglines($logrows, $LogDateFormat, $this->type);
        }
    }
}