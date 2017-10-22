<?php

function convertLuna($lunaNumar) {
    switch ($lunaNumar) {
        case 1:  return "ianuarie";
        case 2:  return "februarie";
        case 3:  return "martie";
        case 4:  return "aprilie";
        case 5:  return "mai";
        case 6:  return "iunie";
        case 7:  return "iulie";
        case 8:  return "august";
        case 9:  return "septembrie";
        case 10: return "octombrie";
        case 11: return "noiembrie";
        case 12: return "decembrie";
    }
    return $lunaNumar;
}

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function getImageFilesInDir($dirPath)
{
    /**
     * // https://stackoverflow.com/questions/12801370/count-how-many-files-in-directory-php
     */
    // glob($directory . "*.{jpg,png,gif}",GLOB_BRACE)
    // the GLOB_BRACE flag expands {a,b,c} to match 'a', 'b', or 'c'
    return glob($dirPath . "/" . "*.{jpg,jpeg,png}", GLOB_BRACE);
}

function getDirsInPath($dirPath)
{
    return glob($dirPath . "/" . "*", GLOB_ONLYDIR);
}