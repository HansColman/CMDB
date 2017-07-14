<?php
print "<h2>".htmlentities($title)."</h2>";
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
echo "<th>Type</th>";
echo "<th>Serialnumber</th>";
echo "<th># Keys</th>";
echo "<th>has Lock</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach ($rows as $row):
    echo "<tr>";
        echo "<td>".htmlentities($row['Type'])."</td>";
        echo "<td>".htmlentities($row['Serial'])."</td>";
        echo "<td>".htmlentities($row['AmountKeys'])."</td>";
        echo "<td>".htmlentities($row['hasLock'] == 1 ? "Yes" : "No")."</td>";
        echo "</tr>";
endforeach;
echo "</tbody>";
echo "</table>";
?>
<p></p>
<form class="form-horizontal" action="" method="post">
    <div class="form-group">
        <label class="control-label" for="reason">Reason <span style="color:red;">*</span></label>
        <input name="reason" type="text" id="reason" placeholder="Please insert reason" value="<?php echo $Reason;?>">
    </div>
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Delete</button>
        <a class="btn" href="Kensington.php">Back</a>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
</form>