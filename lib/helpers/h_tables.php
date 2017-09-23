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

function buildCards($dbResultSet, $divsHead) {
    $allDivs = "";

    while ($row = $dbResultSet->fetchArray(SQLITE3_ASSOC)) {
        $currentDiv = "<div class = 'inlineDiv'>";
        $an = $divsHead['An']($row);
        $luna = $divsHead['Luna']($row);
        $imagine = $divsHead['Imagine']($row);

        $currentDiv .= $imagine;
        $currentDiv .= "<p>$an-$luna</p>";

        $currentDiv .= "</div>";
        $allDivs .= $currentDiv . PHP_EOL;
    }
    return $allDivs;
}

function getColData($row, $colName) {
    return $row[$colName];
}

// TODO scoate html
function getTd($cellValue) {
    return "<td>$cellValue</td>";
}

