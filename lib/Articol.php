<?php
namespace ArhivaRevisteVechi\lib;

require_once("../resources/config.php");
require_once HELPERS . "/h_misc.php";
require_once HELPERS . "/HtmlPrinter.php";
use ArhivaRevisteVechi\resources\db\DBC;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;

class Articol
{
    public $titlu, $rubrica, $autor;
    public $articolId, $pageToc, $pageCount, $nota;
    public $listaPagini;

    public $editiaParinte;


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
        $this->nota          = $dbRow[DBC::ART_NOTA];

        // info pagini
        $this->listaPagini   = $this->buildPagini();

        // adauga articol in lista din editia-parinte
        $this->editiaParinte->listaArticole[$this->articolId] = $this;
        if ($editiaParinte->typeIsPreview) $editiaParinte->buildPages($this->pageToc, $this->pageCount);
    }

    /**
     * Construieste array doar cu numerele paginilor pe care
     * le cuprinde articolul; aceste imagini vor fi folosite ca
     * referinta pentru array-ul de imagini din Editie
     */
    private function buildPagini ()
    {
        $listaPagini = array();
        for ($pgIndex = 0; $pgIndex < ($this->pageCount); $pgIndex++) {
            $currentPageNo = $this->pageToc + $pgIndex;
            $listaPagini[] = $currentPageNo;
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

        /* afisare atribute articol */
        foreach ($propertiesToDisplay as $propName => $propValue) {
           $row .= wrapDiv($propValue, "articol-card-cell", "articol-card-$propName");
        }

        /* afisare lista imagini */
        $row .= $this->buildHtmlPagesMicroThumbs($showSearchProperties);

        /* span-link pentru clickable div */
        // solutia de aici https://stackoverflow.com/questions/796087/make-a-div-into-a-link

        // TODO page count poate lipsi la unele articole, deci listaPagini va fi 0
        // si prefer sa pun verificarea asta aici decat o valoare default pentru pageCount
        // in constructor ca sa se vada mai usor cand lipsesc pagini
        if (count($this->listaPagini) > 0) {
            $linkToArticol = $this->getArticolUrl();
            $clickableSpan = "<span class = 'clickable-div-span' ></span>";
            $row .= HtmlPrinter::wrapLink($clickableSpan, $linkToArticol , "clickable-div-link");
        }
        $row .= "</div>" . PHP_EOL;
        return $row;
    }

    /**
     * Construieste thumbnails pagini cu linkuri catre imaginea mare
     * Returneaza un string cu html pentru afisare in tabel
     */
    public function buildHtmlPagesMicroThumbs($useDirectLinkToImage = false)
    {
        $thumbsRow = "<div class = 'articol-card-cell articol-card-lista-microthumb'>" . PHP_EOL;
        foreach($this->listaPagini as $pageNo /*=> $imageBaseName*/)
        {
            $pagina = $this->editiaParinte->listaPagini[$pageNo];
            if ($useDirectLinkToImage) {
                $thumbsRow .= $pagina->getMicroThumbWithLinkToFull();
            } else {
                $thumbsRow .= $pagina->getMicroThumbWithLinkToPaginaDinArticol($this->articolId);
            }
        }
        $thumbsRow .= "</div>" . PHP_EOL;
        return $thumbsRow;
    }

    public function getArticolUrl()
    {
        return $this->editiaParinte->getEditieUrl() . "&articol=$this->articolId";
    }

    public function getHtmlOutputTitle()
    {
        return "<div class = 'articol-nav-title-container'>
          <h3 class = 'articol-nav-rubrica'>$this->rubrica</h3>"
          . (!empty($this->rubrica) && !empty($this->titlu) ? "<hr>" : "")
          . "<h2>$this->titlu</h2>"
          . (!empty($this->autor) ? "<h3>($this->autor)</h3>" : "")
          . "</div>";
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
            "nota"    => $this->nota,
        );
    }

    // TODO delete, e o functie si in Pagina.php
    /**
     * Construieste numele imaginii fara extensie
     * (ex: 'Level 1999 12 002' fara spatii)
     */
//    private function buildPaginaBaseName($pageNo)
//    {
//        return $this->editiaParinte->editieBaseNameForPages
//               . padLeft($pageNo, PAGINA_PAD);
//    }

}