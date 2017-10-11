<?php

require_once LIB . "/Articol.php";
require_once LIB . "/Editie.php";
require_once DB_DIR. "/DBC.php";
use ArhivaRevisteVechi\lib\Articol;
use ArhivaRevisteVechi\lib\Editie;
use ArhivaRevisteVechi\resources\db\DBC;

// TODO clean search filter
function performSimpleSearch($params)
{
    global $db;

    if (empty($params['filter'])) return "Empty filter";
    else {
        $dbResult = specialQuerySimpleSearch($db, $params['filter']);
        $processedResult = processSimpleSearchDbResult($dbResult);
//        return buildHtmlTableFromArray($processedResult);
//        return buildHtmlTableFromDbResult($dbResult);
//        return buildDivRows($processedResult['articole'], "articol-card-container");
        $output = "";
        foreach($processedResult as $categ => $results ) {
            $output .= "<h1>$categ</h1>";
            $output .= buildDivRowsFromArray($results['divArray'], $results['divClasses'], true);
        }
        return $output;
    }

}

function specialQuerySimpleSearch($db, $searchFilter)
{
    $searchFilter = strtolower($searchFilter);
    $query = "
        SELECT e.*, a.*
        FROM articole a
        LEFT JOIN editii e USING ('editie_id')
        WHERE lower(e.an)  LIKE '%$searchFilter%'
        OR lower(e.luna)  LIKE '%$searchFilter%'
        OR lower(e.joc_complet)  LIKE '%$searchFilter%'
        OR lower(a.rubrica)  LIKE '%$searchFilter%'
        OR lower(a.titlu)  LIKE '%$searchFilter%'
        OR lower(a.joc_platforma)  LIKE '%$searchFilter%'
        OR lower(a.autor)  LIKE '%$searchFilter%'
    ";
//        LEFT JOIN reviste r USING ('revista_id')
//        AND lower(r.revista_nume)  LIKE '%$searchFilter%'
//    echo ($query);
    return $db->directQuery($query);
}


function processSimpleSearchDbResult($dbResult)
{
    $listaEditii = array();
    $listaEditiiHtml = array();
    $listaArticoleHtml = array();

    while ($dbRow = $dbResult->fetchArray(SQLITE3_ASSOC)) {
        $idEditiaCurenta = $dbRow[DBC::ED_ID];

        if (isset($listaEditii[$idEditiaCurenta])) {
            $editiaCurenta = $listaEditii[$idEditiaCurenta];
        } else {
            $editiaCurenta = new Editie($dbRow);
            $listaEditii[$idEditiaCurenta] = $editiaCurenta;
        }

        $articolCurent = new Articol($dbRow, $editiaCurenta);
        $listaArticoleHtml[] = $articolCurent;
    }

    // TODO poate gasesti alta solutie
    return array(
        'editii'   => array("divArray" => $listaEditii, "divClasses" => array("search-card-container")),
        'articole' => array("divArray" => $listaArticoleHtml, "divClasses" => array("search-articol-card-container"))
    );
}