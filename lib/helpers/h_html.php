<?php

function getBaseUrl() {
    $urlWithParams = $_SERVER['REQUEST_URI'];
    $urlParts = explode("?", $urlWithParams, 2);
    return $urlParts[0];
}

function getCssClassList($classList) {
    if (empty($classList)) return "";
    else if (!is_array($classList)) return " class = '$classList' ";
    else return " class = '".implode(" ", $classList)."' ";
}

function wrapDiv($elementValue, ...$htmlClasses)
{
    $htmlClassList = getCssClassList($htmlClasses);
    return "<div $htmlClassList>$elementValue</div>";
}