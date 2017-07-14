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
            <label class="control-label">UserID <span style="color:red;">*</span></label>
            <input class="form-control" name="UserID" type="text" placeholder="Please insert UserID" value="<?php echo $UserID;?>">
        </div>
        <div class="form-group ">
            <label class="control-label">Type <span style="color:red;">*</span></label>
            <select name="type" class="form-control">
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
        <div class="Form-group ">
            <label class="control-label">Application <span style="color:red;">*</span></label>
            <select name="Application" class="form-control">
            <?php echo "<option value=\"\"></option>";
                if (empty($_POST["Application"])){
                    foreach ($applications as $application){
                        echo "<option value=\"".$application["App_ID"]."\">".$application["Name"]."</option>";
                    }
                }  else {
                    foreach ($applications as $application){
                        if ($_POST["Application"] == $application["App_ID"]){
                            echo "<option value=\"".$application["App_ID"]."\" selected>".$application["Name"]."</option>";
                        }else{
                            echo "<option value=\"".$application["App_ID"]."\">".$application["Name"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div> 
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn" href="account.php">Back</a>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}