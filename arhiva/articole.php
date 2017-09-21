<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_images.php";

$editieId = $_GET["editie"];

$toateArticolele = $db->query("
    SELECT a.*, e.an, e.luna
    FROM articole a
    LEFT JOIN editii e
    USING (editie_id)
    WHERE editie_id = $editieId
");

$tabelHead = array(
    "Pg."       => function ($row) {return getColData($row, "pg_toc");},
    "Rubrica."  => function ($row) {return getColData($row, "rubrica");},
    "Titlu."    => function ($row) {return getColData($row, "titlu");},
    "Autor."    => function ($row) {return getColData($row, "autor");},
    "Pagini"    => function ($row) {return extractPagesForArticle(getColData($row, 'revista_nume'),
                                                                  getColData($row, 'an'),
                                                                  getColData($row, 'luna'),
                                                                  getColData($row, 'pg_toc'),
                                                                  getColData($row, 'pg_count'));}
    );

$tabelBody = buildRowsDinamic($toateArticolele, $tabelHead);

include_once TEMPL . "/tpl_dual_div.php";


/* --- internals --- */

function extractPagesForArticle($numeRevista, $an, $luna, $startPage, $pageCount) {
    $imgDir = getImageDir($numeRevista, $an, $luna);

    $pageThumbLinks = "";

    for ($pgIndex = 0; $pgIndex < $pageCount; $pgIndex++) {
        $imageBaseName = getBaseImageName($numeRevista, $an, $luna, $startPage + $pgIndex);
        $imageThumb = getImageThumbPath($imgDir, $imageBaseName);
        $destinationImage = getImagePath($imgDir, $imageBaseName);
        $pageThumbLinks .= getImageWithLink($imageThumb, $destinationImage, "minithumb")."  ";
    }
    return $pageThumbLinks;
}