<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_html.php";

require_once LIB . "/Editie.php";
require_once DB_DIR. "/DbConstants.php";
use ArhivaRevisteVechi\lib\Editie;

$revistaId = $_GET["revista"];

//$editiiDbResult = $db->query("
//    SELECT r.revista_nume, e.*
//    FROM editii e
//    LEFT JOIN reviste r
//    USING ('revista_id')
//    WHERE 1
//    AND e.revista_id = '$revistaId'
//    AND e.tip = 'revista'
//");

$editiiDbResult = $db->query(Editie::getRegularDbQuery($revistaId));

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

$pageContent = buildCards($editiiDbResult, $revisteCardRecipe);

$editiiArray = array();
while ($dbRow = $editiiDbResult->fetchArray(SQLITE3_ASSOC)) {
    $editiiArray[] = new Editie($dbRow);
}

include_once HTMLLIB . "/view_simple.php";


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