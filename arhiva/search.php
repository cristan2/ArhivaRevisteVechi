<?php

DEFINE("ROOT", "..");
require("../resources/config.php");
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/h_misc.php";

$urlWithParams = $_SERVER['REQUEST_URI'];
$paramsRaw = parse_url($urlWithParams, PHP_URL_QUERY);


// add simple search (always show)
$simpleSearchContent = buildHtmlSimpleSearch();

if (empty($paramsRaw)) {

    // add quick search
    $quickSearchContent = buildHtmlPresetSearch();

    // add custom search
    // TODO implement

} else {
    parse_str($paramsRaw, $params);
    $pageContent = processSearchRequest($params);
}


/* --- afisare in pagina --- */
include_once HTMLLIB . "/view_simple.php";


/* --- internals --- */

function buildHtmlSimpleSearch()
{
    $searchPage = ARHIVA . "/search.php";
    return <<<START_HTML
	<form action = "$searchPage">
		 <input type = "text" name = "filter" />
         <input type = "submit" value = "Cauta" />
      </form>

START_HTML;

}

function buildHtmlPresetSearch()
{
    return '<a href = "?type=quick-search&target=scan-status">Scan status</a>'
        . ' (format <a href = "?type=quick-search&target=scan-status&option=doku">DokuWiki</a>?)';
}

function processSearchRequest($params)
{
    $searchType = !empty($params['type']) ? $params['type'] : "";
    switch($searchType)
    {
        case "quick-search":
            include ARHIVABITS . "/search_bit_preset_search.php";
            return performQuickSearch($params);

        default:
            include ARHIVABITS . "/search_bit_simple_search.php";
            return performSimpleSearch($params);
    }
}

