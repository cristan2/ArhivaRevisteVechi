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
function performSimpleSearch($params)
{
    global $db;

    if (empty($params['filter'])) return "Empty filter";
    else {
        $dbResult = specialQuerySimpleSearch($db, $params['filter']);
        $processedResult = processSimpleSearchDbResult($db, $dbResult);
        $output = "";
        foreach($processedResult as $categ => $results ) {
            $numarRezultate = count($results['divArray']);
            if ($numarRezultate > 0) {
                $output .= "<h1>$categ</h1>";
                $output .= "<h2>($numarRezultate rezultate)</h2>";
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

    $queryEditii = "
        SELECT e.*, r.*
        FROM editii e
        LEFT JOIN reviste r USING ('revista_id')
        WHERE lower(e.an)  LIKE '%$searchFilter%'
        OR lower(e.luna)  LIKE '%$searchFilter%'
        OR lower(e.joc_complet)  LIKE '%$searchFilter%'
    ";

    $rezultatDbEditii = $db->directQuery($queryEditii);
    $rezultatDbArticole = $db->directQuery($queryArticole);

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
        $listaEditii[] = new Editie($dbRow);
    }

    // rezultat articole
    $listaEditiileArticolelor = array();
    $listaArticole = array();
    while ($dbRow = $db->getNextRow($articoleDbResult)) {
        $idEditiaCurenta = $dbRow[DBC::ED_ID];

        if (isset($listaEditiileArticolelor[$idEditiaCurenta])) {
            $editiaCurenta = $listaEditiileArticolelor[$idEditiaCurenta];
        } else {
            $editiaCurenta = new Editie($dbRow, Editie::EDITIE_PREVIEW);
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