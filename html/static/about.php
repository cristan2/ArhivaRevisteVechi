<?php
DEFINE("ROOT", "../..");
require_once("../../resources/config.php");

$pageContent = <<<START_HTML
<div class='static about'>
<h1>ArhivaRevisteVechi</h1>

<p>Vizualizare și căutare prin reviste vechi. Aproape toate revistele au un cuprins căutabil, cele mai multe reviste sunt scanate şi accesibile. Vor urma diverse îmbunătățiri, atât pe partea de conținut, cât și pe partea de prezentare și căutare.</p>

<h2>Codul sursă</h2>
Disponibil pe <a href='https://github.com/cristan2/ArhivaRevisteVechi'>GitHub</a>. Contribuțiile sunt binevenite, pentru cine e doritor. Există un fișier TODO cu ce ar mai fi de făcut.

<h2>Baza de date</h2>
<p>E fundaţia site-ului şi are un proiect dedicat pe <a href='https://github.com/adakaleh/revistevechi-db'>GitHub</a>. Tot acest site nu e în esență decât o interfață către informația din baza de date.</p>

<h2>Proiecte conexe</h2>
<p><a href='https://wiki.candaparerevista.ro/'><b>Reviste Vechi Wiki</b></a>: un wiki dedicat revistelor vechi, plecând de la aceeaşi bază de date.</p>

<h2>Road map</h2>
<ul>
<li>Image Gallery</li>
<li>Complex Search</li>
<li>Download CSV personalizat</li>
<li>Mai multe reviste</li>
<li>Diverse îmbunătățiri</li>
<li>Luna de pe cer</li>
</ul>

<h2>Contact</h2>
<p>Pentru orice sugestii, semnalări de bugs, critici sau orice altceva puteți folosi <a href='https://forum.candaparerevista.ro/viewtopic.php?f=16&t=1684'>forumul candaparerevista.ro</a>.</p>
</div>
START_HTML;

include_once HTMLLIB . "/view_simple.php";
