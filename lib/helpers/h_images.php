<?php

define ("LUNA_PAD",   2);
define ("PAGINA_PAD", 3);

// TODO refactor names

// TODO scoate html
/**
 * Construieste link html catre destinatia specificata
 */
function getImageWithLink($displayedImagePath, $targetLink, ...$htmlClasses) {
    if (! file_exists($displayedImagePath)) return "�";
    else {
        $htmlClassList = getClassList($htmlClasses);
        return "<a href='$targetLink'><img src='$displayedImagePath' $htmlClassList alt='Image' /></a>";
    }
}

// TODO delete; imageDir exista in Editie, imageBaseName exista in Articol
/**
 * Construieste calea catre o imagine
 */
function getImage($editie, $pagina) {
    $imageDir      = buildImageDir($editie->numeRevista, $editie->an, $editie->luna);
    $imageBaseName = getBaseImageName($editie->numeRevista, $editie->an, $editie->luna, $pagina);
    return getImagePath($imageDir, $imageBaseName);
}

/**
 * Construieste calea catre o imagine
 * (ex img/level/1999/12/Level199912002.jpg)
 */
function getImagePath($imageDir, $imageBaseName) {
    return $imageDir."/".$imageBaseName.".jpg";
}

/**
 * Construieste numele imaginii fara extensie
 * (ex: 'Level 1999 12 002' fara spatii)
 */
function getBaseImageName($numeRevista, $an, $luna, $pagina) {
    return strtolower($numeRevista)
            .$an
            .padLeft($luna, LUNA_PAD)
            .padLeft($pagina, PAGINA_PAD);
}

// TODO delete (exista deja in Editii.php)
/**
 * Construieste calea catre directorul imaginilor unei reviste
 * (ex img/level/1999/12)
 */
function buildImageDir($numeRevista, $an, $luna) {
    return  IMG .DIRECTORY_SEPARATOR
            .strtolower($numeRevista).DIRECTORY_SEPARATOR
            .$an. DIRECTORY_SEPARATOR
            .padLeft($luna, LUNA_PAD);
}

/**
 * Construieste calea catre thumbnailul unei imagini
 * (ex img/level/1999/12/th/Level199912002_th.jpg)
 */
function getImageThumbPath($imageDir, $imageBaseName) {
    return $imageDir."/"."th"."/".$imageBaseName."_th.jpg";
}

/**
 * Adauga padding la numarul lunii sau paginii
 * (ex luna: 2 -> 02)
 * (ex pagina: 3 -> 003, 24 -> 024)
 */
function padLeft($targetNo, $padLength)
{
    return str_pad($targetNo, $padLength, '0', STR_PAD_LEFT);
}