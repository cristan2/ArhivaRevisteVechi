<?php
// ROOT si config.php sunt definite de index.php
/*DEFINE("ROOT", "..");
require_once(RESOURCES . "/config.php");*/

require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/h_images.php";

$revisteDbResult = $db->queryToateRevistele();

$revisteCardRecipe = array(
    "Titlu"       => function ($row) {return getColData($row, 'revista_nume');},
    "Subtitlu"    => function ($row) {return makeEditiiInfo(getColData($row, 'cnt'),
                                                            getColData($row, 'aparitii'));},
    "Imagine"     => function ($row) {return makeImgUrl(getNumeFisier(getColData($row, 'revista_nume')),
                                                                      getColData($row, 'revista_id'));},
    "HtmlClasses" => array("inline-div")
);

$pageContent = buildCards($revisteDbResult, $revisteCardRecipe);

include_once HTMLLIB . "/view_simple.php";

/* --- internals --- */

// TODO move to helper
function getNumeFisier($numeRevista) {
    $simpleName = preg_replace('/[^a-z0-9 ]+/', "", strtolower($numeRevista));
    $imgDirPath = IMG . "/coperti/$simpleName.jpg";
    if (file_exists($imgDirPath)) {
        return $imgDirPath;
    } else return IMG . "/coperti/default.jpg";
}

// TODO move to Editie.php
function makeEditiiInfo($countArhiva, $countTotal) {
    $countTotal = explode(" ", $countTotal, 2)[0];
    if (!isset($countArhiva)) $countArhiva = "0";
    return "$countArhiva / $countTotal";
}

// TODO scoate html
function makeImgUrl($imagePath, $revistaId) {
    $targetLink = ARHIVA . "/editii.php" . "?revista=$revistaId";
    return getImageWithLink($imagePath, $targetLink, "card-img");
}