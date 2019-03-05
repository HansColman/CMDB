<h2>Mobile details</h2>
<?php 
if ($ViewAccess){
    if ($AddAccess){
        echo "<a class=\"btn icon-btn btn-success\" href=\"Mobile.php?op=new\">";
        echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    }
    echo " <a href=\"Mobile.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
    echo "<p></p>";
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>IMEI</th>";
    echo "<th>Type</th>";
    echo "<th>Active</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['IMEI'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
    //Identity Overview
    if($IdenOverAccess){
        echo "<H3>Identity overview</H3>";
        if(!empty(($idenrows))){
            echo "<table class=\"table table-striped table-bordered\">";
        }else{
            echo "No Identity assigned to this Mobile";
        }
    }
    if($AssignIdenAccess){
        echo "<a class=\"btn btn-success\" href=\"Mobile.php?op=assign&id=".$row['IMEI']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Assign Identity\">";
        echo "<span class=\"fa fa-user-plus\"></span></a>";
    }
    // Supscription overview
    if($SubOverAccess){
        echo "<H3>Subsription overview</H3>";
        if(!empty($subrows)){
            echo "<table class=\"table table-striped table-bordered\">";
        }else {
            echo "No Supscriptions assigned to this Mobile";
        }
    }
    
    //LogOverview
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
            echo "<td class=\"small\">".htmlentities(date($this->getLogDateFormat(), strtotime($log["Log_Date"])))."</td>";
            echo "<td class=\"small\">".htmlentities($log["Log_Text"])."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }  else {
        echo "No Log entries found for this Token";
    }
}else {
    $this->showError("Application error", "You do not access to this page");
}