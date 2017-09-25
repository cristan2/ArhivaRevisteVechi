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
    SELECT r.revista_nume, e.an, e.luna, e.numar FROM editii e
    LEFT JOIN reviste r
    USING ('revista_id')
    WHERE e.editie_id = $editieId
");

$editiaCurenta = $editieFromDb->fetchArray(SQLITE3_ASSOC);
$editiaCurenta = array(
    'id'    => $editieId,
    "nume"  => getColData($editiaCurenta, "revista_nume"),
    "an"    => getColData($editiaCurenta, "an"),
    "luna"  => getColData($editiaCurenta, "luna"),
    "numar" => getColData($editiaCurenta, "numar"),
);

$paginaCurentaImagePath = getImage($editiaCurenta['nume'], $editiaCurenta['an'], $editiaCurenta['luna'], $paginaCurentaNr);

$titluEditieCurenta = "{$editiaCurenta['nume']} nr. {$editiaCurenta['numar']}";
$lunaEditieCurenta = "(". convertLuna($editiaCurenta['luna']) ." {$editiaCurenta['an']})";

// TODO: redo, nu e ok, id-urile editiilor nu sunt neaparat consecutive
$navLinkNext = getEditieUrl($editieId+1);
$navLinkPrev = getEditieUrl($editieId-1);

/* --- cuprins articole --- */
$articoleDbResult = $db->query("
    SELECT a.*, e.an, e.luna
    FROM articole a
    LEFT JOIN editii e
    USING (editie_id)
    WHERE editie_id = $editieId
");

$articoleCardRecipe = array(
    "pagina"        => function ($row) {return getColData($row, "pg_toc");},
    "rubrica"       => function ($row) {return getColData($row, "rubrica");},
    "titlu"         => function ($row) {return getColData($row, "titlu");},
    "autor"         => function ($row) {return getColData($row, "autor");},
    "pagini-count"  => function ($row) {return extractThumbPages(getColData($row, 'pg_toc'),
                                                                  getColData($row, 'pg_count'));}
    );

$articoleCardRows = buildCardRows($articoleDbResult, $articoleCardRecipe);


/* --- afisare in pagina --- */

include_once HTMLLIB . "/tpl_dual_div.php";


/* --- internals --- */

/**
 * Construieste thumbnails pagini cu linkuri catre imaginea mare
 * Returneaza un string cu html pentru afisare in tabel
 */
function extractThumbPages($startPage, $pageCount) {
    global $editiaCurenta;
    $imgDir = getImageDir($editiaCurenta['nume'], $editiaCurenta['an'],$editiaCurenta['luna']);

    $pageThumbLinks = "";

    for ($pgIndex = 0; $pgIndex < $pageCount; $pgIndex++) {
        $thisPageNo = $startPage + $pgIndex;
        $imageBaseName = getBaseImageName($editiaCurenta['nume'],
                                            $editiaCurenta['an'],
                                            $editiaCurenta['luna'],
                                            $thisPageNo);
        $imageThumb = getImageThumbPath($imgDir, $imageBaseName);

        $destinationLink = getBaseUrl() . "?editie={$editiaCurenta['id']}" . "&pagina=$thisPageNo";

        $pageThumbLinks .= getImageWithLink($imageThumb, $destinationLink, "minithumb")."  ";
    }
    return $pageThumbLinks;
}

function getPaginaCurentaImage($numeRevista, $an, $luna, $pagina) {
    return getImage($numeRevista, $an, $luna, $pagina);
}

// TODO extract to helper
function convertLuna($lunaNumar) {
    switch ($lunaNumar) {
        case 1:  return "ianuarie";
        case 2:  return "februarie";
        case 3:  return "martie";
        case 4:  return "aprilie";
        case 5:  return "mai";
        case 6:  return "iunie";
        case 7:  return "iulie";
        case 8:  return "august";
        case 9:  return "septembrie";
        case 10: return "octombrie";
        case 11: return "noiembrie";
        case 12: return "decembrie";
    }
    return $lunaNumar;
}