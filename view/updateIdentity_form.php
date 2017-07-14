<?php
print "<h2>".htmlentities($title)."</h2>";
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
        <label class="control-label">First Name <span style="color:red;">*</span></label>
        <input name="FirstName" type="text" class="form-control" placeholder="FirstName" value="<?php echo $FristName;?>">
    </div>
    <div class="form-group">
      <label class="control-label">Last Name <span style="color:red;">*</span></label>
      <input name="LastName" type="text" class="form-control" placeholder="LastName" value="<?php echo $LastName;?>">
    </div>
    <div class="form-group">
        <label class="control-label">UserID <span style="color:red;">*</span></label>
        <input name="UserID" type="text" class="form-control" placeholder="UserID" value="<?php echo $userid;?>" readonly="readonly">
    </div>
    <div class="form-group">
        <label class="control-label">Company</label>
        <input name="Company" type="text" class="form-control" placeholder="Company" value="<?php echo $company;?>">
    </div>
    <div class="form-group">
      <label class="control-label">E-Mail Address <span style="color:red;">*</span></label>
      <input name="EMail" type="text" class="form-control" placeholder="E-Mail Address" value="<?php echo $EMail;?>">
    </div>
    <div class="form-group ">
        <label class="control-label">Language <span style="color:red;">*</span></label>
        <select name="Language" class="form-control">
        <?php if(empty($Language)){?>
            <option value=""></option>
            <option value="NL">Dutch</option>
            <option value="FR">French</option>
            <option value="EN">English</option>
        <?php } elseif ($Language == "NL") {?>
            <option value=""></option>
            <option value="NL" selected>Dutch</option>
            <option value="FR">French</option>
            <option value="EN">English</option>
        <?php } elseif ($Language == "FR") {?>
            <option value=""></option>
            <option value="NL">Dutch</option>
            <option value="FR" selected>French</option>
            <option value="EN">English</option>
        <?php } elseif ($Language == "EN"){ ?>
            <option value=""></option>
            <option value="NL">Dutch</option>
            <option value="FR" >French</option>
            <option value="EN" selected>English</option>
        <?php } ?>
        </select>
    </div>
    <div class="form-group ">
        <label class="control-label">Type <span style="color:red;">*</span></label>
        <select name="type" class="form-control">
        <?php echo "<option value=\"\"></option>";
            if (empty($type)){
                foreach ($types as $row){
                    echo "<option value=\"".$row["Type_ID"]."\">".$row["Type"]." ".$row["Description"]."</option>";
                }
            }  else {
                foreach ($types as $row){
                    if ($type == $row["Type_ID"]){
                        echo "<option value=\"".$row["Type_ID"]."\" selected>".$row["Type"]." ".$row["Description"]."</option>";
                    }else{
                        echo "<option value=\"".$row["Type_ID"]."\">".$row["Type"]." ".$row["Description"]."</option>";
                    }
                }
            }
        ?>
        </select>
    </div> 
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Update</button>
        <a class="btn" href="identity.php">Back</a>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
</form>