<?php
DEFINE("ROOT", "../..");
require_once("../../resources/config.php");

$pageContent = <<<START_HTML
<div class='static changelog'>
<h3>v0.1 (29-11-2017)</h3>
Primul release:
<ul>
<li>Cuprins căutabil pentru majoritatea revistelor Level</li>
<li>Cuprins căutabil pentru Games4Kids</li>
<li>Pagini scanate Level (majoritatea existente)</li>
<li>Căutare simplă</li>
</ul>
</div>
START_HTML;

include_once HTMLLIB . "/view_simple.php";