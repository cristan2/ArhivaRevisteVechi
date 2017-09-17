<?php

// TODO scoate html
function buildRowsDinamic($dbResultSet, $tabelHead) {
    $allRows = "";

    while ($row = $dbResultSet->fetchArray(SQLITE3_ASSOC)) {
        $currentRow = "<tr>";
        foreach($tabelHead as $colNume => $colValue) {
            $currentRow .= getTd($colValue($row));
        }
        $currentRow .= "</tr>";
        $allRows .= $currentRow . PHP_EOL;
    }
    return $allRows;
}

function getColData($row, $colName) {
    return $row[$colName];
}

// TODO scoate html
function getTd($cellValue) {
    return "<td>$cellValue</td>";
}

