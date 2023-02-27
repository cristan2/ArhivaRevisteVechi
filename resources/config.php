<?php

// debug & production
DEFINE ("IS_DEBUG", false);
DEFINE ("REVISTE_READY_FOR_PROD", array("Level", "Games 4 Kids", "Hobbyte''s", "Game Over"));

// site
DEFINE ("SITE_NAME"  , "Arhiva Reviste Vechi");
DEFINE ("SITE_TAG"   , "Sniper rifles... close-range weapons for when you accidentaly select the wrong gun.");
DEFINE ("SEARCH_HINT", "I’m looking for 30 dead guys and one woman.");

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
DEFINE ("HTMLSTATIC", HTMLLIB ."/static");

// resources
DEFINE ("RESOURCES" , ROOT . "/resources");
DEFINE ("TEMPL"     , RESOURCES ."/templates");
DEFINE ("IMG"       , RESOURCES ."/img");

// cache
DEFINE ("CACHE_ENABLED", false);
DEFINE ("CACHE_DIR" , ROOT . "/cache");

// db
DEFINE ("DB_DIR"    , RESOURCES . "/db");
DEFINE ("DB_FILE"   , DB_DIR . "/arhiva_reviste_v7.0.db");

require_once DB_DIR . "/DBC.php";
use ArhivaRevisteVechi\resources\db\DBC;
$db = new DBC(DB_FILE);

// coperti default
DEFINE ("COPERTI_DIR"    , IMG . "/coperti");
DEFINE ("COPERTA_DEFAULT", IMG . "/coperti/default.jpg");
DEFINE ("THUMB_DEFAULT"  , IMG . "/coperti/default_th.jpg");

// external links
DEFINE ("RVWIKI_BASE_LINK"         , "https://wiki.candaparerevista.ro");
DEFINE ("KNOWN_FILE_HOSTS", array(
    "archive" => "Archive.org",
    "scribd" => "Scribd",
    "mediafire" => "Mediafire",
    "imgur" => "Imgur"));
