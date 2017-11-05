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

function convertLunaToNumăr($lunaLitere) {
    switch (strtolower(trim($lunaLitere))) {
        case "ianuarie"  :  return 1;
        case "februarie" :  return 2;
        case "martie"    :  return 3;
        case "aprilie"   :  return 4;
        case "mai"       :  return 5;
        case "iunie"     :  return 6;
        case "iulie"     :  return 7;
        case "august"    :  return 8;
        case "septembrie":  return 9;
        case "octombrie" :  return 10;
        case "noiembrie" :  return 11;
        case "decembrie" :  return 12;
    }
    return $lunaLitere;
}

function convertTipPublicatie($tip)
{
    switch($tip) {
        case "revista"   : return "";
        case "supliment" : return " (suplim.)";
    }
}

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function contains($haystack, $needle)
{
    return stripos($haystack, $needle) !== false;
}

function cleanPrefixFromName($dirName, $prefixArray)
{
    foreach ($prefixArray as $prefix) {
        if (startsWith($dirName, $prefix)) {
            return substr($dirName, strlen($prefix));
        }
    }
    return $dirName;
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

define ("LUNA_PAD",   2);
define ("ISSUE_PAD",  3);
define ("PAGINA_PAD", 3);

/**
 * Adauga padding la numarul lunii sau paginii
 * (ex luna: 2 -> 02)
 * (ex pagina: 3 -> 003, 24 -> 024)
 */
function padLeft($targetNo, $padLength)
{
    $res = str_pad($targetNo, $padLength, '0', STR_PAD_LEFT);
    return $res;
}

/**
 * Verifica linkurile de download extrase din DB si
 * returneaza un nume pentru a fi folosit in link
 * ex: https://www.scribd.com/doc/16826391 => "Scribd"
 */
function extractKnownLinkName($link)
{
    if (contains($link, "archive")) return FILE_HOST_NAME_ARCHIVEORG;
    else if (contains($link, "scribd")) return FILE_HOST_NAME_SCRIBD;
    else return "Link";
}