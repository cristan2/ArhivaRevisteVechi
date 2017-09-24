<?php

// TODO scoate html

/**
 * Construieste carduri reviste
 * Cu 3 sectiuni: Imagine, Titlu si Subtitlu
 * In plus, primeste un array cu clasele CSS
 */
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

function buildCardRows($dbResultSet, $divRecipe) {
    $allRows = "<div class = 'articol-card-container'>" . PHP_EOL;

    while ($row = $dbResultSet->fetchArray(SQLITE3_ASSOC)) {
        $currentRow = "<div class = 'articol-card-row'>" . PHP_EOL;

        foreach($divRecipe as $colNume => $colValue) {
            $currentRow .= "<div class = 'articol-card-cell articol-card-$colNume'>";
            $currentRow .= $colValue($row);
            $currentRow .= "</div>";      // end div articol-card-cell
        }
        $currentRow .= "</div>";          // end div articol-card-row
        $allRows .= $currentRow . PHP_EOL;
    }
    return $allRows . "</div>" . PHP_EOL; // end div articol-card-container
}

function getColData($row, $colName) {
    return $row[$colName];
}

