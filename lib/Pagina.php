<?php

namespace ArhivaRevisteVechi\lib;
require_once HELPERS . "/h_images.php";

class Pagina
{

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

    public function getThumbWithLink()
    {
        return getImageWithLink($this->thumbPath, $this->path, "minithumb");
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