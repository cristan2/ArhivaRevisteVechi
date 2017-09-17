<?php
DEFINE("ROOT", "..");
require("../resources/config.php");
require_once LIB . "/helper_tables.php";

$editieId = $_GET["editie"];

$toateArticolele = $db->query("
    SELECT * FROM articole
    WHERE editie_id = $editieId
");

$tabelHead = array(

    "Pg."       => function ($row) {return getColData($row, "pg_toc");},
    "Rubrica."  => function ($row) {return getColData($row, "rubrica");},
    "Titlu."    => function ($row) {return getColData($row, "titlu");},
    "Autor."    => function ($row) {return getColData($row, "autor");}
);

$tabelBody = buildRowsDinamic($toateArticolele, $tabelHead);

include_once TEMPL . "/tpl_tabel.php";