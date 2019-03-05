<h2>Mobiles</h2>
<?php
echo "<div class=\"container\">";
echo "<div class=\"row\">";
if ($AddAccess){
    echo "<div class=\"col-md-6 text-left\"><a class=\"btn icon-btn btn-success\" href=\"Mobile.php?op=new\">";
    echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    echo "</div>";
}
echo "<div class=\"col-md-6 text-right\">";
?>
<form class="form-inline" role="search" action="Mobile.php?op=search" method="post">
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
    echo "<th><a href=\"Mobile.php?orderby=IMEI\">IMEI</a></th>";
    echo "<th><a href=\"Mobile.php?orderby=Type\">Type</a></th>";
    echo "<th><a href=\"Mobile.php?orderby=ussage\">Ussage</a></th>";
    echo "<th><a href=\"Mobile.php?orderby=Active\">Active</a></th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['IMEI'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['ussage'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "<td>";
        IF ($AddAccess){
            echo "<a class=\"btn btn-primary\" href=\"Mobile.php?op=edit&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\">";
            echo "<span class=\"fa fa-pencil\"></span></a>";
        }
        if ($row["Active"] == "Active" and $DeleteAccess){
            echo "<a class=\"btn btn-danger\" href=\"Mobile.php?op=delete&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\">";
            echo "<span class=\"fa fa-toggle-off\"></span></a>";
        }elseif ($ActiveAccess){
            echo "<a class=\"btn btn-glyphicon\" href=\"Mobile.php?op=activate&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Activate\">";
            echo "<span class=\"fa fa-toggle-on\"></span></a>";
        }
        if ($row["Active"] == "Active" and $AssignIdenAccess){
            echo "<a class=\"btn btn-success\" href=\"Mobile.php?op=assign&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
            echo "<span class=\"fa fa-user-plus\"></span></a>";
        }
        if ($InfoAccess) {
            echo "<a class=\"btn btn-info\" href=\"Mobile.php?op=show&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Info\">";
            echo "<span class=\"fa fa-info\"></span></a>";
        }
        echo "</td>"; 
        echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
}else {
    echo "<div class=\"alert alert-danger\">No rows found, please add a new record</div>";
}
?>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>