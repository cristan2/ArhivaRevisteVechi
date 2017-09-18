<?php

require_once(RESOURCES . "/config.php");
require_once LIB . "/helper_tables.php";

$toaterevistele = $db->query("
  SELECT r.*, COUNT(e.editie_id) cnt
  FROM reviste r
  LEFT JOIN editii e
  USING (revista_id)
  GROUP BY r.revista_id
  ");

$tabelHead = array(
    "Nume" => function($row) {return getColData($row, 'revista_nume');},
    "Editii Arhiva" => function($row) {return getColData($row, 'cnt');},
    "Coperta" => function ($row) {return makeImgUrl(getNumeFisier(getColData($row, 'revista_nume')),
        getColData($row, 'revista_id'));}
);

$tabelBody = buildRowsDinamic($toaterevistele, $tabelHead);

include_once TEMPL . "/tpl_tabel.php";

function getNumeFisier($numeRevista) {
    $simpleName = preg_replace('/[^a-z0-9 ]+/', "", strtolower($numeRevista));
    echo "transformed $numeRevista => $simpleName" . "<br>";
    $imgDirPath = IMG . "/coperti/$simpleName.jpg";
    if (file_exists($imgDirPath)) {
        return $imgDirPath;
    } else return IMG . "/coperti/default.jpg";
}

function makeImgUrl($imagePath, $revistaId) {
    $targetLink = ARHIVA . "/editii.php" . "?revista=$revistaId";
    return "<a href=\"$targetLink\"><img src=\"$imagePath\" alt=\"Image\" style = \"max-width:20%\"/></a>";
}