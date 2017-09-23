<!DOCTYPE html>
<html>

<!-- TODO move to css -->

<head>
    <meta content="text/html; charset=utf-8">
    <style>
        .col {
            border: 2px solid black;
            display: inline-block;
            vertical-align: top;
            min-height: 700px;
        }

        .cuprins {
            width: 50%;
        }

        .imagini {
            width: 40%;
            background-color:#4f443a;
        }

        img.minithumb {
            border: 1px solid black;
            max-width: 50px;
            min-width: 20px;
        }

        img.paginafull {
            width: 100%;
            max-width: 600px;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            min-height: 1em;
        }
    </style>
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
