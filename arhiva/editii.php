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

$editiiArray = array();
while ($dbRow = $db->getNextRow($editiiDbResult)) {
    $editie = new Editie($dbRow /*, $revista*/);
    $editiiArray[] = $editie;
}

$pageContent = buildDivRowsFromArray($editiiArray, array("card-container"));

include_once HTMLLIB . "/view_simple.php";