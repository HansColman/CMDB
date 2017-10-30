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
    <form action="" method="post">
        <div class="form-group">
            <label class="control-label">AssetTag <span style="color:red;">*</span></label>
            <input name="AssetTag" type="text" class="form-control" placeholder="Please insert a AssetTag" value="<?php echo $AssetTag;?>" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Serial Number <span style="color:red;">*</span></label>
            <input name="SerialNumber" type="text" class="form-control" placeholder="Please enter a SerialNumber" value="<?php echo $SerialNumber;?>">
        </div>
        <div class="form-group ">
            <label class="control-label">Type <span style="color:red;">*</span></label>
            <select name="Type" class="form-control">
            <?php echo "<option value=\"\"></option>";
                if (empty($Type)){
                    foreach ($typerows as $type){
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Vendor"]." ".$type["Type"]."</option>";
                    }
                }  else {
                    foreach ($typerows as $type){
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
            <label class="control-label">Name </label>
            <input name="Name" type="text" class="form-control" placeholder="Please enter a name" value="<?php echo $Name;?>">
        </div>
        <div class="form-group">
            <label class="control-label">MAC Address </label>
            <input name="MAC" type="text" class="form-control" placeholder="Please enter a MAC Address" value="<?php echo $MAC;?>">
        </div>
        <div class="form-group">
            <label class="control-label">IP Address </label>
            <input name="IP" type="text" class="form-control" placeholder="Please enter a IP Address" value="<?php echo $IP;?>">
        </div>
        <?php if ($this->Category == "Laptop" or $this->Category == "Desktop"){ ?>
        <div class="form-group">
            <label class="control-label">RAM <span style="color:red;">*</span></label>
            <select name="RAM" class="form-control">
            <?php echo "<option value=\"\"></option>";
                if (empty($RAM)){
                    foreach ($Ramrows as $ram){
                        echo "<option value=\"".$ram["Text"]."\">".$ram["Text"]."</option>";
                    }
                }  else {
                    foreach ($Ramrows as $ram){
                        if ($RAM == $ram["Text"]){
                            echo "<option value=\"".$ram["Text"]."\" selected>".$ram["Text"]."</option>";
                        }else{
                            echo "<option value=\"".$ram["Text"]."\">".$ram["Text"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
        <?php }?>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <input type="hidden" name="AssetTag" value="<?php echo $AssetTag;?>" />
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Update</button>
            <?php echo "<a class=\"btn\" href=\"Devices.php?Category=".$this->Category."\">Back</a>"; ?>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}