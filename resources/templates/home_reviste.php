<?php

$toaterevistele = $db->query("SELECT * FROM reviste");

afiseazaMeniuReviste($toaterevistele);

function afiseazaMeniuReviste($toateRevistele) {
    $optiuniMeniu = buildOptiuniMeniuReviste($toateRevistele);
    echo <<<_BAGAHTML
	<form action = "" method = "POST">
	    <select name='reviste'>
	    $optiuniMeniu
	    </select>
	    <input type="submit" value = "Afiseaza reviste" />
    </form>
_BAGAHTML;
}

function buildOptiuniMeniuReviste($toateRevistele) {
    $optiuniMeniu = "";
    while ($row = $toateRevistele->fetchArray(SQLITE3_ASSOC)) {
        $revistaNume = $row['revista_nume'];
        $revistaId = $row['revista_id'];
        $optiuniMeniu .= "<option value=\"$revistaId\">$revistaNume</option>";
    }
    return $optiuniMeniu;
}