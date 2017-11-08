<?php

namespace ArhivaRevisteVechi\lib;
require_once HELPERS . "/h_misc.php";
require_once HELPERS . "/HtmlPrinter.php";

use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;

class Pagina
{
    const NORMAL_THUMB = "minithumb";
    const MICRO_THUMB  = "microthumb";

    const THUMB = 0;
    const IMAGE = 1;

    public $editiaParinte;
    public $numar;

    public $paginaBaseName;

    public $path;
    public $thumbPath;


    public function __construct(Editie $editie, $numar = 1)
    {
        $this->editiaParinte  = $editie;
        $this->numar          = $numar;

        $this->paginaBaseName = $this->buildPaginaBaseName();
        $this->path           = $this->makePath();
        $this->thumbPath      = $this->makePath(true);
    }

    /**
     * Construieste numele imaginii fara extensie
     * (ex: 'Level 1999 12 002' fara spatii)
     */
    private function buildPaginaBaseName()
    {
        return $this->editiaParinte->editieBaseNameForPages
        . padLeft($this->numar, PAGINA_PAD);
    }

    /**
     * Construieste calea catre o imagine sau thumbnail:
     * ex. $isForThumb == false: img/level/1999/12/Level199912002.jpg
     * ex. $isForThumb == true: img/level/1999/12/th/Level199912002_th.jpg
     *
     * Daca nu exista, returneaza imaginea default.
     */
    public function makePath($isForThumb = false)
    {
        $imagePath = $this->editiaParinte->editieDirPath
            . DIRECTORY_SEPARATOR
            . ($isForThumb ? "th" . DIRECTORY_SEPARATOR : "")
            . $this->paginaBaseName
            . ($isForThumb ? "_th" : "")
            . ".jpg";
        return $imagePath;
    }

    public function getLinkToPaginaDirArticol($articolId)
    {
        return $this->editiaParinte->getEditieUrl() . "&articol=$articolId" . "&pagina=$this->numar";
    }

    public function getMicroThumbWithLinkToPaginaDinArticol($articolId)
    {
        $destinationLink = $this->getLinkToPaginaDirArticol($articolId);
        return $this->getImageWithLink(self::THUMB, $this->thumbPath, $destinationLink, "microthumb");
    }

    public function getMicroThumbWithLinkToFull()
    {
        return $this->getImageWithLink(self::THUMB, $this->thumbPath, $this->path, "microthumb");
    }

    public function getThumbWithLinkToFullImage()
    {
        return $this->getImageWithLink(self::THUMB, $this->thumbPath, $this->path, "minithumb");
    }

    public function getThumbWithLinkToEditie($cssClass)
    {
        $destinationLink = $this->editiaParinte->getEditieUrl();
        return $this->getImageWithLink(self::THUMB, $this->thumbPath, $destinationLink, $cssClass, true);
    }

    public function getHugeThumbWithLinkToFullImage()
    {
        return $this->getImageWithLink(self::IMAGE, $this->path, $this->path, "fullthumb");
    }

    // TODO implement alt description
    /**
     * Primeste calea catre o imagine si un link de destinatie si afiseaza
     * respectiva imagines cu link catre destinatia specificata, atasand
     * si clasele css specificate optional
     *
     * Daca fisierul nu exista (imaginea primita este cea default),
     * va fi afisata imaginea default fara link, cu exceptia cazului in
     * care e specificat explicit (forceLink), util in cazul editiilor
     * care nu au imagini scanate, dar au cuprins
     */
    function getImageWithLink($imgType, $displayedImagePath, $targetLink, $cssClasses, $forceLink = false)
    {
        $htmlClassList = getCssClassList($cssClasses);

        $imageExists = file_exists($displayedImagePath);

        // daca imaginea nu exista, afiseaza imaginea default
        if (!$imageExists) {
            $displayedImagePath = ($imgType == self::THUMB ? THUMB_DEFAULT : COPERTA_DEFAULT);
        }

        $displayedElement = HtmlPrinter::wrapImg($displayedImagePath, $htmlClassList, 'Image');

        if (!$imageExists && !$forceLink)
            return $displayedElement;

        return HtmlPrinter::wrapLink($displayedElement, $targetLink);
    }

//    static function fromPath($editie, $path)
//    {
//        $attrs = array(
//            "isFromPath" => true,
//            "path"       => $path
//        );
//        return new Pagina($editie, true, $path);
//    }
//
//    static function fromName($editie, $numar)
//    {
//        $attrs = array(
//            "numar_pg"  => $numar
//        );
//        return new Pagina($editie, false, $attrs);
//    }

}