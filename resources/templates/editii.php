<?php

$revistaId = $_POST["reviste"];
$toateEditiile = $db->query("SELECT * FROM editii WHERE revista_id = '$revistaId' AND tip = 'revista'; ");

afiseazaTabel($toateEditiile);

//afiseazaMotherFuckingTabelCaCelaltSeIntrerupe($toateEditiile);


// FIXME: WTF echo nu afiseaza tot outputul
function afiseazaTabel($toateEditiile) {
    $randuri = getRows($toateEditiile);

    $output = "" .
    "<!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"utf-8\">
    </head>
    <body>
	<table>
	        <tr>
	            <th>An</th>
	            <th>Luna</th>
	            <th>Nr.</th>
	            <th>Joc full</th>
	        </tr> "
            . $randuri
    . "</table>
    </body>
    </html>";

    echo $output;
}

function getRows($toateEditiile) {
    $randuri = "";
    while ($row = $toateEditiile->fetchArray(SQLITE3_ASSOC)) {
        $id = $row['editie_id'];
        $an = $row['an'];
        $luna = $row['luna'];
        $nr = $row['numar'];
        $joc_complet = $row['joc_complet'];
        $randuri .= getRowTableData($an, $luna, $nr, $joc_complet) . PHP_EOL;
    }
    return $randuri;
}

// FIXME: stringul rezultatul rupe tabelul
function getRowTableData(...$cols) {
    $tableRow= "<tr>";
    foreach ($cols as $col) {
        $tableRow .= getTd($col);
    }
    return $tableRow . "</tr>";
}

function getTd($cellValue) {
    return "<td>$cellValue</td>";
}


// FIXME: temp function pana o repari pe aia normala
// CACAT, nici asta nu afiseaza tot tabelul cplm
function afiseazaMotherFuckingTabelCaCelaltSeIntrerupe($toateEditiile) {
    // table header
    echo "<!DOCTYPE html><html><head><meta charset=\"utf-8\"></head><body>";
    echo "<table><tr><th>An</th><th>Luna</th><th>Nr.</th><th>Joc full</th></tr>";

    // table body
    while ($row = $toateEditiile->fetchArray(SQLITE3_ASSOC)) {
        $id =   isset($row['editie_id'])            ? $row['editie_id'] : "-";
        $an =   isset($row['an'])                   ? $row['an'] : "-";
        $luna = isset($row['luna'])                ? $row['luna'] : "-";
        $nr =   isset($row['nr'])                  ? $row['nr'] : "-";
        $joc_complet =   isset($row['joc_complet']) ? $row['joc_complet'] : "-";
        echo "<tr><td>$id</td><td>$an</td><td>$luna</td><td>$nr</td><td>$joc_complet</td></tr>" . PHP_EOL;
    }
    echo "</table>";
    echo "</body></html>";
}