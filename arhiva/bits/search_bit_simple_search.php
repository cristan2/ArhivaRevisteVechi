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
        return buildDivRows($processedResult['articole'], "articol-card-container");
//        return buildHtmlTableFromDbResult($dbResult);
    }

}

function specialQuerySimpleSearch($db, $searchFilter)
{
    $searchFilter = strtolower($searchFilter);
    return $db->directQuery("
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
    ");
//        LEFT JOIN reviste r USING ('revista_id')
//        AND lower(r.revista_nume)  LIKE '%$searchFilter%'
}

function processSimpleSearchDbResult($dbResult)
{

    $listaEditii = array();
    $listaArticole = array();

    while ($dbRow = $dbResult->fetchArray(SQLITE3_ASSOC)) {
        $idEditiaCurenta = $dbRow[DBC::ED_ID];
        if (isset($listaEditii[$idEditiaCurenta])) {
            $editiaCurenta = $listaEditii[$idEditiaCurenta];
        } else {
            $editiaCurenta = new Editie($dbRow);
            $listaEditii[$idEditiaCurenta] = $editiaCurenta;
        }

        $articolCurent = new Articol($dbRow, $editiaCurenta);
        $listaArticole[] = $articolCurent->getHtmlOutput(true);
    }
    return array(
        'editii'   => $listaEditii,
        'articole' => $listaArticole
    );
}