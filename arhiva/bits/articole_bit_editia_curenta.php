<?php
use ArhivaRevisteVechi\resources\db\DBC;

// next & prev links
// TODO: trebuie sa existe si un max(editie_id) pentru disable la navLinkNext
$prevEditieId = $db->getNextRow($db->queryEditieIdFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) - 1));
$navLinkPrev = getEditieUrl($prevEditieId[DBC::ED_ID]);

$nextEditieId = $db->getNextRow($db->queryEditieIdFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) + 1));
$navLinkNext = getEditieUrl($nextEditieId[DBC::ED_ID]);

$titluEditieCurenta = "{$editiaCurenta->numeRevista} nr. {$editiaCurenta->numar}";
$lunaEditieCurenta = "(". convertLuna($editiaCurenta->luna) ." {$editiaCurenta->an})";