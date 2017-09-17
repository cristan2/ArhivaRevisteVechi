<?php

require("./resources/config.php");

if (isset($_POST["reviste"])) {
    include_once ARHIVA . "/editii.php";
} else {
    include_once TEMPL . "/home_reviste.php";
}
