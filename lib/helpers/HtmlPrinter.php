<?php
namespace ArhivaRevisteVechi\lib\helpers;

class HtmlPrinter {


    /**
     * Construieste un div container folosind div-uri din array
     * care pot proveni de la un Articol, Editie sau Revista
     */
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

    /**
     * Construieste un tabel html pentru afisarea statusului arhivei
     */
    static function buildHtmlTableFromArray($arrToDisplay) {

        $rezultatFinal = "";

        foreach ($arrToDisplay as $numeRevistaCurenta => $arrRevistaCurenta) {

            $rezultatFinal .= "<h2>$numeRevistaCurenta</h2>" . "<br>";

            // HEADER TABEL ----------
            $headerTabel = "<tr>";
            $arrKeys = array_keys($arrRevistaCurenta[0]);
            foreach($arrKeys as $key) {
                // skip An sa nu se repete, pentru ca l-am adaugat pe randul-intertitlu
                if ($key === 'An') continue;
                $headerTabel .= "<th>$key</th>";
            }
            $headerTabel .= "</tr>";


            // RANDURI TABEL ----------
            $randuriTabel = "";
            $anulCurent = "";
            foreach ($arrRevistaCurenta as $arrRow) {
                // adauga un rand gol intre ani
                if ($arrRow['An'] != $anulCurent) {
                    $randuriTabel .= '<tr class = "empty-row"><td colspan="' . count($arrKeys) . '">' . $arrRow['An'] . '</td></tr>' . PHP_EOL;
                    $anulCurent = $arrRow['An'];
                }

                // construieste randul
                $htmlRow = "<tr>";
                foreach($arrKeys as $key) {
                    // skip An sa nu se repete, pentru ca l-am adaugat pe randul-intertitlu
                    if ($key === 'An') continue;
                    $htmlRow .= "<td>$arrRow[$key]</td>";
                }
                $htmlRow .= "</tr>";

                // adauga randul in lista de randuri
                $randuriTabel .= $htmlRow . PHP_EOL;
            }

            // TABEL FINAL ----------
            $tabel = "<div><table>" . PHP_EOL;
            $tabel .= $headerTabel . PHP_EOL;
            $tabel .= $randuriTabel . PHP_EOL;
            $tabel .= "</table></div>" . PHP_EOL;
            $rezultatFinal .= $tabel;
        }
        return $rezultatFinal;
    }

    /**
     * Construieste un tabel cu sintaxa DokuWiki pentru afisarea statusului arhivei
     */
    static function buildDokuWikiTableFromArray($arrToDisplay) {

        $rezultatFinal = "<pre>";

        foreach ($arrToDisplay as $numeRevistaCurenta => $arrRevistaCurenta) {

            $rezultatFinal .= "===== $numeRevistaCurenta =====" . "<br>";

            // HEADER TABEL ----------
            $headerTabel = "^";
            $arrKeys = array_keys($arrRevistaCurenta[0]);
            foreach ($arrKeys as $key) {
                if ($key === 'An') continue;
                $headerTabel .= "&nbsp;&nbsp;$key&nbsp;&nbsp;^";
            }
//            $headerTabel .= "<br>";

            // RANDURI TABEL ----------
            $randuriTabel = "";
            $anulCurent = '';
            foreach ($arrRevistaCurenta as $arrRow) {
                // adauga un rand gol intre ani
                if ($arrRow['An'] != $anulCurent) {
                    $randuriTabel .= '^' . $arrRow['An'] . str_repeat('^', count($arrKeys)-1);
                    $anulCurent = $arrRow['An'];
                }

                // construieste randul
                $dokuRow = "|";
                foreach ($arrKeys as $key) {
                    if ($key === 'An') continue;
                    $dokuRow .= "&nbsp;&nbsp;$arrRow[$key]&nbsp;&nbsp;|";
                }
//                $dokuRow .= "<br>";

                // adauga randul in lista de randuri
                $randuriTabel .= $dokuRow . PHP_EOL;
            }

            // TABEL FINAL ----------
            $tabel = "";
            $tabel .= $headerTabel . PHP_EOL;
            $tabel .= $randuriTabel . PHP_EOL;

            $rezultatFinal .= $tabel . "</pre>";
        }
        return $rezultatFinal;
    }

    static function wrapLink($displayedElement, $targetLink)
    {
        return "<a href='$targetLink'>$displayedElement</a>";
    }

    static function wrapImg($imagePath, $cssClass, $altDescription = 'Image')
    {
        return "<img src='$imagePath' $cssClass alt='$altDescription' />";
    }
}