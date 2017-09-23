<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <link rel="stylesheet" href="<?php echo CSSLIB.'/articole_dual_div.css'; ?>" >
</head>

<body>
    <div>
        <div class="col cuprins">
            <h1>Div 1</h1>
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
