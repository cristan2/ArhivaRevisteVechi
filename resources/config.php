<?php
DEFINE("LIB", "./lib/");
DEFINE ("PATH_DB", $_SERVER["DOCUMENT_ROOT"]."/resources/db/arhiva_reviste_v4.1.db");
DEFINE ("PATH_TEMPL", $_SERVER["DOCUMENT_ROOT"]."/resources/templates");

$db = new SQLite3(PATH_DB) or die;