<?php
print "<h2>".htmlentities($title)."</h2>";
if ($AddAccess){
    if ( $errors ) {
        print '<ul class="list-group">';
        foreach ( $errors as $field => $error ) {
            print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
        }
        print '</ul>';
    }
?>
	<form class="form-horizontal" action="" method="post">
        <div class="form-group">
            <label class="control-label">IMEI <span style="color:red;">*</span></label>
            <input name="IMEI" type="text" class="form-control" placeholder="Please insert a IMEI" value="<?php echo $IMEI;?>">
        </div>
        <div class="form-group ">
            <label class="control-label">Type <span style="color:red;">*</span></label>
            <select name="Type" class="form-control">
            <?php echo "<option value=\"\"></option>";
                if (empty($_POST["Type"])){
                    foreach ($typerows as $type){
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }  else {
                    foreach ($typerows as $type){
                        if ($_POST["Type"] == $type["Type_ID"]){
                            echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                        }else{
                            echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Create</button>
            <?php echo "<a class=\"btn\" href=\"Mobile.php\">Back</a>"; ?>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}