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
        $startTime = microtime(true);
        $dbResult = specialQuerySimpleSearch($db, $params['filter']);
        $processedResult = processSimpleSearchDbResult($db, $dbResult);
        $output = "";

        foreach($processedResult as $categ => $results ) {
            $numarRezultate = count($results['divArray']);

            // hint cautare
            $stringRezultate = ($numarRezultate == 1) ? "rezultat" : "rezultate";
            if ($categ == "Ediții") {
                $hint = "pentru 'număr', 'an', 'lună', 'joc complet'";
            } else if ($categ == "Articole") {
                $hint = "pentru 'rubrică', 'titlu articol', 'platformă joc', 'autor'";
            }

            $output .= "<h1>$categ</h1>";
            $output .= "<h2>($numarRezultate $stringRezultate $hint)</h2>";

            if ($numarRezultate > 0) {
                $output .= HtmlPrinter::buildDivContainer($results['divArray'], $results['divClasses'], true);
            }
        }
        return $output . "<p>(" . (microtime(true) - $startTime) . " sec.)</p>";
    }
}

function specialQuerySimpleSearch($db, $searchFilter)
{
    $searchFilter = strtolower($searchFilter);

    $statementArticole = $db->db->prepare("
        SELECT e.*, a.*
        FROM articole a
        LEFT JOIN editii e USING (editie_id)
        WHERE instr(lower(a.rubrica), :search_filter) > 0
        OR instr(lower(a.titlu), :search_filter) > 0
        OR instr(lower(a.joc_platforma), :search_filter) > 0
        OR instr(lower(a.autor), :search_filter) > 0
        ORDER BY e.an, e.luna
        LIMIT 300  -- TODO: pagination
    ");
    $statementArticole->bindValue(':search_filter', $searchFilter, SQLITE3_TEXT);

    $lunaConvertita = searchLunaNumeric($searchFilter);
    $statementEditii = $db->db->prepare("
        SELECT e.*, r.*
        FROM editii e
        LEFT JOIN reviste r USING (revista_id)
        WHERE e.numar = :search_filter
        OR e.an = :search_filter
        OR e.luna = :luna_convertita
        OR instr(lower(e.joc_complet), :search_filter) > 0
        LIMIT 300  -- TODO: pagination
    ");
    $statementEditii->bindValue(':search_filter', $searchFilter, SQLITE3_TEXT);
    $statementEditii->bindValue(':luna_convertita', $lunaConvertita, SQLITE3_INTEGER);

    return array(
        "rezultatEditii"   => $statementEditii->execute(),
        "rezultatArticole" => $statementArticole->execute());
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

/**
 * Pentru cautarea lunii in DB trebuie doar valoarea numerica
 */
function searchLunaNumeric($lunaCareNuSeStieCeE)
{
    if (is_numeric($lunaCareNuSeStieCeE)) return $lunaCareNuSeStieCeE;
    else return convertLunaToNumăr($lunaCareNuSeStieCeE);
}
