<?php
namespace ArhivaRevisteVechi\lib;

require_once("../resources/config.php");
require_once LIB . "/HtmlPrintable.php";
require_once HELPERS . "/h_images.php";
use ArhivaRevisteVechi\resources\db\DBC;

class Articol implements HtmlPrintable
{
    public $titlu, $rubrica, $autor;
    public $pageToc, $pageCount;
    public $listaPagini;

    private $editiaParinte;


    public function __construct($dbRow, Editie $editiaParinte)
    {
        // info editia-parinte
        $this->editiaParinte = $editiaParinte;

        // info articol
        $this->pageToc       = $dbRow[DBC::ART_PG_TOC];
        $this->rubrica       = $dbRow[DBC::ART_RUBRICA];
        $this->titlu         = $dbRow[DBC::ART_TITLU];
        $this->autor         = $dbRow[DBC::ART_AUTOR];
        $this->pageCount     = $dbRow[DBC::ART_PG_CNT];

        // info pagini
        $this->listaPagini   = $this->buildPagini();
    }

    private function buildPagini ()
    {
        $listaPagini = array();
        for ($pgIndex = 0; $pgIndex < $this->pageCount; $pgIndex++) {
            $currentPageNo = $this->pageToc + $pgIndex;
            $currentPageBaseName = $this->buildPaginaBaseName($currentPageNo);
            $listaPagini["$currentPageNo"] = $currentPageBaseName;
        }
        return $listaPagini;
    }

    /**
     * Construieste un container de tip <div> ce contine
     * atributele printabile ale obiectului impachetate in
     * sub-elemente <div> separate
     */
    public function getHtmlOutput()
    {
        $propertiesToDisplay = $this->getDefaultPrintableProperties();
        $row = "<div class = 'articol-card-row'>" . PHP_EOL;

        // afisare atribute articol
        foreach ($propertiesToDisplay as $propName => $propValue) {
           $row .= wrapDiv($propValue, "articol-card-cell", "articol-card-$propName");
        }

        // afisare lista imagini
        $row .= $this->buildHtmlPagesThumbnails();

        $row .= "</div>" . PHP_EOL;
        return $row;
    }

    // TODO needs refactoring ('Image' class?)
    /**
     * Construieste thumbnails pagini cu linkuri catre imaginea mare
     * Returneaza un string cu html pentru afisare in tabel
     */
    private function buildHtmlPagesThumbnails()
    {
        $thumbsRow = "<div class = 'articol-card-cell', 'articol-card-lista-minithumb'>" . PHP_EOL;
        $baseDestinationLink = getBaseUrl() . "?editie={$this->editiaParinte->editieId}";
        // genereaza fiecare minithumb cu link catre imaginea mare
        foreach($this->listaPagini as $pageNo => $imageBaseName)
        {
            $destinationLink = $baseDestinationLink . "&pagina=$pageNo";
            $imageThumb = getImageThumbPath($this->editiaParinte->editieDirPath, $imageBaseName);
            $thumbsRow .= getImageWithLink($imageThumb, $destinationLink, "minithumb")."  ";
        }
        $thumbsRow .= "</div>" . PHP_EOL;
        return $thumbsRow;
    }

    /**
     * Proprietatile articolului care vor fi afisate in html
     */
    private function getDefaultPrintableProperties()
    {
        return array(
            "pagina"          => $this->pageToc,
            "rubrica"         => $this->rubrica,
            "titlu"           => $this->titlu,
            "autor"           => $this->autor,
//            "lista-minithumb" => implode(", ", $this->listaPagini)
        );
    }

    /**
     * Construieste numele imaginii fara extensie
     * (ex: 'Level 1999 12 002' fara spatii)
     */
    private function buildPaginaBaseName($pageNo)
    {
        return $this->editiaParinte->editieBaseName
               . padLeft($pageNo, PAGINA_PAD);
    }

}