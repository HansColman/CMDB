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
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php print htmlentities($Name); ?></td>
        </tr>
    </tbody>
</table>
<p></p>
<form class="form-horizontal" action="" method="post">
    <div class="form-group">
        <label class="control-label" for="reason">Reason <span style="color:red;">*</span></label>
        <input name="reason" type="text" id="reason" placeholder="Please insert reason" value="<?php echo $Reason;?>">
    </div>
    <input type="hidden" name="form-submitted" value="1" /><br>
    <div class="form-actions">
        <button type="submit" class="btn btn-success">Delete</button>
        <a class="btn" href="Application.php">Back</a>
    </div>
    <div class="form-group">
        <span class="text-muted"><em><span style="color:red;">*</span> Indicates required field</em></span>
    </div>
</form>