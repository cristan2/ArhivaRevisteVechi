<?php

DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/h_misc.php";

// load classes
require_once DB_DIR. "/DBC.php";
require_once LIB . "/Articol.php";
require_once LIB . "/Editie.php";
use ArhivaRevisteVechi\resources\db\DBC;
use ArhivaRevisteVechi\lib\Articol;
use ArhivaRevisteVechi\lib\Editie;


$editieId = $_GET["editie"];


/* --- info editia curenta --- */

$editiaCurenta = $db->getFirstRowFromResult($db->getEditie($editieId));
$editiaCurenta = new Editie($editiaCurenta);

// next & prev links
// TODO: trebuie sa existe si un max(editie_id) pentru disable la navLinkNext
$prevEditieId = $db->getFirstRowFromResult($db->getEditieIdFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) - 1));
$navLinkPrev = getEditieUrl($prevEditieId[DBC::ED_ID]);

$nextEditieId = $db->getFirstRowFromResult($db->getEditieIdFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) + 1));
$navLinkNext = getEditieUrl($nextEditieId[DBC::ED_ID]);

$titluEditieCurenta = "{$editiaCurenta->numeRevista} nr. {$editiaCurenta->numar}";
$lunaEditieCurenta = "(". convertLuna($editiaCurenta->luna) ." {$editiaCurenta->an})";


/* --- info pagina curenta --- */

$paginaCurentaNr = "1";
if (isset($_GET['pagina'])) $paginaCurentaNr = $_GET['pagina'];
$paginaCurentaImagePath = getImage($editiaCurenta, $paginaCurentaNr);


/* --- cuprins articole --- */

$articoleDbResult = $db->getArticoleDinEditie($editieId);

$articoleCardRecipe = array(
    "pagina"        => function ($row) {return getColData($row, DBC::ART_PG_TOC);},
    "rubrica"       => function ($row) {return getColData($row, DBC::ART_RUBRICA);},
    "titlu"         => function ($row) {return getColData($row, DBC::ART_TITLU);},
    "autor"         => function ($row) {return getColData($row, DBC::ART_AUTOR);},
    "pagini-count"  => function ($row) {return extractThumbPages(getColData($row, DBC::ART_PG_TOC),
                                                                  getColData($row, DBC::ART_PG_CNT));}
    );

$articoleCardRows = buildCardRows($articoleDbResult, $articoleCardRecipe);

// test
$articoleArray = array();
while ($dbRow = $articoleDbResult->fetchArray(SQLITE3_ASSOC)) {
    $articoleArray[] = new Articol($dbRow);
}
// var_dump($articoleArray);
// end test


/* --- afisare in pagina --- */

include_once HTMLLIB . "/view_dual.php";


/* --- internals --- */

/**
 * Construieste thumbnails pagini cu linkuri catre imaginea mare
 * Returneaza un string cu html pentru afisare in tabel
 */
function extractThumbPages($startPage, $pageCount) {
    global $editiaCurenta;
    $imgDir = getImageDir($editiaCurenta->numeRevista,
                            $editiaCurenta->an,
                            $editiaCurenta->luna);

    $pageThumbLinks = "";

    for ($pgIndex = 0; $pgIndex < $pageCount; $pgIndex++) {
        $thisPageNo = $startPage + $pgIndex;
        $imageBaseName = getBaseImageName($editiaCurenta->numeRevista,
                                            $editiaCurenta->an,
                                            $editiaCurenta->luna,
                                            $thisPageNo);
        $imageThumb = getImageThumbPath($imgDir, $imageBaseName);

        $destinationLink = getBaseUrl() . "?editie={$editiaCurenta->editieId}" . "&pagina=$thisPageNo";

        $pageThumbLinks .= getImageWithLink($imageThumb, $destinationLink, "minithumb")."  ";
    }
    return $pageThumbLinks;
}