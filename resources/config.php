<?php
// arhiva
DEFINE ("ARHIVA"    , ROOT . "/arhiva");

// lib
DEFINE ("LIB"       , ROOT ."/lib");
DEFINE ("HELPERS"   , LIB ."/helpers");

// html + css
DEFINE ("HTMLLIB"   , ROOT ."/html");
DEFINE ("CSSLIB"    , HTMLLIB ."/css");

// resources
DEFINE ("RESOURCES" , ROOT . "/resources");
DEFINE ("DB_FILE"   , RESOURCES . "/db/arhiva_reviste_v4.2.db");
DEFINE ("TEMPL"     , RESOURCES ."/templates");
DEFINE ("IMG"       , RESOURCES ."/img");

$db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY) or die;