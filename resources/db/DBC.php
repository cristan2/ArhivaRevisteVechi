<?php
namespace ArhivaRevisteVechi\resources\db;

class DBC
{
    const ART_ID        = "articol_id";
    const ART_PG_TOC    = "pg_toc";
    const ART_PG_CNT    = "pg_count";
    const ART_RUBRICA   = "rubrica";
    const ART_TITLU     = "titlu";
    const ART_AUTOR     = "autor";

    const ED_AN         = "an";
    const ED_LUNA       = "luna";
    const ED_NUMAR      = "numar";
    const ED_ID         = "editie_id";
    const ED_PG_CNT     = "nr_pagini";

    const REV_ID        = "revista_id";
    const REV_NUME      = "revista_nume";

    public $db;


    public function __construct($dbFile)
    {
        $this->db = new \SQLite3($dbFile, SQLITE3_OPEN_READONLY) or die;
    }

    public function directQuery($query)
    {
        return $this->db->query($query);
    }

    public function queryToateRevistele()
    {
        return $this->directQuery("
            SELECT rev.*, ed.cnt
            FROM reviste rev
            LEFT JOIN (
                SELECT revista_id, COUNT(editie_id) cnt
                FROM editii
                WHERE tip = 'revista'
                GROUP BY revista_id) ed
            USING (revista_id)
            GROUP BY rev.revista_id
        ");
    }

    public function queryToateEditiile($revistaId)
    {
        return $this->directQuery("
            SELECT r.revista_nume, e.*
            FROM editii e
            LEFT JOIN reviste r
            USING ('revista_id')
            WHERE 1
            AND e.revista_id = '$revistaId'
            AND e.tip = 'revista'
        ");
    }

    public function queryEditie($editieId)
    {
        return $this->directQuery("
            SELECT r.revista_nume, e.editie_id,
            e.revista_id, e.an, e.luna, e.numar, e.nr_pagini
            FROM editii e
            LEFT JOIN reviste r
            USING ('revista_id')
            WHERE e.editie_id = $editieId
        ");
    }

    public function queryEditieIdFromNumar($revistaId, $editieNumar)
    {
        return $this->directQuery("
            SELECT editie_id
            FROM editii
            WHERE revista_id = '$revistaId'
            AND numar = '$editieNumar'
        ");
    }

    public function queryArticoleDinEditie($editieId)
    {
        return $this->directQuery("
            SELECT a.*,
            e.an, e.luna,
            r.revista_nume
            FROM articole a
            LEFT JOIN editii e USING (editie_id)
            LEFT JOIN reviste r USING (revista_id)
            WHERE editie_id = $editieId
            ORDER BY a.pg_toc
        ");
    }

    public function getNextRow($dbResult)
    {
        return $dbResult->fetchArray(SQLITE3_ASSOC);
    }
}