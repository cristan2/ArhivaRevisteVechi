<?php

require("./resources/config.php");

// quick test
if (isset($_POST["reviste"])) {
    include_once PATH_TEMPL . "/editii.php";
} else {
    include_once PATH_TEMPL . "/home_reviste.php";
}
