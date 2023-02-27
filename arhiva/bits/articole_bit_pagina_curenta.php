<?php
/**
 * Toate informatiile necesare afisarii unei pagini de revista
 * si a barei de navigatie prin articol / revista
 *
 * Pot exista 3 situatii: linkul poate avea 3 parametri:
 * editia, articolul si pagina; doar editia e obligatorie.
 * Daca are doar editie, va afisa thumbs pentru toate paginile
 * Daca are si articol+pagina, va afisa pagina specificata din articol
 * Daca are doar articol, va afisa prima pagina din articol
 * Daca are doar pagina, va afisa pagina specificata cu microthumbs
 * pentru toate paginile (TODO)
 */

// TODO handle errors if wrong ids

// lista microthumbs din article-nav
$outputMicroThumbsNav = "";

$articolCurent = filter_input(INPUT_GET, "articol", FILTER_VALIDATE_INT);
$paginaCurentaNr = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);

$hasArticol = !empty($articolCurent);
$hasPagina = !empty($paginaCurentaNr);

// afiseaza un singur articol din editie
if ($hasArticol) {

    // lista microthumbs articol
    $articolCurent = $editiaCurenta->listaArticole[$articolCurent];
    $outputMicroThumbsNav = $articolCurent->buildHtmlPagesMicroThumbs();
    $outputMicroThumbsNav .= $articolCurent->getHtmlOutputTitle();

    // TODO
    // $navLinkPagePrev;
    // $navLinkPageNext;
    // $navLinkArticolPrev;
    // $navLinkArticolNext;

    // afisare pagina din articol
    if (!$hasPagina) {
        $paginaCurentaNr = $articolCurent->listaPagini[0];
    }
    $paginaCurentaHugeThumb = $editiaCurenta->listaPagini[$paginaCurentaNr]->getHugeThumbWithLinkToFullImage();


/* ------- afiseaza intreaga editie ------- */
} else {

    // cazul in care avem pagina specificata, dar fara articol
    // in general nu ar trebui sa se intample (nu exista linkuri directe, doar daca se modifica manual url-ul)
    if ($hasPagina) {

        // TODO lista microthumbs editie - are sens sa afisam asta?
        // $outputMicroThumbsNav = ???

        // afisare pagina din editie
        $paginaCurentaHugeThumb = $editiaCurenta->listaPagini[$paginaCurentaNr]->getHugeThumbWithLinkToFullImage();

    // varianta default - daca nu are nici pagina, nici articol
    // afiseaza thumbs pentru toate paginile din editie
    } else {
        $paginiThumbsContent = $editiaCurenta->printAllPagesThumbsToHtml();
    }

}
