<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_misc.php";

// TODO delete and decouple classes (editii.php - Editie.php - h_html.php - HtmlPrinter.php)
require_once HELPERS . "/h_html.php";

require_once HELPERS . "/HtmlPrinter.php";
require_once LIB . "/Revista.php";
require_once LIB . "/Editie.php";
require_once DB_DIR. "/DBC.php";
use ArhivaRevisteVechi\resources\db\DBC;
use ArhivaRevisteVechi\lib\Revista;
use ArhivaRevisteVechi\lib\Editie;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;


if (isset($_GET["revista"])) {
    $revistaId = intval($_GET["revista"]);
} else {
    $pageContent = "Nu trebuia să faci asta. Vor fi consecințe...";
};

$filtruAn = '';
if (isset($_GET["an"])) {
    $an = $_GET["an"];
    if (is_numeric($an)) $filtruAn = $an;
}

if (!empty($revistaId)) {

    /* ------- simple cache pentru lista de editii ------- */
    // (http://wesbos.com/simple-php-page-caching-technique/)
    $cachefile = ROOT . "/cache/editii_$revistaId"
                . (!empty($revistaId) ? "-$filtruAn" : "")
                    ."_".date('M-d-Y').'.php';
    $cachetime = 86400; // 24 ore

    // get cache if exists / is not old
    if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
        include($cachefile);
        exit;
    }
    // else genereaza pagina normal
    ob_start();

   /* ------- info revista curenta ------- */
    $revistaDbResult = $db->getNextRow($db->queryRevista($revistaId));
    $revista = new Revista($revistaDbResult);

    /* ------- info editii ------- */
    $editiiDbResult = $db->queryToateEditiile($revistaId, $filtruAn);

    $editiiArray = array();
    while ($dbRow = $db->getNextRow($editiiDbResult)) {
        $editie = new Editie($dbRow, Editie::EDITIE_PREVIEW /*, $revista*/);
        $editiiArray[] = $editie;
    }

    // daca nu exista intrari in DB, folosim direct
    // imaginile salvate pe disc, daca exista
    if (count($editiiArray) == 0) {
        $editiiArray = Editie::getEditiiArrayFromNumeRevista($revista);
    }

    $currentPageTitle = $revista->numeRevista;
    $pageContent = HtmlPrinter::buildDivContainer($editiiArray, array("card-container"));

    include_once ARHIVABITS . "/editii_bit_filtre.php";
}

include_once HTMLLIB . "/view_simple.php";

/* ------- save cache ------- */

if (!empty($revistaId)) {

    $cachefile = ROOT . "/cache/editii_$revistaId"
                . (!empty($revistaId) ? "-$filtruAn" : "")
                ."_".date('M-d-Y').'.php';

    $fp = fopen($cachefile, 'w');
    fwrite($fp, ob_get_contents());
    fclose($fp);

    // get browser output
    ob_end_flush();
}