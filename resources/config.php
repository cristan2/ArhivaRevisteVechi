<?php
DEFINE("LIB", "./lib/");
DEFINE ("PATH_DB", "./resources/db/arhiva_reviste_v4.1.db");
DEFINE ("PATH_TEMPL", "./resources/templates");
DEFINE ("PATH_IMG", "./resources/img");

$db = new SQLite3(PATH_DB) or die;