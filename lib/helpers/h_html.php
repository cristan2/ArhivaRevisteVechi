<?php

function getBaseUrl() {
    $urlWithParams = $_SERVER['REQUEST_URI'];
    $urlParts = explode("?", $urlWithParams, 2);
    return $urlParts[0];
}

function getClassList($classList) {
    if (empty($classList)) return "";
    else return " class = '".implode(" ", $classList)."' ";
}

function getEditieUrl($editieId) {
    return ARHIVA."/articole.php?editie=$editieId";
}

function wrapDiv($elementValue, ...$htmlClasses)
{
    $htmlClassList = getClassList($htmlClasses);
    return "<div $htmlClassList>$elementValue</div>";
}