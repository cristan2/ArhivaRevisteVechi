<?php

DEFINE("ROOT", "..");
require_once("../resources/config.php");
require_once HELPERS . "/h_tables.php";
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/h_misc.php";

// load classes
require_once LIB . "/Articol.php";
require_once LIB . "/Editie.php";
use ArhivaRevisteVechi\lib\Articol;

$editieId = $_GET["editie"];


/* --- info editia curenta --- */
include_once "articole_bit_editia_curenta.php";


/* --- info pagina curenta --- */
include_once "articole_bit_pagina_curenta.php";


/* --- cuprins articole --- */
$articoleDbResult = $db->queryArticoleDinEditie($editiaCurenta->editieId);


$articoleArray = array();

while ($dbRow = $db->getNextRow($articoleDbResult)) {
    $articol = new Articol($dbRow, $editiaCurenta);
    $articoleArray[] = $articol->getHtmlOutput();
}

$articoleCardRows = buildDivRows($articoleArray, "articol-card-container");


/* --- afisare in pagina --- */
include_once HTMLLIB . "/view_dual.php";
