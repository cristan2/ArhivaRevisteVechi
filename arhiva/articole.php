<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_html.php";

$editieId = $_GET["editie"];

/* --- info revista + pagina curenta --- */
$paginaCurentaNr = "1";
if (isset($_GET['pagina'])) $paginaCurentaNr = $_GET['pagina'];

$editieFromDb = $db->query("
    SELECT r.revista_nume, e.an, e.luna FROM editii e
    LEFT JOIN reviste r
    USING ('revista_id')
    WHERE e.editie_id = $editieId
");

$editie = $editieFromDb->fetchArray(SQLITE3_ASSOC);
$editie = array(
    'id'   => $editieId,
    "nume" => getColData($editie, "revista_nume"),
    "an"   => getColData($editie, "an"),
    "luna" => getColData($editie, "luna"),
);

$paginaCurentaImagePath = getImage($editie['nume'], $editie['an'], $editie['luna'], $paginaCurentaNr);

/* --- cuprins articole --- */
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
    "Pagini"    => function ($row) {return extractPagesForArticle(getColData($row, 'pg_toc'),
                                                                  getColData($row, 'pg_count'));}
    );

$tabelBody = buildRowsDinamic($toateArticolele, $tabelHead);


/* --- afisare in pagina --- */

include_once TEMPL . "/tpl_dual_div.php";


/* --- internals --- */

/**
 * Construieste thumbnails pagini cu linkuri catre imaginea mare
 * Returneaza un string cu html pentru afisare in tabel
 */
function extractPagesForArticle($startPage, $pageCount) {
    global $editie;
    $imgDir = getImageDir($editie['nume'], $editie['an'],$editie['luna']);

    $pageThumbLinks = "";

    for ($pgIndex = 0; $pgIndex < $pageCount; $pgIndex++) {
        $thisPageNo = $startPage + $pgIndex;
        $imageBaseName = getBaseImageName($editie['nume'],
                                            $editie['an'],
                                            $editie['luna'],
                                            $thisPageNo);
        $imageThumb = getImageThumbPath($imgDir, $imageBaseName);

        $destinationLink = getBaseUrl() . "?editie={$editie['id']}" . "&pagina=$thisPageNo";

        $pageThumbLinks .= getImageWithLink($imageThumb, $destinationLink, "minithumb")."  ";
    }
    return $pageThumbLinks;
}

function getPaginaCurentaImage($numeRevista, $an, $luna, $pagina) {
    return getImage($numeRevista, $an, $luna, $pagina);
}