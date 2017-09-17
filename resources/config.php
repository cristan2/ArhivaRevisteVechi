<?php
include_once "db/Database.php";
DEFINE ("DB_FILE"   , "./resources/db/arhiva_reviste_v4.1.db");
DEFINE ("LIB"       , "./lib");
DEFINE ("TEMPL"     , "./resources/templates");
DEFINE ("IMG"       , "./resources/img");
DEFINE ("ARHIVA"    , "./arhiva");

//$db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY) or die;
$db = Database::getConnection(DB_FILE);