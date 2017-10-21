<?php

DEFINE("ROOT", "..");
require_once("../resources/config.php");
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/h_misc.php";

// load classes
require_once LIB . "/Articol.php";
require_once LIB . "/Editie.php";
require_once HELPERS . "/HtmlPrinter.php";
use ArhivaRevisteVechi\lib\Editie;
use ArhivaRevisteVechi\lib\Articol;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;

$editieId = $_GET["editie"];


/* ------- info editia curenta ------- */
$editiaCurenta = $db->getNextRow($db->queryEditie($editieId));
$editiaCurenta = new Editie($editiaCurenta);
include_once ARHIVABITS . "/articole_bit_editia_curenta.php";


/* ------- cuprins articole ------- */
$articoleDbResult = $db->queryArticoleDinEditie($editiaCurenta->editieId);

$articoleArray = array();

while ($dbRow = $db->getNextRow($articoleDbResult)) {
    $articol = new Articol($dbRow, $editiaCurenta);
    $articoleArray[] = $articol;
}

$articoleCardRows = HtmlPrinter::buildDivContainer($articoleArray, array("articol-card-container"));


/* ------- info pagina curenta ------- */
include_once ARHIVABITS . "/articole_bit_pagina_curenta.php";


/* ------- afisare in pagina ------- */
include_once HTMLLIB . "/view_dual.php";


function getEditieUrl($editieId) {
    return ARHIVA."/articole.php?editie=$editieId";
}