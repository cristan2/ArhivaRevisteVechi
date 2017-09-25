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
    if ($editieId <= 1) $editieId = 1;
    return ARHIVA."/articole.php?editie=$editieId";
}