<h2>Kensington details</h2>
<?php 
if ($ViewAccess){
    if ($AddAccess){
        echo "<a class=\"btn icon-btn btn-success\" href=\"Kensington.php?op=new\">";
        echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
    }
    echo " <a href=\"Kensington.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
    echo "<p></p>";
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Type</th>";
    echo "<th>Serialnumber</th>";
    echo "<th># Keys</th>";
    echo "<th>Lock</th>";
    echo "<th>Active</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Serial'])."</td>";
        echo "<td>".htmlentities($row['AmountKeys'])."</td>";
        echo "<td>".htmlentities($row['hasLock'] == 1 ? "Yes" : "No")."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
    if ($IdenViewAccess){
        //Identity Overview
        echo "<H3>Assigned device</H3>";
        if (!empty($idenrows)){
            echo "<table class=\"table table-striped table-bordered\">";
            echo "<thead>";
            echo "<tr>";
            echo "<th>AssetTag</th>";
            echo "<th>Category</th>";
            echo "<th>Type</th>";
            echo "<th>SerialNumber</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($idenrows as $row):
                echo "<tr>";
                echo "<td>".htmlentities($row['AssetTag'])."</td>";
                echo "<td>".htmlentities($row['Category'])."</td>";
                echo "<td>".htmlentities($row['Type'])."</td>";
                echo "<td>".htmlentities($row['SerialNumber'])."</td>";
                echo "</tr>"; 
            endforeach;
            echo "</tbody>";
            echo "</table>";
        }  else {
            echo "No device assigned to this token";
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
