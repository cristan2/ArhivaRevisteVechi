<?php
/**
 * Toate informatiile necesare afisarii unei pagini de revista
 * si a barei de navigatie prin articol / revista
 */

// TODO handle errors if wrong ids

/* ------- navigatie articole ------- */

$thumbsArticolCurent = "";
if (!empty($_GET['articol'])) {
    $articolCurent = $_GET['articol'];
    $articolCurent = $editiaCurenta->listaArticole[$articolCurent];
    $thumbsArticolCurent = $articolCurent->buildHtmlPagesThumbnails();
}


/* ------- info pagina curenta ------- */
$paginaCurentaNr = "1";
if (!empty($_GET['pagina']))  $paginaCurentaNr = $_GET['pagina'];

$paginaCurentaImagePath = getImage($editiaCurenta, $paginaCurentaNr);