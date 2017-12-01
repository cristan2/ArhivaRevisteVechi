<?php

DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/h_misc.php";

$urlWithParams = $_SERVER['REQUEST_URI'];
$paramsRaw = parse_url($urlWithParams, PHP_URL_QUERY);

/* if true, va ascunde search box din main header, util in cazul
   in care vrem sa afisam alt search box aici in pagina */
$suppresMainHeaderSearch = false;

if (empty($paramsRaw)) {

    $currentPageTitle = "Căutare";

    // add preset search
    $presetSearchContent = buildHtmlPresetSearch();

    // add custom search
    // TODO implement

} else {

    $currentPageTitle = "Rezultat căutare";

    parse_str($paramsRaw, $params);
    $pageContent = processSearchRequest($params, $db);
}


/* --- afisare in pagina --- */
include_once HTMLLIB . "/view_simple.php";


/* --- internals --- */

function buildHtmlPresetSearch()
{
    return '<a href = "?type=preset-search&target=scan-status">Scan status</a>'
        . ' (format <a href = "?type=preset-search&target=scan-status&option=doku">DokuWiki</a>?)';
}

function processSearchRequest($params, $db)
{
    $searchType = !empty($params['type']) ? $params['type'] : "";
    switch($searchType)
    {
        case "preset-search":
            include ARHIVABITS . "/search_bit_preset_search.php";
            return performPresetSearch($params, $db);

        default:
            include ARHIVABITS . "/search_bit_simple_search.php";
            return performSimpleSearch($params, $db);
    }
}

