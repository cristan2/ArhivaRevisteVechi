<?php

namespace ArhivaRevisteVechi\lib;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;
use ArhivaRevisteVechi\lib\Pagina;
use ArhivaRevisteVechi\resources\db\DBC;

require_once("../resources/config.php");
require_once HELPERS . "/h_misc.php";
require_once HELPERS . "/h_html.php";
require_once HELPERS . "/HtmlPrinter.php";
require_once LIB     . "/Pagina.php";


/**
 * Class Editie
 * Se bazeaza pe informatiile din baza de date si/sau pe
 * imaginile scanate de pe disc.
 *
 * In cazul in are sursa primara e baza de date, se construieste
 * calea catre directorul si paginile editiei in functie de anul, luna
 * si/sau numarul obtinute din baza de date. In acest caz, requestul GET
 * contine doar id-ul editiei.
 *
 * Daca nu exista informatii in baza de date, se scaneaza directorul
 * cu imagini salvate si se reconstituie unele informatii despre editii
 * pe baza numelui revistei, anului si subdirectoarelor care reprezinta
 * editiile, ce pot avea fie numele lunii (ex: 09 = septembrie),
 * fie numarul editiei (Nr. 9). Requestul GET contine aceste 3 valori.
 *
 * Directoarele care reprezinta luna contin doar numarul lunii, iar cele
 * care reprezinta numarul sunt prefixate de una din valorile acceptate
 * din $issuePrefixes.
 */
class Editie
{
    // --- static attrs ---
    static $issuePrefixes = ['Nr. ', '#'];
    const EDITIE_FULL     = 0;
    const EDITIE_PREVIEW  = 1;

    // --- base attrs ---
    public  $numeRevista, $revistaId;
    public  $an, $luna, $numar, $editieId;
    public  $maxNumPages;
    private $numarArticole;

    private $coperta;


    // --- location attrs ---
    // calea catre directorul in care sta editia (in general e anul)
    public  $editieHomeDirPath;

    // editieHomeDirPath + editieDirName
    // Numele Revistei + Anul + numarul din editieDirName
    public  $editieDirPath;


    // --- identification attrs ---

    // numele directorului editiei (dat de luna sau numarul editiei)
    public $editieDirName;            // poate fi "01" sau "Nr. 01"
    public $editieDirNameNumericVal;  // adica "01" din "Nr. 01"

    // radacina comuna a numelor imaginilor din director
    public  $editieBaseNameForPages;  // adica Level199901


    // --- content attrs ---
    public  $listaArticole;
    public  $listaPagini;


    // --- external attrs ---
    public $linkuriDownload;
    public $linkWiki;

    // --- content flags ---

    /* denota daca editia e construita doar citind
       imaginile scanate, fara info din DB */
    private $isBuiltFromDisk = false;
    public $typeIsPreview = false;

    // TODO unde naiba am vrut sa-l folosesc pe asta?
    /* denota daca numele directorului este dat de luna
       aparitiei (02 = luna februarie) sau numarul editiei
       (ex: 02 = al doilea numar aparut) */
    private $baseDirAreNumarulLunii = true;

    public  $areArticoleIndexate, $arePaginiScanate;

    // TODO in functie de numarul de editii si numarul de aparitii total
    public  $isFirst, $isLast;

    public function __construct($dbRow, $editieTypeToBuild)
    {

        /* ******** base attrs ******** */
        $this->numeRevista              = $dbRow[DBC::REV_NUME];
        $this->an                       = $dbRow[DBC::ED_AN];

        if (isset($dbRow['isBuiltFromDisk'])) {
            /* --- doar pentru revistele de pe disc --- */
            $this->isBuiltFromDisk      = true;

        } else {
            /* --- doar pentru revistele din DB --- */
            $this->revistaId            = $dbRow[DBC::REV_ID];
            $this->editieId             = $dbRow[DBC::ED_ID];
            $this->maxNumPages          = $dbRow[DBC::ED_PG_CNT];
        }

        /* ******** location & identification attrs ******** */

        // homeDir e construit pe baza numelui revistei, deci
        // nu conteaza de unde vine numele, e comun
        $this->editieHomeDirPath        = $this->buildHomeDirPath();

        if ($this->isBuiltFromDisk) {
            // construim Editia pe baza informatiilor scanate de pe disc
            // si trimise prin requestul GET
            $this->editieDirNameNumericVal      = $dbRow['$editie'];

            // check $editieDirNo as luna
            $dirAsLuna = $this->getDirNameFromLuna($this->editieDirNameNumericVal);

            if ($dirAsLuna) {
                // deci numele directorului==numarul lunii
                $this->editieDirName            = $dirAsLuna;
                $this->luna                     = $this->editieDirNameNumericVal;
                $this->baseDirAreNumarulLunii   = true;

            } else {
                // check $editieDirNo as issue
                $dirAsIssue = $this->getDirNameFromIssueNo($this->editieDirNameNumericVal);
                if ($dirAsIssue) {

                    // deci numele directorului==numarul editiei
                    $this->editieDirName        = $dirAsIssue;
                    $this->numar                = $this->editieDirNameNumericVal;
                    // $this->luna;                     // nu avem de unde sa stim luna
                    $this->baseDirAreNumarulLunii = false;
                } else {
                    // TODO default sau exceptie
                    // directorul nu a fost gasit - nu mai exista sau are alt prefix
                }
            }

        } else {
            // construim Editia pe baza informatiilor primite din baza de date
            $this->luna                         = $dbRow[DBC::ED_LUNA];
            $this->numar                        = $dbRow[DBC::ED_NUMAR];

            // check daca numele directorului==numarul lunii
            $dirAsLuna = $this->getDirNameFromLuna($this->luna);
            if ($dirAsLuna) {

                $this->editieDirName            = padLeft($this->luna, LUNA_PAD);
                $this->editieDirNameNumericVal  = padLeft($this->luna, LUNA_PAD);
                $this->baseDirAreNumarulLunii   = true;

            } else {

                // check daca numele directorului==numarul editiei
                $dirAsIssue = $this->getDirNameFromIssueNo($this->numar);
                if ($dirAsIssue) {

                    $this->editieDirName           = $dirAsIssue;
                    $this->editieDirNameNumericVal = $this->numar;
                    $this->baseDirAreNumarulLunii  = false;

                } else {
                    // TODO default sau exceptie
                    // directorul nu a fost gasit - nu mai exista sau are alt prefix
                }
            }
        }

        // TODO trebuie mutat in conditiile de mai sus
        $this->editieDirPath            = $this->buildEditieDirPath();
        $this->editieBaseNameForPages   = $this->buildEditieBaseName();
        $this->coperta                  = new Pagina($this, 1);


        /* ******** content attrs ******** */
        // fiecare articol se adauga singur in acest array
        // $this->listaArticole = array();
//
        if ($editieTypeToBuild == self::EDITIE_PREVIEW) {
            $this->typeIsPreview = true;
        }

        $this->arePaginiScanate = $this->countPaginiScanate() > 0;

        /* ******** pagini ******** */
        if ($editieTypeToBuild == self::EDITIE_FULL) {
            // $listaPagini = array();
            if ($this->isBuiltFromDisk) {

                // TODO implement

            } else {

                // TODO si daca nu avem maxNumPages?
                // dar pot exista intrari in DB pentru editie, dar sa nu avem maxNumPages?
                // anyway, putem lua pg_toc de la ultimul articol sau paginile de pe disc
                $this->buildPages(1, $this->maxNumPages);
            }
        }

        /* ******** downloads ******** */
        /** linkurile de download sunt setate separat
         * de catre articole.php (doar aici sunt utilizate)
         * deoarece e nevoie de un query separat
         */
        // $this->linkuriDownload = buildLinkuriDownload();
        $this->linkWiki = $this->buildLinkWiki();


        /* ******** extras ******** */
        if (isset($dbRow[DBC::ED_ART_CNT])) {
            // coloana asta nu e inclusa in toate query-urile,
            // e relevanta doar in atunci cand construim mai multe editii
            // (ex: pagina cu editii sau rezultate cautare)
            $this->numarArticole  = $dbRow[DBC::ED_ART_CNT];
            if ($this->numarArticole > 0) $this->areArticoleIndexate = true;
        }
    }

    // TODO de inlocuit cu $revistaMama->revistaDirPath
    /**
     * Construieste calea catre directorul anului din care face parte editia
     */
    private function buildHomeDirPath()
    {
        return IMG . DIRECTORY_SEPARATOR
            . strtolower($this->numeRevista) . DIRECTORY_SEPARATOR
            . $this->an;
    }

    /**
     * Verifica daca numele directorului reprezinta *luna aparitiei*
     * si returneaza calea catre director daca exista
     */
    private function getDirNameFromLuna($editieDirNo)
    {
        $editieDirNo = padLeft($editieDirNo, LUNA_PAD);
        $dirWithPath = $this->editieHomeDirPath
            . DIRECTORY_SEPARATOR . $editieDirNo;
        if (is_dir($dirWithPath)) return basename($dirWithPath);
        else return false;
    }

    /**
     * Verifica daca numele directorului reprezinta *numarul editiei*
     * si returneaza calea catre director daca exista
     */
    private function getDirNameFromIssueNo($editieDirNo)
    {
        // check exact match
        foreach(self::$issuePrefixes as $prefix) {
            $tempDir = $this->editieHomeDirPath
                . DIRECTORY_SEPARATOR . $prefix . $editieDirNo;
            if (is_dir($tempDir)) return basename($tempDir);
        }

        // check partial match
        $allSubdirs = getDirsInPath($this->editieHomeDirPath);
        foreach($allSubdirs as $subDir) {
            $simpleDirName = basename($subDir);
            foreach(self::$issuePrefixes as $prefix) {
                $tempDir = $this->editieHomeDirPath
                    . DIRECTORY_SEPARATOR . $prefix . $editieDirNo;
                if (startsWith($simpleDirName, $tempDir)) return basename($subDir);
            }
        }
    }

    /**
     * Construieste calea catre directorul imaginilor unei reviste
     * (ex img/level/1999/12)
     */
    private function buildEditieDirPath() {
        return
            $this->editieHomeDirPath . DIRECTORY_SEPARATOR
            . $this->editieDirName;
    }

    /**
     * Construieste radacina comuna a numelor imaginilor din director,
     * compusa din Numele Revistei, Anul si Luna aparitiei,
     * care va fi folosita ulterior pentru a construi
     * numele fiecarei imagini. Exemplu:
     * * editie base name = 'Level 1999 12' (fara spatii)
     * * pagina 1 base name = Level199912001
     * * pagina 1 imagine full = Level199912001.jpg
     * * pagina 1 imagine thumb = /th/Level199912001_th.jpg
     */
    private function buildEditieBaseName()
    {
        return  ucfirst(strtolower($this->numeRevista
        . $this->an
        . $this->editieDirNameNumericVal));
    }

    public function countPaginiScanate()
    {
        $filecount = 0;
        $files = getImageFilesInDir($this->editieDirPath);
        if ($files) $filecount = count($files);
        return $filecount;
    }

    public function setDownloadLinks($dldLinksArray)
    {
        $this->linkuriDownload = $dldLinksArray;
    }

    public function buildPages($startPage, $maxPages)
    {
        for ($idx = 0; $idx < $maxPages; $idx++) {
            $currPageNo = $startPage + $idx;
            $this->listaPagini[$currPageNo] = new Pagina($this, $currPageNo);
        }
    }

    private function buildLinkWiki()
    {
        $strippedNr = ltrim($this->editieDirNameNumericVal, "0");
        $wikiLink = lcfirst($this->numeRevista)
                    . ":" . $this->an
                    . ":" . $strippedNr;
        return RVWIKI_BASE_LINK . "/" . $wikiLink;
    }

    /* ************************************************* */
    /* ****************   HTML OUTPUT   **************** */

    // TODO REFACTOR & rename to getHtmlCardOutput
    /**
     * Construieste carduri reviste
     * Cu 3 sectiuni: Imagine, Titlu si Subtitlu
     * In plus, primeste un array cu clasele CSS
     */
    function getHtmlOutput($useSearchLayout = false)
    {
        $previewContinut = $this->outputPreviewContinut();

        $htmlClass = $useSearchLayout ? "reviste-cards-search" : "reviste-cards";
        $attrsToPrint = array(
            "imagine card"  => $this->outputCopertaWithLink(),
            "detalii card"  => HtmlPrinter::buildCardDiv(array(
                "titlu card"    => "<h1>{$this->outputTitluPeScurt()}</h1>",
                "subtitlu card" => "<h2>{$this->outputNumar()}</h2>",
            ), "reviste-cards-detail"),
            "preview continut" => "<div
                                    title = '{$previewContinut['tooltip']}'
                                    class = 'reviste-cards-preview-icon {$previewContinut['cssCuloare']}'>
                                    </div>"
        );
        return HtmlPrinter::buildCardDiv($attrsToPrint, $htmlClass);
    }

    /**
     * Construieste titlu pentru pagina articole
     * ex: "Level nr. 24"
     */
    public function outputTitluDetaliat($useTitleAsHomeLink = false)
    {
        $title = $this->outputTitluCuNumeRevista();
        if ($useTitleAsHomeLink) {
            return HtmlPrinter::wrapLink($title, $this->getEditieUrl(), "header-nav-home-link");
        }
        return $title;
    }

    /**
     * Construieste subtitlu pentru pagina articole
     * ex: "(septembrie 1999)"
     */
    public function outputInfoEditie()
    {
        return "(". convertLuna($this->luna) ." {$this->an})";
    }

    public function printAllPagesThumbsToHtml()
    {
        $output = "";
        for ($pg = 1; $pg <= $this->maxNumPages; $pg++) {
            $output .= $this->listaPagini[$pg]->getThumbWithLinkToFullImage()."  ";
        }
        return $output;
    }

    public function printPageToHtml($pageNo)
    {

    }

    public function outputTitluCuNumeRevista()
    {
        return "{$this->numeRevista} nr. {$this->numar}";
    }

    /**
     * Construieste titlu pentru carduri
     * ex: "9 / 1999" (luna) sau "#9 / 1999" (numar)
     */
    private function outputTitluPeScurt()
    {
        if ($this->isBuiltFromDisk) {
            return "&#35;$this->numar / $this->an";
        }
        return "$this->luna / $this->an";
    }

    /**
     * Construieste... n-ai sa ghicesti ce
     */
    private function outputCopertaWithLink()
    {
        return $this->coperta->getThumbWithLinkToEditie("card-img");
    }

    /**
     * Construieste subtitlu pentru carduri
     * ex: "Nr. 24"
     */
    private function outputNumar()
    {
        return "Nr. " . $this->numar;
    }

    private function outputPreviewContinut()
    {
        // are articole + pagini scanate = "editie completa: x articole", verde
        if ($this->areArticoleIndexate && $this->arePaginiScanate) {
            $tooltipArticole = "Ediţie completă: $this->numarArticole articole";
            $cssClass = "reviste-cards-preview-icon-complet";

        // are doar pagini scanate = "editie fara cuprins", orange
        } elseif ($this->arePaginiScanate) {
            $tooltipArticole = "Ediţie fără cuprins";
            $cssClass = "reviste-cards-preview-icon-doar-scan";

        // doar articole = "editie fara pagini scanate", albastru-gri
        } elseif ($this->areArticoleIndexate) {
            $tooltipArticole = "Ediţie fără pagini scanate";
            $cssClass = "reviste-cards-preview-icon-doar-cuprins";

        // fara nimic = "editie fara continut", gri
        } else {
            $tooltipArticole = "Ediţie fără conţinut";
            $cssClass = "reviste-cards-preview-icon-no-content";
        }

        return array(
            "tooltip"    => $tooltipArticole,
            "cssCuloare" => $cssClass
        );
    }

    // TODO trebuie conditie daca nu exista editie id
    /**
     * Construieste link catre pagina unei editii
     */
    public function getEditieUrl()
    {
        if ($this->isBuiltFromDisk) {
            $queryParams = DBC::REV_NUME . "=$this->numeRevista"
                . "&" . DBC::ED_AN . "=$this->an"
                . "&" . "editie=$this->editieDirNameNumericVal";
        } else {
            $queryParams = "editie-id=$this->editieId";
        }

        return ARHIVA."/articole.php?$queryParams";
    }

    /**
     * Array-ul scos din rezultatul din DB e un array 3D
     * de forma categorie => itemNo => download_links array
     * ex: ['CD'] => (['2'] => ('mediafire.com/asdf', 'dropbox.com/asdf'))
     */
    public function outputLinkuriDownload()
    {
        // link catre RevisteVechi Wiki
        $outputLinkWiki = HtmlPrinter::wrapLink("Pagina Wiki RevisteVechi", $this->linkWiki, "external-links");

        // linkuri de download, daca exista, pentru Revista, CD-uri etc.
        $outputLinkuriDownload = count($this->linkuriDownload) > 0 ? ", Download: " : "";
        $linkuriDownload = array();
        foreach ($this->linkuriDownload as $categ => $arrayItemAndLinks) {
            foreach ($arrayItemAndLinks as $item => $arrayLinks) {
                $linkuriDownload[] = $this->getPrettyLinkForDownloadCateg($categ, $item, $arrayLinks);
            }
        }
        $outputLinkuriDownload .= implode(", ", $linkuriDownload);

        return $outputLinkWiki . $outputLinkuriDownload;
    }

    /**
     * Construieste un string cu linkuri de download pentru o categorie.
     * Daca exista mai multe items, fiecare item no va fi
     * appendat la categorie, ex: CD 1, CD 2.
     * Daca exista mai multe linkuri, vor fi separate cu '|'
     * ex: "Revista (Scribd | Archive.org)"
     * @return: un string cu linkuri de forma: CD1 (Mediafire | Dropbox), CD2 (...)
     */
    private function getPrettyLinkForDownloadCateg($categ, $item, $linksArray)
    {
        $totalLinks = count($linksArray);
        // nu ar trebui sa fie niciodata 0, query-ul filtreaza intrarile goale
        // if ($totalLinks == 0) return "";

        $outputCateg = ucfirst($categ) . ($categ == 'revista' ? "" : $item);
        $outputLinks = array();

        foreach($linksArray as $link) {
            $destinationScreenName = extractKnownLinkName($link);
            $outputLinks[] = HtmlPrinter::wrapLink($destinationScreenName, $link);
        }

        return "$outputCateg (" . implode(" | ", $outputLinks) . ")";
    }

    /* ********************************************* */
    /* ****************   FACTORY   **************** */

    /**
     * Construieste informatiile necesare pentru o editie
     * pe baza directoarelor si imaginilor de pe disc
     * in loc de informatiile din baza de date:
     *   get revista name
     *   look in revista dir
     *   get dirs ani
     *   foreach fiecare an
     *   |- get dirs luni/numere
     *      foreach luna/numar*
     *      |- get prima imagine din fiecare
     *      |- make EditieSurogat doar cu imaginea aia
     */
    static function getEditiiArrayFromNumeRevista($revista)
    {
        $arrayEditiiSurogat = array();

        // citeste directoare ani
        $directoareAni = getDirsInPath($revista->revistaDirPath);
        if ($directoareAni) {
            foreach($directoareAni as $anulCurent) {

                // citeste directoare editii (reprezentand fie lunile aparitiei, fie numerele propriu-zise)
                $directoareEditii = getDirsInPath($anulCurent);
                if ($directoareEditii) {
                    foreach($directoareEditii as $editiaCurenta) {

                        // citeste fisiere
                        // daca exista imagini scanate, construim editia
                        $imaginiScanate = getImageFilesInDir($editiaCurenta);
                        if ($imaginiScanate) {

                            // info exclusiv cand construim de pe disc
                            $numeRevista    = $revista->numeRevista;
                            $anEditie       = basename($anulCurent);
                            $dirNo          = cleanPrefixFromName(basename($editiaCurenta), self::$issuePrefixes);
                            $arrayEditiiSurogat[] = self::getEditieFromDisk($numeRevista, $anEditie, $dirNo, self::EDITIE_PREVIEW);
                        }
                    }
                }
            }
        }

        return $arrayEditiiSurogat;
    }

    static function getEditieFromDisk($numeRevista, $anEditie, $dirNo, $editieTypeToBuild)
    {
        $editieInfo = array();

        $editieInfo[DBC::REV_NUME]      = $numeRevista;
        $editieInfo[DBC::ED_AN]         = $anEditie;
        $editieInfo['$editie']          = $dirNo;
        $editieInfo['isBuiltFromDisk']  = true;

        return new Editie($editieInfo, $editieTypeToBuild);
    }
}