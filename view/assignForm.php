<?php
print "<h2>".htmlentities($title)."</h2>";
if ($AssignAccess){
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
    echo "<h3>Device Info</h3>";
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>AssetTag</th>";
    echo "<th>SerialNumber</th>";
    echo "<th>Type</th>";
    echo "<th>Active</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
    echo "<tr>";
    echo "<td>".htmlentities($row['AssetTag'])."</td>";
    echo "<td>".htmlentities($row['SerialNumber'])."</td>";
    echo "<td>".htmlentities($row['Type'])."</td>";
    echo "<td>".htmlentities($row['Active'])."</td>";
    echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
    echo "<h3>Sing info</h3>";
    ?>
    <form class="form-horizontal" action="" method="post">
    <div class="form-group">
        <label class="control-label" for="reason">Employee</label>
        <input name="reason" type="text" id="reason" class="form-control" placeholder="Please insert name of person" value="<?php echo $Name;?>">
    </div>
    <div class="form-group">
        <label class="control-label" for="reason">IT Employee</label>
        <input name="reason" type="text" id="reason" class="form-control"  placeholder="Please insert reason" value="<?php echo $AdminName;?>">
    </div>
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Create PDF</button>
        <?php echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">Back</a>"; ?>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
	</form>
    <?php
}else {
    $this->showError("Application error", "You do not access to this page");
}