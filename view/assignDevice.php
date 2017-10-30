<?php
echo "<H2>".htmlentities($title)."</H2>";
if ( $errors ) {
    print '<ul class="list-group">';
    foreach ( $errors as $field => $error ) {
        print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
    }
    print '</ul>';
}
if ($AssignAccess){
    //echo " <a href=\"Identity.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
    echo "<p></p>";
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>UserID</th>";
    echo "<th>E Mail</th>";
    echo "<th>Language</th>";
    echo "<th>Type</th>";
    echo "<th>Active</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($idenrows as $row):
        echo "<tr>";
        echo "<td>".htmlentities($row['Name'])."</td>";
        echo "<td>".htmlentities($row['UserID'])."</td>";
        echo "<td>".htmlentities($row['E_Mail'])."</td>";
        echo "<td>".htmlentities($row['Language'])."</td>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Active'])."</td>";
        echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
    ?>
    <p style="color:red;">Please select at least on of the options</p>
    <form action="" method="post">
        <div class="form-group">
    		<label class="control-label">Laptop</label>
            <select name="Laptop" class="form-control">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Laptop"])){
                        foreach ($Laptoprows as $type){
                            echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                        }
                    }  else {
                        foreach ($Laptoprows as $type){
                            if ($_POST["Laptop"] == $type["AssetTag"]){
                                echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }else{
                                echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
    		<label class="control-label">Desktop</label>
            <select name="Desktop" class="form-control">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Desktop"])){
                        foreach ($Desktoprows as $type){
                            echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                        }
                    }  else {
                        foreach ($Desktoprows as $type){
                            if ($_POST["Desktop"] == $type["AssetTag"]){
                                echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }else{
                                echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
    		<label class="control-label">Screen</label>
            <select name="Screen" class="form-control">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Screen"])){
                        foreach ($Monitorrows as $type){
                            echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                        }
                    }  else {
                        foreach ($Monitorrows as $type){
                            if ($_POST["Screen"] == $type["AssetTag"]){
                                echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }else{
                                echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
    		<label class="control-label">Token</label>
            <select name="Token" class="form-control">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Token"])){
                        foreach ($Tokenrows as $type){
                            echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                        }
                    }  else {
                        foreach ($Tokenrows as $type){
                            if ($_POST["Screen"] == $type["AssetTag"]){
                                echo "<option value=\"".$type["AssetTag"]."\" selected>".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }else{
                                echo "<option value=\"".$type["AssetTag"]."\">".$type["AssetTag"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
    		<label class="control-label">Mobile</label>
            <select name="Mobile" class="form-control">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Mobile"])){
                        foreach ($Mobilerows as $type){
                            echo "<option value=\"".$type["EMEI"]."\">".$type["EMEI"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                        }
                    }  else {
                        foreach ($Mobilerows as $type){
                            if ($_POST["Screen"] == $type["EMEI"]){
                                echo "<option value=\"".$type["EMEI"]."\" selected>".$type["EMEI"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }else{
                                echo "<option value=\"".$type["EMEI"]."\">".$type["EMEI"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
    		<label class="control-label">Internet</label>
            <select name="Internet" class="form-control">
                <?php echo "<option value=\"\"></option>";
                    if (empty($_POST["Internet"])){
                        foreach ($Internetrows as $type){
                            echo "<option value=\"".$type["PhoneNumber"]."\">".$type["PhoneNumber"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                        }
                    }  else {
                        foreach ($Internetrows as $type){
                            if ($_POST["Screen"] == $type["PhoneNumber"]){
                                echo "<option value=\"".$type["PhoneNumber"]."\" selected>".$type["PhoneNumber"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }else{
                                echo "<option value=\"".$type["PhoneNumber"]."\">".$type["PhoneNumber"].", Type: ".$type["Type"]." ".$type["Vendor"]."</option>";
                            }
                        }
                    }
                ?>
            </select>
        </div>
        <input type="hidden" name="form-submitted" value="1" /><br>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Assign</button>
        </div>
        <div class="form-group">
            <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
        </div>
    </form>
    <?php
}else {
    $this->showError("Application error", "You do not access to this page");
}