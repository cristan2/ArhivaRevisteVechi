<?php

require_once LIB . "/helper_tables.php";

$revistaId = $_POST["reviste"];

$toateEditiile = $db->query("
    SELECT r.revista_nume, e.*
    FROM editii e
    JOIN reviste r
    USING ('revista_id')
    WHERE 1
    AND e.revista_id = '$revistaId'
    AND e.tip = 'revista'
");

$tabelHead = array(

    "Id"        => function ($row) {return getColData($row, 'editie_id');},
    "An"        => function ($row) {return getColData($row, 'an');},
    "Luna"      => function ($row) {return getColData($row, 'luna');},
    "Nr."       => function ($row) {return getColData($row, 'numar');},
    "Joc full"  => function ($row) {return getColData($row, 'joc_complet');},
    "Imagine"   => function ($row) {return makeImgUrl(getColData($row, 'revista_nume'),
                                                      getColData($row, 'an'),
                                                      getColData($row, 'luna'),
                                                      1,
                                                      getColData($row, 'editie_id'));}
    );

$tabelBody = buildRowsDinamic($toateEditiile, $tabelHead);

include_once TEMPL . "/tpl_tabel.php";

// TODO refactor
function makeImgUrl($nume_revista, $an, $luna, $pgNo, $editieId) {
    $nume_revista = strtolower($nume_revista);

    $paddedPage = str_pad($pgNo, 3, '0', STR_PAD_LEFT);
    $paddedMonth = str_pad($luna, 2, '0', STR_PAD_LEFT);

    $baseImgName = $nume_revista . $an . $paddedMonth . $paddedPage;
    $imgDirPath = IMG . "/$nume_revista/$an/$paddedMonth";

    $imgSrc = "$imgDirPath/$baseImgName.jpg";
    $imgThumbSrc = "$imgDirPath/th/$baseImgName" . "_th.jpg";

    $targetLink = ARHIVA . "/articole.php" . "?editie=$editieId";

    if (file_exists($imgSrc)) {
        return "<a href=\"$targetLink\"><img src=\"$imgThumbSrc\" alt=\"Image\" /></a>";
    } else {
        return "n/a";
    }
}
