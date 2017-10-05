<?php

// TODO refactor names

// TODO scoate html
/**
 * Construieste link html catre destinatia specificata
 */
function getImageWithLink($displayedImagePath, $targetLink, ...$htmlClasses) {
    if (! file_exists($displayedImagePath)) return "–";
    else {
        $htmlClassList = getClassList($htmlClasses);
        return "<a href='$targetLink'><img src='$displayedImagePath' $htmlClassList alt='Image' /></a>";
    }
}

/**
 * Construieste calea catre o imagine
 */
function getImage($editie, $pagina) {
    $imageDir      = getImageDir($editie->numeRevista, $editie->an, $editie->luna);
    $imageBaseName = getBaseImageName($editie->numeRevista, $editie->an, $editie->luna, $pagina);
    return _getImagePath($imageDir, $imageBaseName);
}

/**
 * Construieste calea catre o imagine
 * (ex img/level/1999/12/Level199912002.jpg)
 */
function _getImagePath($imageDir, $imageBaseName) {
    return $imageDir."/".$imageBaseName.".jpg";
}

/**
 * Construieste numele imaginii fara extensie
 * (ex: 'Level 1999 12 002' fara spatii)
 */
function getBaseImageName($numeRevista, $an, $luna, $pagina) {
    return strtolower($numeRevista)
            .$an
            .getPaddedMonth($luna)
            .getPaddedPage($pagina);
}

/**
 * Construieste calea catre directorul imaginilor unei reviste
 * (ex img/level/1999/12)
 */
function getImageDir($numeRevista, $an, $luna) {
    return IMG."/".strtolower($numeRevista)."/".$an."/".getPaddedMonth($luna);
}

/**
 * Construieste calea catre thumbnailul unei imagini
 * (ex img/level/1999/12/th/Level199912002_th.jpg)
 */
function getImageThumbPath($imageDir, $imageBaseName) {
    return $imageDir."/"."th"."/".$imageBaseName."_th.jpg";
}

/**
 * Construieste numele paginii cu 3 cifre
 * (ex: 3 -> 003, 24 -> 024))
 */
function getPaddedPage($pgNo) {
    return str_pad($pgNo, 3, '0', STR_PAD_LEFT);
}

/**
 * Construieste numele lunii cu 2 cifre
 * (ex: 2 -> 02)
 */
function getPaddedMonth($luna) {
    return str_pad($luna, 2, '0', STR_PAD_LEFT);
}