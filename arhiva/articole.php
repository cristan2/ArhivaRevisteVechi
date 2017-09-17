<?php
require("../resources/config.php");
require_once LIB . "/helper_tables.php";

if (isset($db)) echo "db is set";
else echo "is not set";

$editieId = $_GET["editie"];

echo "editie = $editieId";

$toateArticolele = $db->query("
    SELECT * FROM editii
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