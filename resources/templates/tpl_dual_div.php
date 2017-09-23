<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <link rel="stylesheet" href="<?php echo CSSLIB.'/articole_dual_div.css'; ?>" >
</head>

<body>
    <div>
        <h1><?php echo $titluEditieCurenta?></h1>
        <h2><?php echo $lunaEditieCurenta?></h2>
        <div class="col cuprins">
            <table>
                <thead>
                <tr>
                    <?php foreach ($tabelHead as $colKey => $colValue) echo "<th>$colKey</th>"; ?>
                </tr>
                </thead>

                <tbody>
                    <?php echo $tabelBody ?>
                </tbody>
            </table>
        </div>
        <div class="col imagini">
            <img class = "paginafull" src = "<?php echo $paginaCurentaImagePath ?>" />
        </div>
    </div>
</body>
</html>
