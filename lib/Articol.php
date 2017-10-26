<?php
namespace ArhivaRevisteVechi\lib;

require_once("../resources/config.php");
require_once HELPERS . "/h_images.php";
use ArhivaRevisteVechi\resources\db\DBC;

class Articol
{
    public $titlu, $rubrica, $autor;
    public $articolId, $pageToc, $pageCount;
    public $listaPagini;

    private $editiaParinte;


    public function __construct($dbRow, Editie $editiaParinte)
    {
        // info editia-parinte
        $this->editiaParinte = $editiaParinte;

        // info articol
        $this->articolId     = $dbRow[DBC::ART_ID];
        $this->pageToc       = $dbRow[DBC::ART_PG_TOC];
        $this->rubrica       = $dbRow[DBC::ART_RUBRICA];
        $this->titlu         = $dbRow[DBC::ART_TITLU];
        $this->autor         = $dbRow[DBC::ART_AUTOR];
        $this->pageCount     = $dbRow[DBC::ART_PG_CNT];

        // info pagini
        $this->listaPagini   = $this->buildPagini();

        // adauga articol in lista din editia-parinte
        $this->editiaParinte->listaArticole[$this->articolId] = $this;
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
     *
     * @param $showSearchProperties - determina daca vor fi
     * afisate si informatiile referitoare la editia din care
     * face parte, in listele agregate (rezultat cautare)
     */
    public function getHtmlOutput($showSearchProperties = false)
    {
        $propertiesToDisplay = $showSearchProperties
            ? $this->getSearchResultProperties()
            : $this->getDefaultPrintableProperties();
        $row = "<div class = 'articol-card-row'>" . PHP_EOL;

        // afisare atribute articol
        foreach ($propertiesToDisplay as $propName => $propValue) {
           $row .= wrapDiv($propValue, "articol-card-cell", "articol-card-$propName");
        }

        // afisare lista imagini
        $row .= $this->buildHtmlPagesThumbnails($showSearchProperties);

        $row .= "</div>" . PHP_EOL;
        return $row;
    }

    // TODO needs refactoring ('Imagine'/'Pagina' class?)
    /**
     * Construieste thumbnails pagini cu linkuri catre imaginea mare
     * Returneaza un string cu html pentru afisare in tabel
     */
    public function buildHtmlPagesThumbnails($useDirectLinkToImage = false)
    {
        $thumbsRow = "<div class = 'articol-card-cell articol-card-lista-microthumb'>" . PHP_EOL;
        if ($useDirectLinkToImage) {
            $baseDestinationLink = $this->editiaParinte->editieDirPath;
        } else {
            $baseDestinationLink = getBaseUrl()
                                    . "?editie={$this->editiaParinte->editieId}"
                                    . "&articol={$this->articolId}";
        }
        // genereaza fiecare microthumb cu link catre imaginea mare
        foreach($this->listaPagini as $pageNo => $imageBaseName)
        {
            if ($useDirectLinkToImage) {
                $destinationLink = getImagePath($baseDestinationLink, $imageBaseName);
            } else {
                $destinationLink = $baseDestinationLink . "&pagina=$pageNo";
            }

            $imageDir = $this->editiaParinte->editieDirPath;
            $imageThumb = getImageThumbPath($imageDir, $imageBaseName);
            $thumbsRow .= getImageWithLink($imageThumb, $destinationLink, "microthumb")."  ";
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
            "pagina"  => $this->pageToc,
            "rubrica" => $this->rubrica,
            "titlu"   => $this->titlu,
            "autor"   => $this->autor,
        );
    }

    private function getSearchResultProperties()
    {
        return array(
            "nume"    => $this->editiaParinte->numeRevista,
            "an"      => $this->editiaParinte->an,
            "luna"    => $this->editiaParinte->luna,
            "rubrica" => $this->rubrica,
            "titlu"   => $this->titlu,
            "autor"   => $this->autor,
        );
    }

    // TODO delete, e o functie si in Pagina.php
    /**
     * Construieste numele imaginii fara extensie
     * (ex: 'Level 1999 12 002' fara spatii)
     */
    private function buildPaginaBaseName($pageNo)
    {
        return $this->editiaParinte->editieBaseNameForPages
               . padLeft($pageNo, PAGINA_PAD);
    }

}