<h2>Assign Account</h2>
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
    echo "<th>Name</th>";
    echo "<th>UserID</th>";
    echo "<th>E Mail</th>";
    echo "<th>Language</th>";
    echo "<th>Type</th>";
    echo "<th>Active</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($rows as $row):
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
    echo "</table>";?>
<p></p>
<form role="form" class="form-horizontal" action="" method="post">
    <div class="form-group">
        <label for="account">Account <span style="color:red;">*</span></label>
        <select name="account" id="account" class="form-control">
        <?php echo "<option value=\"\"></option>";
            if (empty($_POST["account"])){
                foreach ($accounts as $account){
                    echo "<option value=\"".$account["Acc_ID"]."\">".$account["UserID"]." ".$account["Application"]."</option>";
                }
            }  else {
                foreach ($accounts as $account){
                    if ($_POST["account"] == $account["Acc_ID"]){
                        echo "<option value=\"".$account["Acc_ID"]."\" selected>".$account["UserID"]." ".$account["Application"]."</option>";
                    }else{
                        echo "<option value=\"".$account["Acc_ID"]."\">".$account["UserID"]." ".$account["Application"]."</option>";
                    }
                }
            }
        ?>
        </select>
    </div>
    <div class="form-group has-feedback">
        <label class="control-label">From <span style="color:red;">*</span></label>
        <input type="text" class="form-control" placeholder="DD/MM/YYYY" name="start" id="start-date"/>
        <i class="glyphicon glyphicon-calendar form-control-feedback"></i>
    </div>
    <div class="form-group has-feedback">
        <label class="control-label">Until</label>
        <input type="text" class="form-control" placeholder="DD/MM/YYYY" name="end" id="end-date"/>
        <i class="glyphicon glyphicon-calendar form-control-feedback"></i>
    </div>
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Assign</button>
        <a class="btn" href="identity.php">Back</a>
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