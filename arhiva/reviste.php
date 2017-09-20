<?php
// ROOT si config.php sunt definite de index.php
//DEFINE("ROOT", "..");
//require_once(RESOURCES . "/config.php");
require_once HELPERS . "/h_tables.php";

$toaterevistele = $db->query("
    SELECT rev.*, ed.cnt
    FROM reviste rev
    LEFT JOIN (
        SELECT revista_id, COUNT(editie_id) cnt
        FROM editii
        WHERE tip = 'revista'
        GROUP BY revista_id) ed
    USING (revista_id)
    GROUP BY rev.revista_id
  ");

$tabelHead = array(
    "Nume"          => function ($row) {return getColData($row, 'revista_nume');},
    "Editii Arhiva" => function ($row) {return makeEditiiInfo(getColData($row, 'cnt'),
                                                              getColData($row, 'aparitii'));},
    "Coperta"       => function ($row) {return makeImgUrl(getNumeFisier(getColData($row, 'revista_nume')),
                                                          getColData($row, 'revista_id'));}
);

$tabelBody = buildRowsDinamic($toaterevistele, $tabelHead);

include_once TEMPL . "/tpl_tabel.php";

/* --- internals --- */

function getNumeFisier($numeRevista) {
    $simpleName = preg_replace('/[^a-z0-9 ]+/', "", strtolower($numeRevista));
    $imgDirPath = IMG . "/coperti/$simpleName.jpg";
    if (file_exists($imgDirPath)) {
        return $imgDirPath;
    } else return IMG . "/coperti/default.jpg";
}

function makeEditiiInfo($countArhiva, $countTotal) {
    $countTotal = explode(" ", $countTotal, 2)[0];
    if (!isset($countArhiva)) $countArhiva = "0";
    return "$countArhiva / $countTotal";
}

function makeImgUrl($imagePath, $revistaId) {
    $targetLink = ARHIVA . "/editii.php" . "?revista=$revistaId";
    return "<a href=\"$targetLink\"><img src=\"$imagePath\" alt=\"Image\" style = \"max-width:20%\"/></a>";
}