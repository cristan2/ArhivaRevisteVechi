<?php

namespace ArhivaRevisteVechi\lib;
use ArhivaRevisteVechi\resources\db\DBC;
use ArhivaRevisteVechi\lib\helpers\HtmlPrinter;

// ROOT si config.php sunt definite de index.php
//require_once("../resources/config.php");

require_once HELPERS . "/HtmlPrinter.php";

class Revista {

    // base attrs
    public  $numeRevista, $revistaId;
    public  $maxNumEditii;

    // location attrs
    public  $revistaDirPath;
    public  $revistaBaseName;

    // content attrs
    private $countEditiiScanate;

    public function __construct($dbRow)
    {
        // base attrs
        $this->numeRevista     = $dbRow[DBC::REV_NUME];
        $this->revistaId       = $dbRow[DBC::REV_ID];
        $this->maxNumEditii    = $this->getMaxNumAparitii($dbRow[DBC::REV_APARITII]);

        // location attrs
        $this->revistaBaseName = self::getSimpleName($this->numeRevista);
        $this->revistaDirPath  = $this->buildRevistaBaseDir();

        // content attrs
        if (isset($dbRow[DBC::REV_CNT_ED])) {
            // coloana asta nu e inclusa in toate query-urile,
            // e relevanta doar in pagina cu toate editiile (?)
            $this->countEditiiScanate = $dbRow[DBC::REV_CNT_ED];
        }
    }

    static function getSimpleName($numeRevista)
    {
        return preg_replace('/[^a-z0-9 ]+/', "", strtolower($numeRevista));
    }

    private function buildRevistaBaseDir()
    {
        return IMG . DIRECTORY_SEPARATOR
            . $this->revistaBaseName;
    }

    private function getMaxNumAparitii($aparitiiFromDb)
    {
        return explode(" ", $aparitiiFromDb, 2)[0];
    }

    private function getStatusArhiva()
    {
        if (empty($this->countEditiiScanate)) $this->countEditiiScanate = "0";
        return "$this->countEditiiScanate / $this->maxNumEditii";
    }

    public function getRevistaUrl()
    {
        return ARHIVA . "/editii.php" . "?revista=$this->revistaId";
    }

    private function makeCoperta()
    {
        $targetLink = $this->getRevistaUrl();
        $copertaImagePath =  COPERTI_DIR . DIRECTORY_SEPARATOR . $this->revistaBaseName . ".jpg";
        if (!file_exists($copertaImagePath)) $copertaImagePath = COPERTA_DEFAULT;
        return $this->getImageWithLink($copertaImagePath, $targetLink, "card-img");
    }

    private function getImageWithLink($displayedImagePath, $targetLink, ...$htmlClasses) {
        if (! file_exists($displayedImagePath))
//        return "n/a";
            $displayedImagePath = COPERTA_DEFAULT;
//    else {
        $htmlClassList = getCssClassList($htmlClasses);
        return "<a href='$targetLink'><img src='$displayedImagePath' $htmlClassList alt='Image' /></a>";
//    }
    }

    /**
     * Construieste carduri reviste
     * cu 3 sectiuni: Imagine, Titlu si Subtitlu
     */
    function getHtmlOutput($useSearchLayout = false)
    {
        $htmlClass = "reviste-cards";

        $attrsToPrint = array(
            "imagine card"  => $this->makeCoperta(),
            "titlu card"    => "<h1>{$this->numeRevista}</h1>",
            "subtitlu card" => "<h2>{$this->getStatusArhiva()}</h2>",
        );

        return HtmlPrinter::buildCardDiv($attrsToPrint, $htmlClass);
    }

    // TODO
    function getListaAniAparitie()
    {
//        return DBC::queryAniEditii ....
    }
}