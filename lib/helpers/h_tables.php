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

function buildCards($dbResultSet, $divRecipe) {
    $allDivs = "";
    while ($row = $dbResultSet->fetchArray(SQLITE3_ASSOC)) {
        $currentDiv = "<div".getClassList($divRecipe['HtmlClasses']) .">";

        $titluCard    = "<h1>{$divRecipe['Titlu']($row)}</h1>";
        $subtitluCard = "<h2>{$divRecipe['Subtitlu']($row)}</h2>";
        $imagineCard  = $divRecipe['Imagine']($row);

        $currentDiv .= $imagineCard . $titluCard . $subtitluCard;
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

