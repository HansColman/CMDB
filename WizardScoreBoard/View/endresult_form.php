<?php
print "<h2>" . htmlentities ($title) . "</h2>";
echo "<table class=\"table table-sm table-bordered table-hover\">";
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
for ($i=1; $i <= $round; $i ++){
    echo "<tr>";
    echo "<th scope=\"row\" width=\"3%\">".$i.":</td>";
    foreach (${"resultsRound".$i} as $result){
        echo "<td>".$result["Required"]."</td>";
        echo "<td>".$result["Received"]."</td>";
        echo "<td>".$result["Score"]."</td>";
    }
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
?>