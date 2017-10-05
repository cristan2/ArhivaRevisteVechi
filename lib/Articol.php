<?php
namespace ArhivaRevisteVechi\lib;
use ArhivaRevisteVechi\resources\db\DBC;

class Articol
{
    public $titlu, $rubrica, $autor;
    public $pageToc, $pageCount;

    public $listaPagini = array();


    public function __construct($dbRow)
    {
        $this->pageToc    = $dbRow[DBC::ART_PG_TOC];
        $this->rubrica    = $dbRow[DBC::ART_RUBRICA];
        $this->titlu      = $dbRow[DBC::ART_TITLU];
        $this->autor      = $dbRow[DBC::ART_AUTOR];
    }
}