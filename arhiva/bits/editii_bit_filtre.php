<?php
use ArhivaRevisteVechi\lib\Revista;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;
use ArhivaRevisteVechi\resources\db\DBC;

// TODO

$listaAniDbResult = $db->queryAniEditii($revistaId);
$listaAni = $db->getSingleArrayFromDbResult($listaAniDbResult, DBC::ED_AN);

if (!empty($listaAni) && count($listaAni) > 1) {
    $dropDownFiltruAni = HtmlPrinter::buildDropdownFromArray($listaAni, $revista->getRevistaUrl(), "Filtru ani");
}