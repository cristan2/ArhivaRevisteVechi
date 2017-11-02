<?php
use ArhivaRevisteVechi\lib\Editie;

// previous Editie
// TODO provision for editii fara id, dar care au numar
$prevEditieDbResult = $db->getNextRow(
    $db->queryEditieFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) - 1));
$prevEditie = new Editie($prevEditieDbResult, Editie::EDITIE_PREVIEW);
$prevEditieLink = $prevEditie->getEditieUrl();

// next Editie
// TODO: trebuie sa existe si un max(editie_id) pentru disable la navLinkNext
// TODO provision for editii fara id, dar care au numar
$nextEditieDbResult = $db->getNextRow(
    $db->queryEditieFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) + 1));
$nextEditie = new Editie($nextEditieDbResult, Editie::EDITIE_PREVIEW);
$nextEditieLink = $nextEditie->getEditieUrl();
