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
    <div class="form-group ">
            <label class="control-label">Category <span style="color:red;">*</span></label>
            <select name="Category" class="form-control">
            <?php echo "<option value=\"\"></option>";
                if (empty($_POST["Category"])){
                    foreach ($catrows as $type){
                        echo "<option value=\"".$type["ID"]."\">".$type["Category"]."</option>";
                    }
                }  else {
                    foreach ($catrows as $type){
                        if ($_POST["Category"] == $type["ID"]){
                            echo "<option value=\"".$type["ID"]."\" selected>".$type["Category"]."</option>";
                        }else{
                            echo "<option value=\"".$type["ID"]."\">".$type["Category"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
        <div class="form-group ">
            <label class="control-label">Vendor <span style="color:red;">*</span></label>
            <input name="Vendor" type="text" class="form-control" placeholder="Please enter a Vendor" value="<?php echo $Vendor;?>">
        </div>
        <div class="form-group ">
          <label class="control-label">Type <span style="color:red;">*</span></label>
          <input name="Type" type="text" class="form-control" placeholder="Please enter a Type" value="<?php echo $Type;?>">
        </div> 
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn" href="AssetType.php">Back</a>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}