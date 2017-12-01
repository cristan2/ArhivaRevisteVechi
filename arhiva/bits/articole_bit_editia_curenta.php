<?php
use ArhivaRevisteVechi\lib\Editie;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;
use ArhivaRevisteVechi\resources\db\DBC;

// link catre editia precedenta
// TODO provision for editii fara id, dar care au numar
if ($editiaCurenta->numar > 1) {
    $prevEditieDbResult = $db->getNextRow(
        $db->queryEditieFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) - 1));
    $prevEditie = new Editie($prevEditieDbResult, Editie::EDITIE_PREVIEW);
    $prevEditieUrl = $prevEditie->getEditieUrl();

    $prevEditieLink = HtmlPrinter::wrapLink("<<< Prev", $prevEditieUrl);
}

// link catre editia urmatoare
// TODO: de inlocuit cu atribut din Revista-parinte a editiei
$maxEditieDbResult = $db->getNextRow($db->queryMaxEditiiDinRevista($editiaCurenta->revistaId));
$maxEditie = $maxEditieDbResult[DBC::REV_MAX_ED];

if ($editiaCurenta->numar < $maxEditie) {
    // TODO provision for editii fara id, dar care au numar
    $nextEditieDbResult = $db->getNextRow(
        $db->queryEditieFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) + 1));
    $nextEditie = new Editie($nextEditieDbResult, Editie::EDITIE_PREVIEW);
    $nextEditieUrl = $nextEditie->getEditieUrl();

    $nextEditieLink = HtmlPrinter::wrapLink("Next >>>", $nextEditieUrl);;
}