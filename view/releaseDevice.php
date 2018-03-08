<?php
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
    ?>
    <form class="form-horizontal" action="" method="post">
	<div class="form-group">
		<div class="form-check form-check-inline">
		<?php
		$amount = 1;
		foreach($devicerows as $device) :
            echo "<label class=\"checkbox-inline\">";
            echo "<input type=\"checkbox\" name=\"".$device["Category"].$amount."\" value=\"".$device["AssetTag"]."\">".$device["AssetTag"]." ".$device["Type"];
            echo "</label>";
            $amount ++;
		endforeach;
		?>
		</div>
    </div>
    <?php 
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
    ?>
        <div class="form-group">
            <label class="control-label" for="Employee">Employee</label>
            <input name="Employee" type="text" id="Employee" class="form-control" placeholder="Please insert name of person" value="<?php echo $Name;?>">
        </div>
        <div class="form-group">
            <label class="control-label" for="ITEmp">IT Employee</label>
            <input name="ITEmp" type="text" id="ITEmp" class="form-control"  placeholder="Please insert reason" value="<?php echo $AdminName;?>">
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
        <button type="submit" class="btn btn-success">Create PDF</button>
        <?php if($_SESSION["Class"] == "Device"){
            echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">Back</a>";
        }else{
            echo "<a class=\"btn\" href=\"Identity.php\">Back</a>";
        }
        ?>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
	</form>
    <?php
} else {
    $this->showError("Application error", "You do not access to this page");
}