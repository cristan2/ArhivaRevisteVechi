<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_html.php";

$revistaId = $_GET["revista"];

$editiiDbResult = $db->query("
    SELECT r.revista_nume, e.*
    FROM editii e
    LEFT JOIN reviste r
    USING ('revista_id')
    WHERE 1
    AND e.revista_id = '$revistaId'
    AND e.tip = 'revista'
");

$revisteCardRecipe = array(
    "Titlu"     => function ($row) {return makeCardTitle(getColData($row, 'an'),
                                                         getColData($row, 'luna') );},
    "Subtitlu"  => function ($row) {return "Nr. " . getColData($row, 'numar');},
    "Imagine"   => function ($row) {return makeImgUrl(getColData($row, 'revista_nume'),
                                                      getColData($row, 'an'),
                                                      getColData($row, 'luna'),
                                                      1,
                                                      getColData($row, 'editie_id'));},
    "HtmlClasses" => array("inline-div")
);

$revisteCards = buildCards($editiiDbResult, $revisteCardRecipe);

include_once HTMLLIB . "/tpl_tabel.php";


/* --- internals --- */

/*
 * Construieste thumbnail cu link catre revista
 */
function makeImgUrl($nume_revista, $an, $luna, $pgNo, $editieId) {
    $baseImgName = getBaseImageName($nume_revista, $an, $luna, $pgNo);
    $imgDir = getImageDir($nume_revista, $an, $luna);

    $imgThumbSrc = getImageThumbPath($imgDir, $baseImgName);
//    $targetLink = ARHIVA."/articole.php?editie=$editieId";
    $targetLink = getEditieUrl($editieId);

    return getImageWithLink($imgThumbSrc, $targetLink, "card-img");
}

function makeCardTitle($an, $luna) {
    return "$luna / $an";
}