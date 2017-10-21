<?php
namespace ArhivaRevisteVechi\lib\helpers;

class HtmlPrinter {


    /**
     * Construieste un div container folosind div-uri din array
     * care pot proveni de la un Articol, Editie sau Revista
     */
    // elementele din $divArray pot fi de tip Articole sau Editie
    static function buildDivContainer($divArray, $containerCssClasses, $showSearchResult = false)
    {

        $extractHtmlOutput = function ($elem) use ($showSearchResult) {
            return $elem->getHtmlOutput($showSearchResult);
        };

        $htmlClasses = self::getCssClassList($containerCssClasses);
        $divRowsContainer = "<div $htmlClasses>" . PHP_EOL;
        $divRowsContainer .= implode(PHP_EOL, array_map($extractHtmlOutput, $divArray));
        $divRowsContainer .= "</div>" . PHP_EOL;
        return $divRowsContainer;
    }

    static function buildCardDiv($elementsArray, $cardCssClasses)
    {
        $card = "<div class = '$cardCssClasses'>" . PHP_EOL;
        $card .= implode("", $elementsArray);
        $card .= "</div>" . PHP_EOL;

        return $card;
    }

    static function getCssClassList($classList) {
        if (empty($classList)) return "";
        else return " class = '".implode(" ", $classList)."' ";
    }
}