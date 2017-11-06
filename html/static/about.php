<?php
DEFINE("ROOT", "../..");
require_once("../../resources/config.php");

$pageContent = <<<START_HTML

<h1>ArhivaRevisteVechi</h1>

<p>Căutare prin reviste LEVEL. Deocamdată. Aproape toate revistele au un cuprins căutabil, cele mai multe reviste sunt scanate şi accesibile. Cam asta e tot pentru moment.</p>

<h3>Codul sursă</h3>
https://github.com/cristan2/ArhivaRevisteVechi

<h3>Baza de date</h3>
<p>E fundaţia site-ului şi are un proiect dedicat: https://github.com/adakaleh/revistevechi-db</p>

<h3>Proiecte conexe</h3>
<p>[Reviste Vechi Wiki](https://revistevechi.awiki.org/doku.php?id=index): un wiki dedicat revistelor vechi, plecând de la aceeaşi bază de date.</p>

<h3>Road map</h3>
<ul>
<li>Image Gallery</li>
<li>Complex Search</li>
<li>Download CSV personalizat</li>
<li>Mai multe reviste</li>
</ul>

START_HTML;

include_once HTMLLIB . "/view_simple.php";