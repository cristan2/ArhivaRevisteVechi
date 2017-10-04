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
DEFINE ("TEMPL"     , RESOURCES ."/templates");
DEFINE ("IMG"       , RESOURCES ."/img");

DEFINE ("DB_DIR"    , RESOURCES . "/db");
DEFINE ("DB_FILE"   , DB_DIR . "/arhiva_reviste_v4.4.db");

$db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY) or die;