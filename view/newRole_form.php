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
            <label class="control-label">Name <span style="color:red;">*</span></label>
            <input name="Name" type="text" class="form-control" placeholder="Pleae insert Name" value="<?php echo $Name;?>">
        </div>
        <div class="form-group">
            <label class="control-label">Description</label>
            <input name="Description" type="text" class="form-control" placeholder="Please insert description" value="<?php echo $Description;?>">
        </div>
        <div class="form-group ">
            <label class="control-label">Type <span style="color:red;">*</span></label>
            <select name="type" class="form-Control">
            <?php echo "<option value=\"\"></option>";
                if (empty($_POST["type"])){
                    foreach ($types as $type){
                        echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                    }
                }  else {
                    foreach ($types as $type){
                        if ($_POST["type"] == $type["Type_ID"]){
                            echo "<option value=\"".$type["Type_ID"]."\" selected>".$type["Type"]." ".$type["Description"]."</option>";
                        }else{
                            echo "<option value=\"".$type["Type_ID"]."\">".$type["Type"]." ".$type["Description"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn" href="Role.php">Back</a>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}