<?php

// root
DEFINE ("RESOURCES" , ROOT . "/resources");
DEFINE ("ARHIVA"    , ROOT . "/arhiva");

// resources
DEFINE ("DB_FILE"   , RESOURCES . "/db/arhiva_reviste_v4.1.db");
DEFINE ("LIB"       , RESOURCES ."/lib");
DEFINE ("TEMPL"     , RESOURCES ."/resources/templates");
DEFINE ("IMG"       , RESOURCES ."/resources/img");

$db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY) or die;
//$db = Database::getConnection(DB_FILE);