<h2>Assign Identity</h2>
<?php 
if ($AssignAccess){
    if ( $errors ) {
        print '<ul class="list-group">';
        foreach ( $errors as $field => $error ) {
            print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
        }
        print '</ul>';
    }
    echo "<table class=\"table table-striped table-bordered\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>UserID</th>";
    echo "<th>Application</th>";
    echo "<th>Type</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
    echo "<tr>";
    echo "<td>".htmlentities($row['UserID'])."</td>";
    echo "<td>".htmlentities($row['Application'])."</td>";
    echo "<td>".htmlentities($row['Type'])."</td>";
    echo "</tr>";
    endforeach;
    echo "</tbody>";
    echo "</table>";
    ?>
<p></p>
<form class="form-horizontal" action="" method="post">
    <div class="form-group">
        <label for="Identity">Identity <span style="color:red;">*</span></label>
        <select name="identity" id="Identity" class="form-control">
        <?php echo "<option value=\"\"></option>";
            if (empty($_POST["identity"])){
                foreach ($identities as $identity){
                    echo "<option value=\"".$identity["Iden_ID"]."\">".$identity["Name"]." ".$identity["UserID"]."</option>";
                }
            }  else {
                foreach ($accounts as $account){
                    if ($_POST["account"] == $identity["Iden_ID"]){
                        echo "<option value=\"".$identity["Iden_ID"]."\" selected>".$identity["Name"]." ".$identity["UserID"]."</option>";
                    }else{
                        echo "<option value=\"".$identity["Iden_ID"]."\">".$identity["Name"]." ".$identity["UserID"]."</option>";
                    }
                }
            }
        ?>
        </select>
    </div>
    <div class="form-group has-feedback">
        <label class="control-label">From <span style="color:red;">*</span></label>
        <input type="text" class="form-control" placeholder="DD/MM/YYYY" name="start" id="start-date"/>
        <i class="glyphicon glyphicon-calendar form-control-feedback date-pick"></i>
    </div>
    <div class="form-group has-feedback">
        <label class="control-label">Until</label>
        <input type="text" class="form-control" placeholder="DD/MM/YYYY" name="end" id="end-date"/>
        <i class="glyphicon glyphicon-calendar form-control-feedback"></i>
    </div>
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Assign</button>
        <a class="btn" href="Account.php">Back</a>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
</form>
<script>
    $(document).ready(function(){
      var date_input=$('input[name="start"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
    })
    $(document).ready(function(){
      var date_input=$('input[name="end"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
    })
</script>
<?php }else{
    $this->showError("Application error", "You do not access to this page");
}
    
