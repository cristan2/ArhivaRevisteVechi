<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_misc.php";

// TODO delete and decouple classes (editii.php - Editie.php - h_html.php - HtmlPrinter.php)
require_once HELPERS . "/h_html.php";

require_once HELPERS . "/HtmlPrinter.php";
require_once LIB . "/Revista.php";
require_once LIB . "/Editie.php";
require_once DB_DIR. "/DBC.php";
use ArhivaRevisteVechi\lib\Revista;
use ArhivaRevisteVechi\lib\Editie;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;

$revistaId = $_GET["revista"];

$editiiDbResult = $db->queryToateEditiile($revistaId);

$editiiArray = array();
while ($dbRow = $db->getNextRow($editiiDbResult)) {
    $editie = new Editie($dbRow /*, $revista*/);
    $editiiArray[] = $editie;
}

if (count($editiiArray) == 0) {

    // daca nu exista intrari in DB, folosim direct
    // imaginile salvate pe disc, daca exista
    $revistaDbResult = $db->getNextRow($db->queryRevista($revistaId));
    $revista = new Revista($revistaDbResult);
    $editiiArray = Editie::getEditiiArrayFrom($revista);
}

$pageContent = HtmlPrinter::buildDivContainer($editiiArray, array("card-container"));

include_once HTMLLIB . "/view_simple.php";
