<?php
// ROOT si config.php sunt definite de index.php
//DEFINE("ROOT", "..");
//require_once(RESOURCES . "/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_html.php";

$revisteDbResult = $db->query("
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

$revisteCardRecipe = array(
    "Titlu"       => function ($row) {return getColData($row, 'revista_nume');},
    "Subtitlu"    => function ($row) {return makeEditiiInfo(getColData($row, 'cnt'),
                                                            getColData($row, 'aparitii'));},
    "Imagine"     => function ($row) {return makeImgUrl(getNumeFisier(getColData($row, 'revista_nume')),
                                                                      getColData($row, 'revista_id'));},
    "HtmlClasses" => array("inlineDiv")
);

$revisteCards = buildCards($revisteDbResult, $revisteCardRecipe);

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

// TODO scoate html
function makeImgUrl($imagePath, $revistaId) {
    $targetLink = ARHIVA . "/editii.php" . "?revista=$revistaId";
    return "<a href='$targetLink'><img src='$imagePath' alt='Image' style = 'max-width:20%'/></a>";
}