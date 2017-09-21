<?php

// TODO scoate html
/**
 * returneaza link html catre destinatia specificata
 */
function getImageWithLink($displayedImagePath, $targetLink, ...$htmlClasses) {
    if (! file_exists($displayedImagePath)) return "–" /*"n/a"*/ ;
    else {
        $htmlClassList = "";
        if (!empty($htmlClasses)) $htmlClassList = " class = '".implode(" ", $htmlClasses)."''";
        return "<a href='$targetLink'><img src='$displayedImagePath' $htmlClassList alt='Image' /></a>";
    }

}

/**
 * returneaza numele imaginii fara extensie
 * (ex: 'Level 1999 12 002' fara spatii)
 */
function getBaseImageName($numeRevista, $an, $luna, $pagina) {
    return strtolower($numeRevista)
            .$an
            .getPaddedMonth($luna)
            .getPaddedPage($pagina);
}

/**
 * returneaza calea catre directorul imaginilor unei reviste
 * (ex img/level/1999/12)
 */
function getImageDir($numeRevista, $an, $luna) {
    return IMG."/".strtolower($numeRevista)."/".$an."/".getPaddedMonth($luna);
}

/**
 * returneaza calea catre o imagin
 * (ex img/level/1999/12/Level199912002.jpg)
 */
function getImagePath($imageDir, $imageBaseName) {
    return $imageDir."/".$imageBaseName.".jpg";
}

/**
 * returneaza calea catre thumbnailul unei imagini
 * (ex img/level/1999/12/th/Level199912002_th.jpg)
 */
function getImageThumbPath($imageDir, $imageBaseName) {
    return $imageDir."/"."th"."/".$imageBaseName."_th.jpg";
}

/**
 * returneaza numele paginii cu 3 cifre
 * (ex: 3 -> 003, 24 -> 024))
 */
function getPaddedPage($pgNo) {
    return str_pad($pgNo, 3, '0', STR_PAD_LEFT);
}

/**
 * returneaza numele lunii cu 2 cifre
 * (ex: 2 -> 02)
 */
function getPaddedMonth($luna) {
    return str_pad($luna, 2, '0', STR_PAD_LEFT);
}