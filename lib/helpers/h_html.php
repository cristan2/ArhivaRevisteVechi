<?php

function getBaseUrl() {
    $urlWithParams = $_SERVER['REQUEST_URI'];
    $urlParts = explode("?", $urlWithParams, 2);
    return $urlParts[0];
}