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
    const ART_NOTA      = "nota";

    const ED_AN         = "an";
    const ED_LUNA       = "luna";
    const ED_NUMAR      = "numar";
    const ED_ID         = "editie_id";
    const ED_PG_CNT     = "nr_pagini";
    const ED_ART_CNT    = "art_cnt";

    const REV_ID        = "revista_id";
    const REV_NUME      = "revista_nume";
    const REV_APARITII  = "aparitii";
    const REV_CNT_ED    = "ed_cnt";

    const DLD_CATEG     = "categorie";
    const DLD_LINKS     = "links";   // alias pentru group_concat('link')
    const DLD_ITEM      = "item";

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
        $editiiCount = self::REV_CNT_ED;
        return $this->directQuery("
            SELECT rev.*, ed.$editiiCount
            FROM reviste rev
            LEFT JOIN (
                SELECT revista_id, COUNT(editie_id) $editiiCount
                FROM editii
                WHERE tip = 'revista'
                GROUP BY revista_id) ed
            USING (revista_id)
            GROUP BY rev.revista_id
        ");
    }

    public function queryRevista($revistaId)
    {
        return $this->directQuery("
            SELECT revista_id, revista_nume, aparitii
            FROM reviste
            WHERE revista_id = '$revistaId'
        ");
    }

    public function queryAniEditii($revistaId)
    {
        return $this->directQuery("
            SELECT DISTINCT " . self::ED_AN . "
            FROM editii
            WHERE 1
            AND revista_id = '$revistaId'
            AND tip = 'revista'
            ORDER BY an
        ");
    }

    public function queryToateEditiile($revistaId, $filtruAn = '')
    {
        $articleCountAlias = self::ED_ART_CNT;
        return $this->directQuery("
            SELECT r.revista_nume, e.*, count(a.articol_id) $articleCountAlias
            FROM editii e
            LEFT JOIN reviste r USING ('revista_id')
            LEFT JOIN articole a USING ('editie_id')
            WHERE 1
            AND e.revista_id = '$revistaId'
            AND e.tip = 'revista'"
            . (!empty($filtruAn) ? "AND e.an =  '$filtruAn'" : "")
            ."GROUP BY editie_id
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

    public function queryEditieFromNumar($revistaId, $editieNumar)
    {
        return $this->directQuery("
            SELECT r.revista_nume, e.editie_id,
            e.revista_id, e.an, e.luna, e.numar, e.nr_pagini
            FROM editii e
            LEFT JOIN reviste r
            USING ('revista_id')
            WHERE e.revista_id = $revistaId
            AND e.numar = $editieNumar
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

    public function queryDownloadsDinEditie($editieId)
    {
        $linksAlias = self::DLD_LINKS;
        return $this->directQuery("
            SELECT categorie, item, group_concat(link,',') $linksAlias
            FROM downloads
            WHERE editie_id = $editieId
            AND link <> ''
            GROUP BY categorie, item
        ");
    }

    public function getNextRow($dbResult)
    {
        return $dbResult->fetchArray(SQLITE3_ASSOC);
    }

    public function getSingleArrayFromDbResult($dbResult, $desiredDbCol)
    {
        $arrayToReturn = array();
        while ($dbRow = $this->getNextRow($dbResult)) {
            $arrayToReturn[] = $dbRow[$desiredDbCol];
        }
        return $arrayToReturn;
    }
}