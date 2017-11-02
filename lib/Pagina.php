<?php

namespace ArhivaRevisteVechi\lib;
require_once HELPERS . "/h_misc.php";

class Pagina
{

    const NORMAL_THUMB = "minithumb";
    const MICRO_THUMB  = "microthumb";


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
     * Construieste calea catre o imagini sau thumbnail
     * ex. $isForThumb == false: img/level/1999/12/Level199912002.jpg
     * ex. $isForThumb == true: img/level/1999/12/th/Level199912002_th.jpg
     */
    public function makePath($isForThumb = false)
    {
        $imagePath = $this->editiaParinte->editieDirPath
            . DIRECTORY_SEPARATOR
            . ($isForThumb ? "th" . DIRECTORY_SEPARATOR : "")
            . $this->paginaBaseName
            . ($isForThumb ? "_th" : "")
            . ".jpg";

        if (!file_exists($imagePath)) {
            return ($isForThumb ? THUMB_DEFAULT : COPERTA_DEFAULT);
        }
        return $imagePath;
    }

    public function getMicroThumbWithLinkToPaginaDinArticol($articolId)
    {
        $destinationLink = $this->editiaParinte->getEditieUrl() . "&articol=$articolId" . "&pagina=$this->numar";
        return $this->getImageWithLink($this->thumbPath, $destinationLink, "microthumb");
    }

    public function getMicroThumbWithLinkToFull()
    {
        return $this->getImageWithLink($this->thumbPath, $this->path, "microthumb");
    }

    public function getThumbWithLinkToFullImage()
    {
        return $this->getImageWithLink($this->thumbPath, $this->path, "minithumb");
    }

    public function getThumbWithLinkToEditie($cssClass)
    {
        $destinationLink = $this->editiaParinte->getEditieUrl();
        return $this->getImageWithLink($this->thumbPath, $destinationLink, $cssClass);
    }

    function getImageWithLink($displayedImagePath, $targetLink, ...$cssClasses) {
        if (! file_exists($displayedImagePath))
            $displayedImagePath = COPERTA_DEFAULT;
        $htmlClassList = getCssClassList($cssClasses);
        return "<a href='$targetLink'><img src='$displayedImagePath' $htmlClassList alt='Image' /></a>";
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