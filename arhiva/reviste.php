<?php
// ROOT si config.php sunt definite de index.php
// TODO: de comentat ROOT si config.php cand dezactivezi
// redirectul din index.php
//if (!defined('ROOT')) DEFINE("ROOT", "..");
//require_once("../resources/config.php");

require_once HELPERS . "/h_html.php";

require_once LIB     . "/Revista.php";
require_once HELPERS . "/HtmlPrinter.php";
use ArhivaRevisteVechi\lib\Revista;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;

$revisteDbResult = $db->queryToateRevistele(REVISTE_READY_FOR_PROD);

$revisteArray = array();
while($dbRow = $db->getNextRow($revisteDbResult)) {
    $revista = new Revista($dbRow);
    $revisteArray[] = $revista;
}

$pageContent = HtmlPrinter::buildDivContainer($revisteArray, array("card-container"));

include_once HTMLLIB . "/view_simple.php";
