<?php

namespace ArhivaRevisteVechi\lib;
use ArhivaRevisteVechi\resources\db\DBC;

class Editie
{
    public $numeRevista, $revistaId;
    public $an, $luna, $numar, $editieId;
    public $basename;


    public function __construct($dbRow)
    {
        $this->numeRevista  = $dbRow[DBC::ED_NUME_REV];
        $this->revistaId    = $dbRow[DBC::ED_ID_REV];
        $this->an           = $dbRow[DBC::ED_AN];
        $this->luna         = $dbRow[DBC::ED_LUNA];
        $this->numar        = $dbRow[DBC::ED_NUMAR];
        $this->editieId     = $dbRow[DBC::ED_ID];
    }
}