<?php
DEFINE("ROOT", "../..");
require_once("../../resources/config.php");

$pageContent = <<<START_HTML
<div class='static changelog'>
<h3>v0.2.2 (01-07-2018)</h3>
Content update:
<ul>
<li>Adăugat revistele Level din 2006 (11 numere, fără august)</li>
</ul>
<h3>v0.2.1 (14-01-2018)</h3>
Content update:
<ul>
<li>Adăugat revistele Level din 2005 (11 numere, fără ianuarie) </li>
</ul>
<h3>v0.2 (30-12-2017)</h3>
Content update:
<ul>
<li>Adăugat revista Game Over</li>
<li>Adăugat revista Hobbyte's</li>
</ul>
Modificări majore:
<ul>
<li>Îmbunătăţit performanţa pagina ediţii</li>
</ul>
Modificări minore:
<ul>
<li>Îmbunătăţit/adăugat efecte shadows la toate elementele la mouse hover</li>
<li>Îmbunătăţiri minore căutare (ordonare, nume reviste colorate diferit)</li>
</ul>
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