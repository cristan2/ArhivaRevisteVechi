<?php
use ArhivaRevisteVechi\lib\Editie;

// previous Editie
$prevEditieDbResult = $db->getNextRow(
    $db->queryEditieFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) - 1));
$prevEditie = new Editie($prevEditieDbResult);
$prevEditieLink = $prevEditie->getEditieUrl();

// next Editie
// TODO: trebuie sa existe si un max(editie_id) pentru disable la navLinkNext
$nextEditieDbResult = $db->getNextRow(
    $db->queryEditieFromNumar($editiaCurenta->revistaId, ($editiaCurenta->numar) + 1));
$nextEditie = new Editie($nextEditieDbResult);
$nextEditieLink = $nextEditie->getEditieUrl();
