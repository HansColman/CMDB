<?php
echo "<H2>".htmlentities($title)."</H2>";
if ( $errors ) {
    print '<ul class="list-group">';
    foreach ( $errors as $field => $error ) {
        print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
    }
    print '</ul>';
}
if($ReleaseAccountAccess){
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
    if (!empty($accounts)){
        echo "<H3>Account information that will be released</H3>";
        echo "<table class=\"table table-striped table-bordered\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>UserID</th>";
        echo "<th>Application</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($accounts as $Account){
            echo "<tr>";
            echo "<td class=\"small\">".htmlentities($Account["UserID"])."</td>";
            echo "<td class=\"small\">".htmlentities($Account["Application"])."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }
    ?>
    <form class="form-horizontal" action="" method="post">
        <div class="form-group">
            <label class="control-label" for="Employee">Employee that will sign</label>
            <input name="Employee" type="text" id="Employee" class="form-control" placeholder="Please insert name of person" value="<?php echo $Name;?>">
        </div>
        <div class="form-group">
            <label class="control-label" for="ITEmp">IT Employee that will sign</label>
            <input name="ITEmp" type="text" id="ITEmp" class="form-control"  placeholder="Please insert reason" value="<?php echo $AdminName;?>">
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
        <button type="submit" class="btn btn-success">Create PDF</button>
        <?php if($_SESSION["Class"] == "Account"){
            echo "<a class=\"btn\" href=\"Account.php\">Back</a>";
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
}else {
    $this->showError("Application error", "You do not access to this page");
}