<?php

$revistaId = $_POST["reviste"];
$toateEditiile = $db->query("
  SELECT r.revista_nume, e.*
  FROM editii e
  JOIN reviste r
  USING ('revista_id')
  WHERE e.revista_id = '$revistaId' AND e.tip = 'revista'; ");

afiseazaTabel($toateEditiile);

function afiseazaTabel($toateEditiile) {
    $randuri = getRows($toateEditiile);
    $output = "" .
    "<!DOCTYPE html>
    <html>
    <head>
        <meta content=\"text/html; charset=utf-8\">
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                min-height: 1em;
            }
        </style>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Imagine</th>
                    <th>An</th>
                    <th>Luna</th>
                    <th>Nr.</th>
                    <th>Joc full</th>
                </tr>
            </thead>"
            ."<tbody>"
                . $randuri
        . " </tbody>
        </table>
    </body>
    </html>";

    echo $output;
}

function getRows($toateEditiile) {
    $randuri = "";
    while ($row = $toateEditiile->fetchArray(SQLITE3_ASSOC)) {
        $id = $row['editie_id'];
        $nume_revista = $row['revista_nume'];
        $an = $row['an'];
        $luna = $row['luna'];
        $nr = $row['numar'];
        $joc_complet = $row['joc_complet'];
        $url = makeImgUrl(strtolower($nume_revista), $an, $luna, 1);
        $randuri .= getRowTableData($url, $an, $luna, $nr, $joc_complet) . PHP_EOL;
    }
    return $randuri;
}

function getRowTableData(...$cols) {
    $tableRow= "<tr>";
    foreach ($cols as $col) {
        $tableRow .= getTd($col);
    }
    return $tableRow . "</tr>";
}

function getTd($cellValue) {
    return "<td>$cellValue</td>";
}

function makeImgUrl($nume_revista, $an, $luna, $pgNo) {

    $paddedPage = str_pad($pgNo, 3, '0', STR_PAD_LEFT);
    $paddedMonth = str_pad($luna, 2, '0', STR_PAD_LEFT);

    $baseImgName = $nume_revista.$an.$paddedMonth.$paddedPage;
    $imgDirPath = PATH_IMG."/$nume_revista/$an/$paddedMonth";

    $imgSrc = "$imgDirPath/$baseImgName.jpg";
    $imgThumbSrc = "$imgDirPath/th/$baseImgName"."_th.jpg";

    if (file_exists($imgSrc)) {
        return "<a href=\"$imgSrc\"><img src=\"$imgThumbSrc\" alt=\"Image\" /></a>";
    } else {
        return "n/a";
    }


}