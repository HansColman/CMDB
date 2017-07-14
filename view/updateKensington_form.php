<?php
print "<h2>".htmlentities($title)."</h2>";
if ($UpdateAccess){
    if ( $errors ) {
        print '<ul class="list-group">';
        foreach ( $errors as $field => $error ) {
            print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
        }
        print '</ul>';
    }
    ?>
    <form class="form-horizontal" action="" method="post">
        <div class="form-group ">
            <label class="control-label">Type <span style="color:red;">*</span></label>
            <select name="Type" class="form-control">
            <?php echo "<option value=\"\"></option>";
                if (empty($Type)){
                    foreach ($types as $type){
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }  else {
                    foreach ($types as $type){
                        if ($Type == $type["Type_ID"]){
                            echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Vendor"]." ".$type["Type"]."</option>";
                        }else{
                            echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Serial Number <span style="color:red;">*</span></label>
            <input name="SerialNumber" type="text" class="form-control" placeholder="Please enter a SerialNumber" value="<?php echo $Serial;?>">
        </div>
        <div class="form-group">
            <label class="control-label">Amount of keys <span style="color:red;">*</span></label>
            <input name="Keys" type="text" class="form-control" placeholder="Please enter a amount" value="<?php echo $NrKeys;?>">
        </div>
        <div class="row"><label class="">Has lock <span style="color:red;">*</span></label></div>
        <div class="form-group">
            <?php if ($hasLock == "No"){ ?>
            <label class="radio-inline"><input type="radio" name="Lock" value="Yes">Yes</label>
            <label class="radio-inline"><input type="radio" checked name="Lock" value="No">No</label>
            <?php }elseif ($hasLock == "Yes"){ ?>
            <label class="radio-inline"><input type="radio" checked name="Lock" value="Yes">Yes</label>
            <label class="radio-inline"><input type="radio" name="Lock" value="No">No</label>
            <?php }else { ?>
            <label class="radio-inline"><input type="radio" name="Lock" value="Yes">Yes</label>
            <label class="radio-inline"><input type="radio" name="Lock" value="No">No</label>
            <?php } ?>
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Update</button>
            <?php echo "<a class=\"btn\" href=\"Kensington.php\">Back</a>"; ?>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php  
}  else {
    $this->showError("Application error", "You do not access to this page");
}