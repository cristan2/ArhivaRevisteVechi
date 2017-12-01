<?php

DEFINE("ROOT", "..");
require_once("../resources/config.php");
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/h_misc.php";

// load classes
require_once LIB . "/Articol.php";
require_once LIB . "/Editie.php";
require_once HELPERS . "/HtmlPrinter.php";
use ArhivaRevisteVechi\lib\Editie;
use ArhivaRevisteVechi\lib\Articol;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;
use ArhivaRevisteVechi\resources\db\DBC;



if (isset($_GET["editie-id"])) {
    $editieId = $_GET["editie-id"];

    /* ------- info editia curenta ------- */
    $editiaCurenta = $db->getNextRow($db->queryEditie($editieId));
    $editiaCurenta = new Editie($editiaCurenta, Editie::EDITIE_FULL);

    // skip editiile fara numar (ex: Level 1998-05)
    if (!empty($editiaCurenta->numar))
        include_once ARHIVABITS . "/articole_bit_editia_curenta.php";

    // download links pentru editia curenta
    // vor veni 3 coloane - categorie, item si lista linkuri
    $dldLinksDbResult = $db->queryDownloadsDinEditie($editieId);
    $downloadLinks = array();
    while($currRow = $db->getNextRow($dldLinksDbResult)) {
        $categorie = $currRow[DBC::DLD_CATEG];
        $itemNo = $currRow[DBC::DLD_ITEM];
        // daca sunt mai multe linkuri pentru aceeasi categorie (revista sau cd),
        // vor veni intr-un singur string separate de virgula
        $downloadLinks[$categorie][$itemNo] = explode(",", $currRow[DBC::DLD_LINKS]);
    }
    $editiaCurenta->setDownloadLinks($downloadLinks);

    /* ------- cuprins articole ------- */
    $articoleDbResult = $db->queryArticoleDinEditie($editiaCurenta->editieId);

    $articoleArray = array();

    while ($dbRow = $db->getNextRow($articoleDbResult)) {
        $articol = new Articol($dbRow, $editiaCurenta);
        $articoleArray[] = $articol;
    }

} else {
    $numeRevista = $_GET[DBC::REV_NUME];
    $anEditie    = $_GET[DBC::ED_AN];
    $dirNo       = $_GET['editie'];
    $editiaCurenta = Editie::getEditieFromDisk($numeRevista, $anEditie, $dirNo, Editie::EDITIE_FULL);
}


if (!empty($articoleArray)) {
    $articoleCardRows = HtmlPrinter::buildDivContainer($articoleArray, array("col", "articol-card-container"));
} else {
    $articoleCardRows = "";
}

$currentPageTitle = $editiaCurenta->outputTitluCuNumeRevista();

/* ------- info pagina curenta ------- */
include_once ARHIVABITS . "/articole_bit_pagina_curenta.php";


/* ------- afisare in pagina ------- */
include_once HTMLLIB . "/view_dual.php";
