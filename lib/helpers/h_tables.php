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

function buildDivRows($divArray, ...$containerClasses)
{
    $htmlClasses = getClassList($containerClasses);
    $divRowsContainer = "<div $htmlClasses>" . PHP_EOL;
    $divRowsContainer .= implode(PHP_EOL, $divArray);
    $divRowsContainer .= "</div>" . PHP_EOL;
    return $divRowsContainer;
}

function getColData($row, $colName) {
    return $row[$colName];
}

/*
 * Converteste un DB result intr-un tabel html simplu
 */
function buildHtmlTableFromDbResult($dbResultSet) {

    // RANDURI TABEL -----
    $randuriTabel = "";
    while ($dbRow = $dbResultSet->fetchArray(SQLITE3_ASSOC)) {
        $htmlRow = "<tr>";
        foreach($dbRow as $dbCellValue) {
            $htmlRow .= "<td>$dbCellValue</td>";
        }
        $htmlRow .= "</tr>";
        $randuriTabel .= $htmlRow . PHP_EOL;
    }

    // TABEL FINAL -----
    $tabel = "<div><table>" . PHP_EOL;
//    $tabel .= $headerTabel . PHP_EOL;
    $tabel .= $randuriTabel . PHP_EOL;
    $tabel .= "</table></div>" . PHP_EOL;
    return $tabel;
}

/*
 * Construieste un tabel html pentru afisarea statusului arhivei
 */
function buildHtmlTableFromArray($arrToDisplay) {

    $rezultatFinal = "";

    foreach ($arrToDisplay as $numeRevistaCurenta => $arrRevistaCurenta) {

        $rezultatFinal .= "<h2>$numeRevistaCurenta</h2>" . "<br>";

        // HEADER TABEL ----------
        $headerTabel = "<tr>";
        $arrKeys = array_keys($arrRevistaCurenta[0]);
        foreach($arrKeys as $key) {
            // skip An sa nu se repete, pentru ca l-am adaugat pe randul-intertitlu
            if ($key === 'An') continue;
            $headerTabel .= "<th>$key</th>";
        }
        $headerTabel .= "</tr>";


        // RANDURI TABEL ----------
        $randuriTabel = "";
//        $anulCurent = $arrRevistaCurenta[0]['An'];
        $anulCurent = "";
    //    $anulCurent = '';
        foreach ($arrRevistaCurenta as $arrRow) {
            // adauga un rand gol intre ani
            if ($arrRow['An'] != $anulCurent) {
                $randuriTabel .= '<tr class = "empty-row"><td colspan="' . count($arrKeys) . '">' . $arrRow['An'] . '</td></tr>' . PHP_EOL;
                $anulCurent = $arrRow['An'];
            }

            // construieste randul
            $htmlRow = "<tr>";
            foreach($arrKeys as $key) {
                // skip An sa nu se repete, pentru ca l-am adaugat pe randul-intertitlu
                if ($key === 'An') continue;
                $htmlRow .= "<td>$arrRow[$key]</td>";
            }
            $htmlRow .= "</tr>";

            // adauga randul in lista de randuri
            $randuriTabel .= $htmlRow . PHP_EOL;
        }

        // TABEL FINAL ----------
        $tabel = "<div><table>" . PHP_EOL;
        $tabel .= $headerTabel . PHP_EOL;
        $tabel .= $randuriTabel . PHP_EOL;
        $tabel .= "</table></div>" . PHP_EOL;
        $rezultatFinal .= $tabel;
    }
    return $rezultatFinal;
}

/*
 * Construieste un tabel cu sintaxa DokuWiki pentru afisarea statusului arhivei
 */
function buildDokuWikiTableFromArray($arrToDisplay) {

    $rezultatFinal = "";

    foreach ($arrToDisplay as $numeRevistaCurenta => $arrRevistaCurenta) {

        $rezultatFinal .= "===== $numeRevistaCurenta =====" . "<br>";

        // HEADER TABEL ----------
        $headerTabel = "^";
        $arrKeys = array_keys($arrRevistaCurenta[0]);
        foreach ($arrKeys as $key) {
            if ($key === 'An') continue;
            $headerTabel .= "&nbsp;&nbsp;$key&nbsp;&nbsp;^";
        }
        $headerTabel .= "<br>";

        // RANDURI TABEL ----------
        $randuriTabel = "";
        $anulCurent = '';
        foreach ($arrRevistaCurenta as $arrRow) {
            // adauga un rand gol intre ani
            if ($arrRow['An'] != $anulCurent) {
                $randuriTabel .= '^' . $arrRow['An'] . str_repeat('^', count($arrKeys)-1) . '<br>';
                $anulCurent = $arrRow['An'];
            }

            // construieste randul
            $dokuRow = "|";
            foreach ($arrKeys as $key) {
                if ($key === 'An') continue;
                $dokuRow .= "&nbsp;&nbsp;$arrRow[$key]&nbsp;&nbsp;|";
            }
            $dokuRow .= "<br>";

            // adauga randul in lista de randuri
            $randuriTabel .= $dokuRow . PHP_EOL;
        }

        // TABEL FINAL ----------
        $tabel = "";
        $tabel .= $headerTabel . PHP_EOL;
        $tabel .= $randuriTabel . PHP_EOL;

        $rezultatFinal .= $tabel;
    }
    return $rezultatFinal;
}
