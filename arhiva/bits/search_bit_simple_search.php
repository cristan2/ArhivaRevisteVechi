<?php

require_once LIB . "/Articol.php";
require_once LIB . "/Editie.php";
require_once DB_DIR. "/DBC.php";
require_once HELPERS . "/HtmlPrinter.php";
use ArhivaRevisteVechi\lib\Articol;
use ArhivaRevisteVechi\lib\Editie;
use ArhivaRevisteVechi\resources\db\DBC;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;

// TODO clean search filter
function performSimpleSearch($params, $db)
{
    if (empty($params['filter'])) return "Oare dacă cauţi nimic e ca şi cum ai împărţi la zero?";
    else {
        $dbResult = specialQuerySimpleSearch($db, $params['filter']);
        $processedResult = processSimpleSearchDbResult($db, $dbResult);
        $output = "";

        foreach($processedResult as $categ => $results ) {
            $numarRezultate = count($results['divArray']);

            $output .= "<h1>$categ</h1>";
            $output .= "<h2>($numarRezultate rezultate)</h2>";

            if ($numarRezultate > 0) {

                // hint cautare
                if ($categ == "Ediții") {
                    $output .= "<p>(întoarce rezultate pentru 'an', 'lună', 'joc complet')";
                } else if ($categ == "Articole") {
                    $output .= "<p>(rubrică, titlu articol, platformă joc, autor)</p>";
                }

                $output .= HtmlPrinter::buildDivContainer($results['divArray'], $results['divClasses'], true);
            }
        }
        return $output;
    }
}

function specialQuerySimpleSearch($db, $searchFilter)
{
    $searchFilter = strtolower($searchFilter);
    $queryArticole = "
        SELECT e.*, a.*
        FROM articole a
        LEFT JOIN editii e USING ('editie_id')
        WHERE lower(a.rubrica)  LIKE '%$searchFilter%'
        OR lower(a.titlu)  LIKE '%$searchFilter%'
        OR lower(a.joc_platforma)  LIKE '%$searchFilter%'
        OR lower(a.autor)  LIKE '%$searchFilter%'
    ";

    $lunaConvertita = searchLunaNumeric($searchFilter);

    $queryEditii = "
        SELECT e.*, r.*
        FROM editii e
        LEFT JOIN reviste r USING ('revista_id')
        WHERE lower(e.an)  LIKE '$searchFilter'
        OR lower(e.luna)  LIKE '$lunaConvertita'
        OR lower(e.joc_complet)  LIKE '%$searchFilter%'
    ";

    $rezultatDbArticole = $db->directQuery($queryArticole);
    $rezultatDbEditii   = $db->directQuery($queryEditii);

    return array(
        "rezultatEditii"   => $rezultatDbEditii,
        "rezultatArticole" => $rezultatDbArticole);
}

function processSimpleSearchDbResult($db, $dbResultTuple)
{
    $articoleDbResult = $dbResultTuple['rezultatArticole'];
    $editiiDbResult = $dbResultTuple['rezultatEditii'];

    // rezultat editii
    $listaEditii = array();
    while ($dbRow = $db->getNextRow($editiiDbResult)) {
        $listaEditii[] = new Editie($dbRow, Editie::EDITIE_PREVIEW);
    }

    // rezultat articole
    $listaEditiileArticolelor = array();
    $listaArticole = array();
    while ($dbRow = $db->getNextRow($articoleDbResult)) {
        $idEditiaCurenta = $dbRow[DBC::ED_ID];

        if (isset($listaEditiileArticolelor[$idEditiaCurenta])) {
            $editiaCurenta = $listaEditiileArticolelor[$idEditiaCurenta];
        } else {
            $editiaCurenta = new Editie($dbRow, Editie::EDITIE_FULL);
            $listaEditiileArticolelor[$idEditiaCurenta] = $editiaCurenta;
        }

        $articolCurent = new Articol($dbRow, $editiaCurenta);
        $listaArticole[] = $articolCurent;
    }

    // TODO poate gasesti alta solutie
    return array(
        'Ediții'   => array("divArray" => $listaEditii, "divClasses" => array("search-card-container")),
        'Articole' => array("divArray" => $listaArticole, "divClasses" => array("search-articol-card-container"))
    );
}

/**
 * Pentru cautarea lunii in DB trebuie doar valoarea numerica
 */
function searchLunaNumeric($lunaCareNuSeStieCeE)
{
    if (is_numeric($lunaCareNuSeStieCeE)) return $lunaCareNuSeStieCeE;
    else return convertLunaToNumăr($lunaCareNuSeStieCeE);
}