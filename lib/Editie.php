<?php

namespace ArhivaRevisteVechi\lib;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;
use ArhivaRevisteVechi\resources\db\DBC;

require_once("../resources/config.php");
require_once HELPERS . "/h_images.php";
require_once HELPERS . "/h_misc.php";
require_once HELPERS . "/HtmlPrinter.php";

class Editie
{
    // --- base attrs ---
    public  $numeRevista, $revistaId;
    public  $an, $luna, $numar, $editieId;
    public  $maxNumPages;
    private $numarArticole;

    private $copertaPath;

    // --- location attrs ---
    public  $editieDirPath;
    public  $editieDirName;
    public  $editieBaseName;

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
        /* ***** doar pentru revistele de pe disk ***** */
        if (isset($dbRow['isBuiltFromDisk'])) {
            $this->isBuiltFromDisk = true;
            $this->copertaPath = $dbRow['copertaPath'];
        }

        /* ******** base attrs ******** */
        $this->numeRevista    = $dbRow[DBC::REV_NUME];
        $this->revistaId      = $dbRow[DBC::REV_ID];
        $this->an             = $dbRow[DBC::ED_AN];
        $this->luna           = $dbRow[DBC::ED_LUNA];
        $this->numar          = $dbRow[DBC::ED_NUMAR];
        $this->editieId       = $dbRow[DBC::ED_ID];
        $this->maxNumPages    = $dbRow[DBC::ED_PG_CNT];

        /* ******** location attrs ******** */
        $this->editieDirName  = $this->buildEditieBaseDirName();
        $this->editieDirPath  = $this->buildEditieBaseDirPath();
        $this->editieBaseName = $this->buildEditieBaseName();

        /* ******** content attrs ******** */
        // fiecare articol se adauga singur in acest array
        $listaArticole = array();
        $listaPagini = array();

        $this->arePaginiScanate = $this->countPaginiScanate() > 0;

        if (isset($dbRow[DBC::ED_ART_CNT])) {
            // coloana asta nu e inclusa in toate query-urile,
            // e relevanta doar in pagina cu toate editiile (?)
            $this->numarArticole  = $dbRow[DBC::ED_ART_CNT];
            $this->areArticoleIndexate = true;
        }
    }

    /**
     * Construieste numele directorului unei reviste
     * (ex img/level/1999/12)
     */
    private function buildEditieBaseDirName() {
//        return
    }

    /**
     * Construieste calea catre directorul imaginilor unei reviste
     * (ex img/level/1999/12)
     */
    private function buildEditieBaseDirPath() {
        return  IMG . DIRECTORY_SEPARATOR
        .strtolower($this->numeRevista) . DIRECTORY_SEPARATOR
        .$this->an . DIRECTORY_SEPARATOR
        .padLeft($this->luna, LUNA_PAD);
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
        return  $this->numeRevista
                .$this->an
                .padLeft($this->luna, LUNA_PAD);
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

        if ($this->isBuiltFromDisk) {
            $imgThumbSrc = $this->copertaPath;
        } else {
            // nume baza pentru imaginea copertii
            $copertaBaseImg = $this->editieBaseName . padLeft(1, PAGINA_PAD);

            // calea catre thumbnail
            $imgThumbSrc = getImageThumbPath($this->editieDirPath, $copertaBaseImg);
        }

        // imaginea copertii: thumb cu link catre imaginea full
        return getImageWithLink($imgThumbSrc, $targetLink, "card-img");
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

        $isEditiaNumerotataCuLuna = function ($basename) { return startsWith($basename, "Nr. ");};

        $arrayEditiiSurogat = array();

        // citeste ani revista
        $directoareAni = getDirsInPath($revista->revistaDirPath);
        if ($directoareAni) {
            foreach($directoareAni as $anulCurent) {

                // citeste editii (organizate pe luni sau numere)
                $directoareEditii = getDirsInPath($anulCurent);
                if ($directoareEditii) {
                    foreach($directoareEditii as $editiaCurenta) {

                        $editiaIsLuna = $isEditiaNumerotataCuLuna($editiaCurenta);

                        // citeste fisiere
                        // daca exista imagini scanate, construim editia
                        $imaginiScanate = getImageFilesInDir($editiaCurenta);
                        if ($imaginiScanate) {

                            $editieInfo = array();

                            $editieInfo[DBC::REV_NUME]      = $revista->numeRevista;
                            $editieInfo[DBC::REV_ID]        = "NOID";
                            $editieInfo[DBC::ED_AN]         = basename($anulCurent);
                            $editieInfo[DBC::ED_LUNA]       = $editiaIsLuna ? basename($editiaCurenta) : "NOLUNA";
                            $editieInfo[DBC::ED_NUMAR]      = $editiaIsLuna ? "NONR" : getCleanNumarEditieFromDirName(basename($editiaCurenta));
                            $editieInfo[DBC::ED_ID]         = "NOID";
                            $editieInfo[DBC::ED_PG_CNT]     = "NOCNT";
                            //$editieInfo[DBC::ED_ART_CNT]  = "";

                            $editieInfo['isBuiltFromDisk']  = "true";
                            $editieInfo['copertaPath']      = $imaginiScanate[0];

                            $arrayEditiiSurogat[] = new Editie($editieInfo);
                        }
                    }
                }
            }
        }

        return $arrayEditiiSurogat;
    }
}