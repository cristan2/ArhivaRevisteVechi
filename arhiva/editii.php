<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_images.php";

$revistaId = $_GET["revista"];

$toateEditiile = $db->query("
    SELECT r.revista_nume, e.*
    FROM editii e
    LEFT JOIN reviste r
    USING ('revista_id')
    WHERE 1
    AND e.revista_id = '$revistaId'
    AND e.tip = 'revista'
");

$tabelHead = array(
    "Id"        => function ($row) {return getColData($row, 'editie_id');},
    "An"        => function ($row) {return getColData($row, 'an');},
    "Luna"      => function ($row) {return getColData($row, 'luna');},
    "Nr."       => function ($row) {return getColData($row, 'numar');},
    "Joc full"  => function ($row) {return getColData($row, 'joc_complet');},
    "Imagine"   => function ($row) {return makeImgUrl(getColData($row, 'revista_nume'),
                                                      getColData($row, 'an'),
                                                      getColData($row, 'luna'),
                                                      1,
                                                      getColData($row, 'editie_id'));}
    );

$tabelBody = buildRowsDinamic($toateEditiile, $tabelHead);

include_once TEMPL . "/tpl_tabel.php";


/* --- internals --- */

/*
 * Construieste thumbnail cu link catre revista
 */
function makeImgUrl($nume_revista, $an, $luna, $pgNo, $editieId) {
    $baseImgName = getBaseImageName($nume_revista, $an, $luna, $pgNo);
    $imgDir = getImageDir($nume_revista, $an, $luna);

    $imgThumbSrc = getImageThumbPath($imgDir, $baseImgName);
    $targetLink = ARHIVA."/articole.php?editie=$editieId";

    return getImageWithLink($imgThumbSrc, $targetLink);
}
