<?php
require_once 'view/view.php';

class AccountTypeView extends View
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This function will print the overview form
     * @param boolean $AddAccess
     * @param array $rows
     * @param boolean $UpdateAccess
     * @param boolean $DeleteAccess
     * @param boolean $ActiveAccess
     * @param boolean $InfoAccess
    */
    public function print_overview($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess){
        echo "<h2>Account Types</h2>";
        echo "<div class=\"container\">";
        echo "<div class=\"row\">";
        if ($AddAccess){
            echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"accounttype.php?op=new\">";
            echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
            echo "</div>";
        }
        $this->SearchForm("accounttype.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th><a href=\"accounttype.php?orderby=Type\">Type</a></th>";
            echo "<th><a href=\"accounttype.php?orderby=Description\">Description</a></th>";
            echo "<th><a href=\"accounttype.php?orderby=Active\">Active</a></th>";
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
                    echo "<a class=\"btn btn-primary\" href=\"accounttype.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                    echo "<span class=\"fa fa-pencil\"></span></a>";
                }
                if ($row["Active"] == "Active" and $DeleteAccess){
                    echo "<a class=\"btn btn-danger\" href=\"accounttype.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                    echo "<span class=\"fa fa-toggle-off\"></span></a>";
                }elseif ($ActiveAccess){
                    echo "<a class=\"btn btn-glyphicon\" href=\"accounttype.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                    echo "<span class=\"fa fa-toggle-on\"></span></a>";
                }
                if ($InfoAccess) {
                    echo "<a class=\"btn btn-info\" href=\"accounttype.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
     * This funcition will print the details of a AccountType
     * @param boolean $ViewAccess
     * @param boolean $AddAccess
     * @param array $rows
     * @param array $logrows
     * @param string $LogDateFormat
    */
    public function print_detail($ViewAccess,$AddAccess,$rows,$logrows,$LogDateFormat) {
         echo"<h2>Account Type Details</h2>";
         if ($ViewAccess){
             if ($AddAccess){
                 echo "<a class=\"btn icon-btn btn-success\" href=\"AccountType.php?op=new\">";
                 echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
             }
             echo " <a href=\"AccountType.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
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
                 echo "No Log entries found for this AccountType";
             }
         }else {
             $this->print_error("Application error", "You do not access to this page");
         }
     }
     /**
      * This function will print the Delete Form
      * @param string $title
      * @param boolean $errors
      * @param array $rows
      * @param string $Reason
      * @param boolean $DeleteAccess
    */
    public function print_deleteForm($title,$errors,$rows,$Reason,$DeleteAccess) {
         print "<h2>".htmlentities($title)."</h2>";
         if ($DeleteAccess){
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
             $this->deleteform($Reason,"AccountType.php");
         }  else {
             $this->print_error("Application error", "You do not access to this page");
         }
     }
    /**
     * This function will print the update form
     * @param string $title
     * @param array $errors
     * @param string $Type
     * @param string $Description
     * @param $UpdateAccess
     */
    public function print_updateForm($title,$errors,$Type,$Description,$UpdateAccess) {
        print "<h2>".htmlentities($title)."</h2>";
        if ($UpdateAccess){
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
            print "<button type=\"submit\" class=\"btn btn-success\">Update</button>";
            print "<a class=\"btn\" href=\"AccountType.php\">Back</a>";
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
             print "<a class=\"btn\" href=\"AccountType.php\">Back</a>";
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
      * This function will print the searched overview
      * @param boolean $AddAccess
      * @param array $rows
      * @param boolean $UpdateAccess
      * @param boolean $DeleteAccess
      * @param boolean $ActiveAccess
      * @param boolean $InfoAccess
      * @param string $search
      */
     public function print_serached($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$InfoAccess,$search) {
         echo "<h2>Account Types</h2>";
         echo "<div class=\"container\">";
         echo "<div class=\"row\">";
         if ($AddAccess){
             echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"accounttype.php?op=new\">";
             echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
             echo "</div>";
         }
         $this->SearchForm("accounttype.php?op=search");
         echo "</div>";
         if (count($rows)>0){
             echo "<table class=\"table table-striped table-bordered\">";
             echo "<thead>";
             echo "<tr>";
             echo "<th><a href=\"accounttype.php?orderby=Type\">Type</a></th>";
             echo "<th><a href=\"accounttype.php?orderby=Description\">Description</a></th>";
             echo "<th><a href=\"accounttype.php?orderby=Active\">Active</a></th>";
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
                 echo "<a class=\"btn btn-primary\" href=\"accounttype.php?op=edit&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                 echo "<span class=\"fa fa-pencil\"></span></a>";
             }
             if ($row["Active"] == "Active" and $DeleteAccess){
                 echo "<a class=\"btn btn-danger\" href=\"accounttype.php?op=delete&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                 echo "<span class=\"fa fa-toggle-off\"></span></a>";
             }elseif ($ActiveAccess){
                 echo "<a class=\"btn btn-glyphicon\" href=\"accounttype.php?op=activate&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                 echo "<span class=\"fa fa-toggle-on\"></span></a>";
             }
             if ($InfoAccess) {
                 echo "<a class=\"btn btn-info\" href=\"accounttype.php?op=show&id=".$row['Type_ID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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

