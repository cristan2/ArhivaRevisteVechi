<?php

namespace ArhivaRevisteVechi\lib;
use ArhivaRevisteVechi\resources\db\DbConstants as DBC;

class Editie
{

    public $numeRevista, $an, $luna, $numar, $editieId;


    public function __construct($dbRow)
    {
        $this->numeRevista  = $dbRow[DBC::ED_NUME_REV];
        $this->an           = $dbRow[DBC::ED_AN];
        $this->luna         = $dbRow[DBC::ED_LUNA];
        $this->numar        = $dbRow[DBC::ED_NUMAR];
        $this->editieId     = $dbRow[DBC::ED_ID];
    }


    public static function getRegularDbQuery($revistaId)
    {
        return "
            SELECT r.revista_nume, e.*
            FROM editii e
            LEFT JOIN reviste r
            USING ('revista_id')
            WHERE 1
            AND e.revista_id = '$revistaId'
            AND e.tip = 'revista'
        ";
    }

}