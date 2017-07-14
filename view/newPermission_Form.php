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
     	<label class="control-label">Level <span style="color:red;">*</span></label>
        <select name="Level" class="form-control">
        <?php echo "<option value=\"\"></option>";
            if (empty($_POST["Level"])){
                foreach ($Levels as $level){
                    echo "<option value=\"".$level["Level"]."\">".$level["Level"]."</option>";
                }
            }  else {
                foreach ($Levels as $level){
                    if ($_POST["Level"] == $level["Level"]){
                        echo "<option value=\"".$level["Level"]."\" selected>".$level["Level"]."</option>";
                    }else{
                        echo "<option value=\"".$level["Level"]."\">".$level["Level"]."</option>";
                    }
                }
            }
        ?>
        </select>
        </div>
        <div class="form-group">
        	<label class="control-label">Menu <span style="color:red;">*</span></label>
            <select name="menu" class="form-control">
            <?php echo "<option value=\"\"></option>";
                if (empty($_POST["menu"])){
                    foreach ($Menus as $type){
                        echo "<option value=\"".$type["Menu_id"]."\">".$type["label"]."</option>";
                    }
                }  else {
                    foreach ($Menus as $type){
                        if ($_POST["menu"] == $type["Menu_id"]){
                            echo "<option value=\"".$type["Menu_id"]."\" selected>".$type["label"]."</option>";
                        }else{
                            echo "<option value=\"".$type["Menu_id"]."\">".$type["label"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
        <div class="form-group">
        	<label class="fomr-control-label">Permission <span style="color:red;">*</span></label><br>
            <select name="permission" class="form-control">
            <?php echo "<option value=\"\"></option>";
                if (empty($_POST["permission"])){
                    foreach ($Perms as $perm){
                        echo "<option value=\"".$perm["perm_id"]."\">".$perm["permission"]."</option>";
                    }
                }  else {
                    foreach ($Perms as $perm){
                        if ($_POST["permission"] == $perm["perm_id"]){
                            echo "<option value=\"".$perm["perm_id"]."\" selected>".$perm["permission"]."</option>";
                        }else{
                            echo "<option value=\"".$perm["perm_id"]."\">".$perm["permission"]."</option>";
                        }
                    }
                }
            ?>
            </select>
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn" href="Permission.php">Back</a>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
 </form>
<?php }  else {
    $this->showError("Application error", "You do not access to this page");
}