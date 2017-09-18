<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html; charset=utf-8">
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
          <?php
            foreach ($tabelHead as $colKey => $colValue) echo "<th>$colKey</th>";
          ?>
        </tr>
      </thead>

      <tbody>
        <?php echo $tabelBody ?>
      </tbody>
    </table>
  </body>
</html>
