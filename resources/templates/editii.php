<?php

$revistaId = $_POST["reviste"];
$toateEditiile = $db->query("SELECT * FROM editii WHERE revista_id = '$revistaId'; ");

afiseazaTabel($toateEditiile);

// TODO fix this
function afiseazaTabel($toateEditiile) {
    $randuri = getRows($toateEditiile);
    echo <<<_BAGAHTML
	<table>
	    <thead>
	        <tr>
	            <th>An</th>
	            <th>Luna</th>
	            <th>Nr.</th>
	            <th>Joc full</th>
	        </tr>
	    </thead>
	    <tbody>
	        $randuri;
	    </tbody>
    </table>
_BAGAHTML;
}

function getRows($toateEditiile) {
    $randuri = "";
    while ($row = $toateEditiile->fetchArray(SQLITE3_ASSOC)) {
        $an = $row['an'];
        $luna = $row['luna'];
        $nr = $row['numar'];
        $joc_complet = $row['joc_complet'];
        $randuri .= getRowTableData($an, $luna, $nr, $joc_complet);
//        echo $an . " | " . $luna . " | " . $nr . " | " . $joc_complet;
    }
    return $randuri;
}

function getRowTableData(...$cols) {
    $tableRow= "<tr>";
    foreach ($cols as $col) {
        $tableRow .= "<td>$col</td>";
    }
    return $tableRow."</tr><br>".PHP_EOL;
}
