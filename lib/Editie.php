<?php

namespace ArhivaRevisteVechi\lib;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;
use ArhivaRevisteVechi\lib\Pagina;
use ArhivaRevisteVechi\resources\db\DBC;

require_once("../resources/config.php");
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_misc.php";
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


    // --- content flags ---

    /* denota daca editia e construita doar citind
       imaginile scanate, fara info din DB */
    private $isBuiltFromDisk = false;

    /* denota daca numele directorului este dat de luna
       aparitiei (02 = luna februarie) sau numarul editiei
       (ex: 02 = al doilea numar aparut) */
    private $baseDirAreNumarulLunii = true;

    public  $areArticoleIndexate, $arePaginiScanate;

    // TODO in functie de numarul de editii si numarul de aparitii total
    public  $isFirst, $isLast;

    public function __construct($dbRow)
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
            $this->editieDirNameNumericVal      = $dbRow['$editieDirNo'];

            // check $editieDirNo as luna
            $dirAsLuna = $this->getDirNameFromLuna($this->editieDirNameNumericVal);
            if ($dirAsLuna) {

                if (IS_DEBUG) echo ("From Disk & dir is luna, dirAsLuna = $dirAsLuna <br>");

                // deci numele directorului==numarul lunii
                $this->editieDirName            = $dirAsLuna;
                $this->luna                     = $this->editieDirNameNumericVal;
                $this->baseDirAreNumarulLunii   = true;

            } else {

                if (IS_DEBUG) echo ("From Disk & dir is NOT luna, dirAsLuna = $dirAsLuna <br>");

                // check $editieDirNo as issue
                $dirAsIssue = $this->getDirNameFromIssueNo($this->editieDirNameNumericVal);
                if ($dirAsIssue) {

                    // deci numele directorului==numarul editiei
                    $this->editieDirName        = $dirAsIssue;
                    $this->numar                = $this->editieDirNameNumericVal;
                    // $this->luna;                     // nu avem de unde sa stim luna
                    $this->baseDirAreNumarulLunii = false;
                } else {
                    // TODO exceptie
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

                if (IS_DEBUG) echo ("From DB & dir is luna, dirAsLuna = $dirAsLuna " . "<br>");

                $this->editieDirName            = padLeft($this->luna, LUNA_PAD);
                $this->editieDirNameNumericVal  = padLeft($this->luna, LUNA_PAD);
                $this->baseDirAreNumarulLunii   = true;

            } else {

                // check daca numele directorului==numarul editiei
                $dirAsIssue = $this->getDirNameFromIssueNo($this->numar);
                if ($dirAsIssue) {

                    if (IS_DEBUG) echo ("From DB & dir is NOT luna, dirAsIssue = $dirAsIssue <br>");

                    $this->editieDirName        = $dirAsIssue;
                    $this->editieDirNameNumericVal  = $this->numar;
                    $this->baseDirAreNumarulLunii = false;

                } else {
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
        $listaArticole = array();
        $listaPagini = array();

        $this->arePaginiScanate = $this->countPaginiScanate() > 0;


        /* ******** coperta ******** */
//        if (isset($dbRow['isBuiltFromDisk'])) {
//            $this->copertaPath          = $dbRow['copertaPath'];
//        }

        /* ******** extras ******** */
        if (isset($dbRow[DBC::ED_ART_CNT])) {
            // coloana asta nu e inclusa in toate query-urile,
            // e relevanta doar in pagina cu toate editiile (?)
            $this->numarArticole  = $dbRow[DBC::ED_ART_CNT];
            $this->areArticoleIndexate = true;
        }
    }

    /**
     * Construieste calea catre directorul anului din care face parte editia
     * // TODO de inlocuit cu $revistaMama->revistaDirPath
     */
    private function buildHomeDirPath()
    {
        return IMG . DIRECTORY_SEPARATOR
            . strtolower($this->numeRevista) . DIRECTORY_SEPARATOR
            . $this->an;
    }

    /**
     * Construieste numele directorului editiei
     * (dat de luna sau numarul editiei)
     * adica "Nr. 12" din "img/level/1999/Nr. 12"
     */
    private function buildEditieDirName($editieDirNo = "0")
    {

    }


    private function getDirNameFromLuna($editieDirNo)
    {
        $editieDirNo = padLeft($editieDirNo, LUNA_PAD);
        $dirWithPath = $this->editieHomeDirPath
            . DIRECTORY_SEPARATOR . $editieDirNo;
        if (is_dir($dirWithPath)) return basename($dirWithPath);
        else return false;
    }

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
        return  $this->editieHomeDirPath . DIRECTORY_SEPARATOR
//            . $this->an . DIRECTORY_SEPARATOR
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


    /* ************************************************* */
    /* ****************   HTML OUTPUT   **************** */

    // TODO REFACTOR
    /**
     * Construieste carduri reviste
     * Cu 3 sectiuni: Imagine, Titlu si Subtitlu
     * In plus, primeste un array cu clasele CSS
     */
    function getHtmlOutput($useSearchLayout = false)
    {
        $htmlClass = $useSearchLayout ? "reviste-cards-search" : "reviste-cards";
        $attrsToPrint = array(
            "imagine card"  => $this->outputCopertaWithLink(),
            "titlu card"    => "<h1>{$this->outputTitluPeScurt()}</h1>",
            "subtitlu card" => "<h2>{$this->outputNumar()}</h2>",
            // TODO temporar
//            "info card"     => "<p>{$this->numarArticole} art, {$this->countPaginiScanate()} pg. scanate</p>"
        );
        return HtmlPrinter::buildCardDiv($attrsToPrint, $htmlClass);
    }

    /**
     * Construieste titlu pentru pagina articole
     * ex: "Level nr. 24"
     */
    public function outputTitluDetaliat()
    {
        return "{$this->numeRevista} nr. {$this->numar}";
    }

    /**
     * Construieste subtitlu pentru pagina articole
     * ex: "(septembrie 1999)"
     */
    public function outputInfoEditie()
    {
        return "(". convertLuna($this->luna) ." {$this->an})";
    }

    /**
     * Construieste titlu pentru carduri
     * ex: "9 / 1999"
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
        $targetLink = $this->getEditieUrl();

//        if ($this->isBuiltFromDisk) {
//            $imgThumbSrc = $this->copertaPath;
//        } else {
//            // nume baza pentru imaginea copertii
//            $copertaBaseImg = $this->editieBaseNameForPages . padLeft(1, PAGINA_PAD);
//
//            // calea catre thumbnail
//            $imgThumbSrc = getImageThumbPath($this->editieDirPath, $copertaBaseImg);
//        }

        $copertaThumbPath = $this->coperta->thumbPath;

        if (IS_DEBUG) var_dump($this->coperta);

        // imaginea copertii: thumb cu link catre imaginea full
        return getImageWithLink($copertaThumbPath, $targetLink, "card-img");
    }

    /**
     * Construieste subtitlu pentru carduri
     * ex: "Nr. 24"
     */
    private function outputNumar()
    {
        return "Nr. " . $this->numar;
    }

    // TODO trebuie conditie daca nu exista editie id
    /**
     * Construieste link catre pagina unei editii
     */
    public function getEditieUrl()
    {
        // TODO
//        if ($this->isBuiltFromDisk) {
//            return ARHIVA."/articole.php?revista=$this->numeRevista&an=$this->an&editie=NOED";
//        }
        return ARHIVA."/articole.php?editie-id=$this->editieId";
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
    static function getEditiiArrayFrom($revista)
    {

        // $isEditiaNumerotataCuLuna = function ($basename) { return startsWith($basename, "Nr. ");};

        $arrayEditiiSurogat = array();

        // citeste ani revista
        $directoareAni = getDirsInPath($revista->revistaDirPath);
        if ($directoareAni) {
            foreach($directoareAni as $anulCurent) {

                // citeste editii (organizate pe luni sau numere)
                $directoareEditii = getDirsInPath($anulCurent);
                if ($directoareEditii) {
                    foreach($directoareEditii as $editiaCurenta) {

                        // $editiaIsLuna = $isEditiaNumerotataCuLuna($editiaCurenta);

                        // citeste fisiere
                        // daca exista imagini scanate, construim editia
                        $imaginiScanate = getImageFilesInDir($editiaCurenta);
                        if ($imaginiScanate) {

                            $editieInfo = array();

                            $editieInfo[DBC::REV_NUME]      = $revista->numeRevista;
                            $editieInfo[DBC::ED_AN]         = basename($anulCurent);

                            // info exclusiv cand construim de pe disc
                            $editieInfo['isBuiltFromDisk']  = "true";
                            $editieInfo['$editieDirNo']     = cleanPrefixFromName(basename($editiaCurenta), self::$issuePrefixes);
                            // $editieInfo['copertaPath']      = $imaginiScanate[0];   // TODO replace w/ "new Pagina"

                            // informatiile astea nu le putem avea decat din baza de date
                            // $editieInfo[DBC::REV_ID]     = "NOID";
                            // $editieInfo[DBC::ED_ID]      = "NOID";
                            // $editieInfo[DBC::ED_PG_CNT]  = "NOCNT";
                            // $editieInfo[DBC::ED_LUNA]    = "NOLUNA";
                            // $editieInfo[DBC::ED_NUMAR]   = "NONR";
                            // $editieInfo[DBC::ED_ART_CNT] = "";

                            $arrayEditiiSurogat[] = new Editie($editieInfo);
                        }
                    }
                }
            }
        }

        return $arrayEditiiSurogat;
    }
}