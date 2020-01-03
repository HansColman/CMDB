<?php
print "<h2>" . htmlentities ($title) . "</h2>";
if ( $errors ) {
    print '<ul class="list-group">';
    foreach ( $errors as $field => $error ) {
        print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
    }
    print '</ul>';
}
echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
echo "<table class=\"table table-bordered table-hover\">";
echo "<thead>";
echo "<tr>";
echo "<th rowspan=\"2\">Round:</th>";
foreach ($players as $row):
    echo "<th colspan=\"3\" scope=\"colgroup\" class=\"text-center\"> Player: ".htmlentities($row['Name'])."</th>";
endforeach;
echo "</tr>";
echo "<tr>";
foreach ($players as $row):
    echo "<th scope=\"col\">Required</th>";
    echo "<th scope=\"col\">Received</th>";
    echo "<th scope=\"col\">Score</th>";
endforeach;
echo "</tr>";
echo "</thead>";
echo "<tbody>";
for ($i=1; $i <= $round-1; $i ++){
	echo "<tr>";
	echo "<th scope=\"row\" width=\"3%\">".$i.":</td>";
	foreach (${"resultsRound".$i} as $result){
		echo "<td>".$result["Required"]."</td>";
		echo "<td>".$result["Received"]."</td>";
		echo "<td>".$result["Score"]."</td>";
	}
	echo "</tr>";
}
echo "<tr>";
echo "<th width=\"3%\">".$round.":</td>";
for ($i =1 ; $i <= $amount;$i++){
    $ReceivedPlayer = "ReceivedPlayer".$i;
    $RequiredPlayer = "RequiredPlayer".$i;
    $amountreq = isset($_POST[$RequiredPlayer])?$_POST[$RequiredPlayer]:0;
    $amountrec = isset($_POST[$ReceivedPlayer])?$_POST[$ReceivedPlayer]:0;
    echo "<td><input type=\"text\" name=\"".$RequiredPlayer."\" value=\"".$amountreq."\"></td>";
    echo "<td><input type=\"text\" name=\"".$ReceivedPlayer."\" value=\"".$amountrec."\"></td>";
    echo "<td>&nbsp</td>";
}
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<input type=\"hidden\" name=\"formRound".$round."-submitted\" value=\"1\" /><br>";
if ($lastround != 1){
	echo "<div class=\"form-actions\">";
	echo "<button type=\"submit\" class=\"btn btn-success\">Next Round</button>";
	echo "</div>";
}
echo "</form>";
?>