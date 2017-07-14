<h2>Admin</h2>
<?php
echo "<div class=\"container\">";
echo "<div class=\"row\">";
if ($AddAccess){
    echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Admin.php?op=new\">";
    echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    echo "</div>";
}
echo "<div class=\"col-md-6 text-right\">";
?>
<form class="form-inline" role="search" action="Admin.php?op=search" method="post">
    <div class="form-group">
       <input name="search" type="text" class="form-control" placeholder="Search">
    </div>
       <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
</form>
<?php
echo "</div>";
echo "</div>";
if (count($rows)>0){
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th><a href=\"Admin.php?orderby=Account\">Account</a></th>";
    echo "<th><a href=\"Admin.php?orderby=Level\">Level</a></th>";
    echo "<th><a href=\"Admin.php?orderby=Active\">Active</a></th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['Account'])."</a></td>";
        echo "<td>".htmlentities($row['Level'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "<td>";
        if ($row["Admin_id"]>1){
            if ($UpdateAccess){
                echo "<a class=\"btn btn-primary\" href=\"Admin.php?op=edit&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
                echo "<span class=\"fa fa-pencil\"></span></a>";
            }
            if ($row["Active"] == "Active" and $DeleteAccess){
                echo "<a class=\"btn btn-danger\" href=\"Admin.php?op=delete&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
                echo "<span class=\"fa fa-toggle-off\"></span></a>";
            }elseif ($ActiveAccess){
                echo "<a class=\"btn btn-glyphicon\" href=\"Admin.php?op=activate&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
                echo "<span class=\"fa fa-toggle-on\"></span></a>";
            }
        }
        if ($InfoAccess) {
            echo "<a class=\"btn btn-info\" href=\"Admin.php?op=show&id=".$row['Admin_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
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
?>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>