<?php

DEFINE("LIB", "./lib/");
DEFINE ("DB_PATH", "./lib/db/arhiva_reviste_v4.1.db");

$db = new SQLite3(DB_PATH) or die;

// quick test
$toaterevistele = $db->query("SELECT * FROM reviste");


echo "<form action=\"\" method='post'>";
echo "<select name='reviste'>";

while ($row = $toaterevistele->fetchArray(SQLITE3_ASSOC)) {
    $numerevista = $row['revista_nume'];
    echo "<option value=\"$numerevista\">$numerevista</option>";
}

echo "</select>";
echo "<input type=\"submit\" />";
echo "</form>";

?>
