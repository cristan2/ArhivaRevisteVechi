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
            width: 40%;
            background-color:#7e8547;
        }

        .imagini {
            width: 50%;
            background-color:#4f443a;
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
            <h1>Div 2</h1>
        </div>
    </div>
</body>
</html>
