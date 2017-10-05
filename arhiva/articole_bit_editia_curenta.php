<?php
use ArhivaRevisteVechi\resources\db\DBC;
use ArhivaRevisteVechi\lib\Editie;

$editiaCurenta = $db->getFirstRowFromResult($db->getEditie($editieId));
$editiaCurenta = new Editie($editiaCurenta);

// next & prev links
// TODO: trebuie sa existe si un max(editie_id) pentru disable la navLinkNext
$prevEditieId = $db->getFirstRowFromResult($db->getEditieIdFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) - 1));
$navLinkPrev = getEditieUrl($prevEditieId[DBC::ED_ID]);

$nextEditieId = $db->getFirstRowFromResult($db->getEditieIdFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) + 1));
$navLinkNext = getEditieUrl($nextEditieId[DBC::ED_ID]);

$titluEditieCurenta = "{$editiaCurenta->numeRevista} nr. {$editiaCurenta->numar}";
$lunaEditieCurenta = "(". convertLuna($editiaCurenta->luna) ." {$editiaCurenta->an})";