<?php

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

    "An"        => function ($row) {return getColData($row, 'an');},
    "Luna"      => function ($row) {return getColData($row, 'luna');},
    "Nr."       => function ($row) {return getColData($row, 'numar');},
    "Joc full"  => function ($row) {return getColData($row, 'joc_complet');},
    "Imagine"   => function ($row) {return makeImgUrl(getColData($row, 'revista_nume'),
                                                      getColData($row, 'an'),
                                                      getColData($row, 'luna'),
                                                      1);}
    );

$tabelBody = buildRowsDinamic($toateEditiile, $tabelHead);

include_once PATH_TEMPL . "/tpl_tabel.php";


// ========== inner stuff =========== //

function buildRowsDinamic($toateEditiile, $tabelHead) {
    $allRows = "";

    while ($row = $toateEditiile->fetchArray(SQLITE3_ASSOC)) {
        $currentRow = "<tr>";
        foreach($tabelHead as $colNume => $colValue) {
            $currentRow .= getTd($colValue($row));
        }
        $currentRow .= "</tr>";
        $allRows .= $currentRow . PHP_EOL;
    }
    return $allRows;
}

function getColData($row, $colName) {
    return $row[$colName];
}

function getTd($cellValue) {
    return "<td>$cellValue</td>";
}

function makeImgUrl($nume_revista, $an, $luna, $pgNo) {
    $nume_revista = strtolower($nume_revista);

    $paddedPage = str_pad($pgNo, 3, '0', STR_PAD_LEFT);
    $paddedMonth = str_pad($luna, 2, '0', STR_PAD_LEFT);

    $baseImgName = $nume_revista . $an . $paddedMonth . $paddedPage;
    $imgDirPath = PATH_IMG . "/$nume_revista/$an/$paddedMonth";

    $imgSrc = "$imgDirPath/$baseImgName.jpg";
    $imgThumbSrc = "$imgDirPath/th/$baseImgName" . "_th.jpg";

    if (file_exists($imgSrc)) {
        return "<a href=\"$imgSrc\"><img src=\"$imgThumbSrc\" alt=\"Image\" /></a>";
    } else {
        return "n/a";
    }
}
