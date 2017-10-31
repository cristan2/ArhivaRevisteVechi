<?php

// debug
DEFINE ("IS_DEBUG", false);

// arhiva
DEFINE ("ARHIVA"    , ROOT . "/arhiva");
DEFINE ("ARHIVABITS", ARHIVA . "/bits");

// lib
DEFINE ("LIB"       , ROOT ."/lib");
DEFINE ("HELPERS"   , LIB ."/helpers");

// html + css
DEFINE ("HTMLLIB"   , ROOT ."/html");
DEFINE ("CSSLIB"    , HTMLLIB ."/css");
DEFINE ("HTMLBITS"  , HTMLLIB ."/bits");

// resources
DEFINE ("RESOURCES" , ROOT . "/resources");
DEFINE ("TEMPL"     , RESOURCES ."/templates");
DEFINE ("IMG"       , RESOURCES ."/img");

// db
DEFINE ("DB_DIR"    , RESOURCES . "/db");
DEFINE ("DB_FILE"   , DB_DIR . "/arhiva_reviste_v5.db");

require_once DB_DIR . "/DBC.php";
use ArhivaRevisteVechi\resources\db\DBC;
$db = new DBC(DB_FILE);

// coperti default
DEFINE ("COPERTI_DIR", IMG . "/coperti");
DEFINE ("COPERTA_DEFAULT", IMG . "/coperti/default.jpg");