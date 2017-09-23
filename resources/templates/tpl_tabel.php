<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono|Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSSLIB.'/editii_cards.css'; ?>" >
</head>

<body>
    <div>
        <?php echo isset($revisteCards) ? $revisteCards : "" ?>
    </div>

<table>
    <thead>
    <tr>
        <?php foreach ($tabelHead as $colKey => $colValue) echo "<th>$colKey</th>";?>
    </tr>
    </thead>

    <tbody>
        <?php echo $tabelBody ?>
    </tbody>
</table>
</body>
</html>
