<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_html.php";

require_once LIB . "/Editie.php";
require_once DB_DIR. "/DBC.php";
use ArhivaRevisteVechi\lib\Editie;

$revistaId = $_GET["revista"];

$editiiDbResult = $db->queryToateEditiile($revistaId);

$revisteCardRecipe = array(
    "Titlu"     => function ($row) {return makeCardTitle(getColData($row, 'an'),
                                                         getColData($row, 'luna') );},
    "Subtitlu"  => function ($row) {return "Nr. " . getColData($row, 'numar');},
    "Imagine"   => function ($row) {return makeImgUrl(getColData($row, 'revista_nume'),
                                                      getColData($row, 'an'),
                                                      getColData($row, 'luna'),
                                                      1,
                                                      getColData($row, 'editie_id'));},
    "HtmlClasses" => array("reviste-cards")
);

$pageContent = buildCards($editiiDbResult, $revisteCardRecipe);

// test
//$editiiArray = array();
//while ($dbRow = $editiiDbResult->fetchArray(SQLITE3_ASSOC)) {
//    $editiiArray[] = new Editie($dbRow);
//}
// end test

include_once HTMLLIB . "/view_simple.php";


/* --- internals --- */

// TODO move to Editie
/**
 * Construieste thumbnail cu link catre revista
 */
function makeImgUrl($nume_revista, $an, $luna, $pgNo, $editieId) {
    $baseImgName = getBaseImageName($nume_revista, $an, $luna, $pgNo);
    $imgDir = buildImageDir($nume_revista, $an, $luna);

    $imgThumbSrc = getImageThumbPath($imgDir, $baseImgName);
    $targetLink = getEditieUrl($editieId);

    return getImageWithLink($imgThumbSrc, $targetLink, "card-img");
}

function makeCardTitle($an, $luna) {
    return "$luna / $an";
}