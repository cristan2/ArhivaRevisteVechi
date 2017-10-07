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

    /**
     * Construieste un container de tip <div> ce contine
     * atributele printabile ale obiectului impachetate in
     * sub-elemente <div> separate
     */
    public function getHtmlOutput()
    {
        $propertiesToDisplay = $this->getDefaultPrintableProperties();
        $row = "<div class = 'articol-card-row'>" . PHP_EOL;
        foreach ($propertiesToDisplay as $propName => $propValue) {
           $row .= wrapDiv($propValue, "articol-card-cell", "articol-card-$propName");
        }
        $row .= "</div>" . PHP_EOL;
        return $row;
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
            "lista-minithumb" => implode(", ", $this->listaPagini)
        );
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
     * Construieste numele imaginii fara extensie
     * (ex: 'Level 1999 12 002' fara spatii)
     */
    private function buildPaginaBaseName($pageNo)
    {
        return $this->editiaParinte->editieBaseName
               . padLeft($pageNo, PAGINA_PAD);
    }

}