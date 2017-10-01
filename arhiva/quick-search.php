<?php

DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/h_misc.php";

if (!isset($_GET["search"])) {
    $content = '<a href = "?search=scan-status">Scan status</a>';
} else {
    $searchParam = $_GET["search"];
    switch ($searchParam) {
        case "scan-status":
            $dbResult = getScanStatusDbResult();
            $dbFiltered = filterDbResult($dbResult);
            $content = buildHtmlTableFromArray($dbFiltered);
//            $content = buildDokuWikiTableFromArray($dbFiltered);
    }
}


/* --- afisare in pagina --- */

include_once HTMLLIB . "/tpl_tabel.php";


/* --- internals --- */

function getScanStatusDbResult() {
    global $db;
    return $db->query("
        SELECT r.revista_nume, r.aparitii,
        e.editie_id, e.numar, e.an, e.luna, e.luna_sfarsit, e.nr_pagini, e.scan_info_nr_pg, e.scan_info_pg_lipsa, e.scan_info_observatii,
        COUNT(a.articol_id) AS nr_articole
        FROM editii e
        LEFT JOIN reviste r USING ('revista_id')
        LEFT JOIN articole a USING ('editie_id')
        WHERE e.numar <> ''
		GROUP BY editie_id
		ORDER BY r.revista_nume, e.an
    ");
}

// TODO vezi daca poti s-o refactorizezi
/*
 * Construieste un array de reviste cu probleme de arhivare de tipul
 * array (
 *      "Level"      => array(
 *                          'An'            => '1997',
 *                          'Luna'          => 'septembrie',
 *                          'Pagini Lipsa'  => '1 (pg. 3),
 *                          'Cuprins'       => '�',
 *                          'Calitate Scan' => 'LQ - rescan'
 *                      ),
 *      "Games4Kids" => array (...)
 * )
 */
function filterDbResult($dbResult) {
    $filteredDbRows = array();

    while ($dbRow = $dbResult->fetchArray(SQLITE3_ASSOC)) {
        $infoRevistaCurenta = array();

        // general info
        $revista = $dbRow['revista_nume'];
        $an = $dbRow['an'];
        $luna = $dbRow['luna'];

        // pagini lipsa
        $listaPgLipsa = "";
        $dbNrPaginiTotal = $dbRow['nr_pagini'];
        $dbNrPaginiScanate = $dbRow['scan_info_nr_pg'];
        $dbListaPaginiLipsa = $dbRow['scan_info_pg_lipsa'];

        if ($dbNrPaginiScanate < $dbNrPaginiTotal) {
            if (isset($dbListaPaginiLipsa)) $listaPgLipsa = ($dbNrPaginiTotal - $dbNrPaginiScanate) . " (pg. $dbListaPaginiLipsa)";
            else if (empty($dbNrPaginiScanate)) $listaPgLipsa =  "Nescanat";
            else $listaPgLipsa = "???";
        }

        // cuprins
        $areCuprins = true;
        $nrArticole = $dbRow['nr_articole'];
        if ($nrArticole == 0) {
            $areCuprins = false;
        }

        // calitate scan
        $calitateScan = $dbRow['scan_info_observatii'];
        if (!startsWith($calitateScan, 'LQ') && !startsWith($calitateScan, 'MQ')) {
            unset($calitateScan);
        }

        // add to filtered result
        if (!empty($listaPgLipsa) || !$areCuprins || !empty($calitateScan)) {
            $infoRevistaCurenta['An'] = $an;
            $infoRevistaCurenta['Luna'] = convertLuna($luna);

            $infoRevistaCurenta['Pagini Lipsa'] = empty($listaPgLipsa) ? "&bull;" : $listaPgLipsa;
            $infoRevistaCurenta['Cuprins'] = $areCuprins ? "&bull;" : "NU";
            $infoRevistaCurenta['Calitate Scan'] = empty($calitateScan) ? "&bull;" : $calitateScan;

            // adauga randul curent in array-ul revistei corespunzatoare
            $filteredDbRows[$revista][] = $infoRevistaCurenta;
        }
    } // end iterating db row

    return $filteredDbRows;
}