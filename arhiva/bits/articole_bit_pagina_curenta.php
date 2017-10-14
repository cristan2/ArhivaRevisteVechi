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

    // TODO
    $navLinkPagePrev;
    $navLinkPageNext;
    $navLinkArticolPrev;
    $navLinkArticolNext;

    /* ------- afisare epagina curenta ------- */
    $paginaCurentaNr = "1";
    if (!empty($_GET['pagina']))  $paginaCurentaNr = $_GET['pagina'];
    $paginaCurentaImagePath = getImage($editiaCurenta, $paginaCurentaNr);

} else {
// TODO - se duplica mult cod din Articol; trebuie clasa separata ('Imagine'/'Pagina' class?)
    /* ------- afisare thumbs toate paginile------- */
    $paginiThumbsContent = "";
    for ($pg = 1; $pg <= $editiaCurenta->maxNumPages; $pg++) {

        $imageBaseName = getBaseImageName(
            $editiaCurenta->numeRevista,
            $editiaCurenta->an,
            $editiaCurenta->luna,
            $pg);

        $destinationLink = getImagePath($editiaCurenta->editieDirPath, $imageBaseName);

        $currImageBaseName = $editiaCurenta->editieBaseName . padLeft($pg, PAGINA_PAD);
        $imageThumb = getImageThumbPath($editiaCurenta->editieDirPath, $currImageBaseName);
        $paginiThumbsContent .= getImageWithLink($imageThumb, $destinationLink, "minithumb")."  ";
    }
}


