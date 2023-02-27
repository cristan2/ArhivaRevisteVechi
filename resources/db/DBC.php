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
    const REV_MAX_ED    = "ed_max";

    const DLD_CATEG     = "categorie";
    const DLD_LINKS     = "links";   // alias pentru group_concat('link')
    const DLD_ITEM      = "item";

    public $db;


    public function __construct($dbFile)
    {
        $this->db = new \SQLite3($dbFile, SQLITE3_OPEN_READONLY) or die;
    }

    public function queryToateRevistele()
    {
        // This query is safe, all vars are internal.
        $editiiCountAlias = self::REV_CNT_ED;
        $reviste = '"' . implode('","', REVISTE_READY_FOR_PROD) . '"';
        return $this->db->query("
            SELECT rev.*, ed.$editiiCountAlias
            FROM reviste rev
            LEFT JOIN (
                SELECT revista_id, COUNT(editie_id) $editiiCountAlias
                FROM editii
                WHERE 1
                AND tip = 'revista'
                AND numar <> ''
                GROUP BY revista_id) ed
            USING (revista_id)
            WHERE rev.revista_nume IN ($reviste)
            GROUP BY rev.revista_id
        ");
    }

    public function queryRevista($revistaId)
    {
        $statement = $this->db->prepare("
            SELECT revista_id, revista_nume, aparitii
            FROM reviste
            WHERE revista_id = :revista_id
        ");
        $statement->bindValue(':revista_id', $revistaId, SQLITE3_INTEGER);
        return $statement->execute();
    }

    public function queryAniEditii($revistaId)
    {
        $statement = $this->db->prepare("
            SELECT DISTINCT " . self::ED_AN . "
            FROM editii
            WHERE 1
            AND revista_id = :revista_id
            AND tip = 'revista'
            ORDER BY an
        ");
        $statement->bindValue(':revista_id', $revistaId, SQLITE3_INTEGER);
        return $statement->execute();
    }

    public function queryMaxEditiiDinRevista($revistaId)
    {
        $maxEdAlias = self::REV_MAX_ED;
        // fara abs() poate returna un string gol de la editiile fara numar
        $statement = $this->db->prepare("
            SELECT MAX(abs(numar)) $maxEdAlias
            FROM editii
            WHERE (revista_id) = :revista_id
         ");
        $statement->bindValue(':revista_id', $revistaId, SQLITE3_INTEGER);
        return $statement->execute();
    }

    public function queryToateEditiile($revistaId, $filtruAn = '')
    {
        $articleCountAlias = self::ED_ART_CNT;
        $extraCondition = !empty($filtruAn) ? "AND e.an = :filtru_an" : "";
        $statement = $this->db->prepare("
            SELECT r.revista_nume, e.*, count(a.articol_id) $articleCountAlias
            FROM editii e
            LEFT JOIN reviste r USING ('revista_id')
            LEFT JOIN articole a USING ('editie_id')
            WHERE 1
            AND e.revista_id = :revista_id
            AND e.tip = 'revista'
            $extraCondition
            GROUP BY editie_id
        ");
        $statement->bindValue(':revista_id', $revistaId, SQLITE3_INTEGER);
        $statement->bindValue(':filtru_an', $filtruAn, SQLITE3_INTEGER);
        return $statement->execute();
    }

    public function queryEditie($editieId)
    {
        $statement = $this->db->prepare("
            SELECT r.revista_nume, e.editie_id,
            e.revista_id, e.an, e.luna, e.numar, e.nr_pagini
            FROM editii e
            LEFT JOIN reviste r
            USING ('revista_id')
            WHERE e.editie_id = :editie_id
        ");
        $statement->bindValue(':editie_id', $editieId, SQLITE3_INTEGER);
        return $statement->execute();
    }

    public function queryEditieFromNumar($revistaId, $editieNumar)
    {
        $statement = $this->db->prepare("
            SELECT r.revista_nume, e.editie_id,
            e.revista_id, e.an, e.luna, e.numar, e.nr_pagini
            FROM editii e
            LEFT JOIN reviste r
            USING ('revista_id')
            WHERE e.revista_id = :revista_id
            AND e.numar = :editie_numar
        ");
        $statement->bindValue(':revista_id', $revistaId, SQLITE3_INTEGER);
        $statement->bindValue(':editie_numar', $editieNumar, SQLITE3_INTEGER);
        return $statement->execute();
    }

    public function queryEditieIdFromNumar($revistaId, $editieNumar)
    {
        $statement = $this->db->prepare("
            SELECT editie_id
            FROM editii
            WHERE revista_id = :revista_id
            AND numar = :editie_numar
        ");
        $statement->bindValue(':revista_id', $revistaId, SQLITE3_INTEGER);
        $statement->bindValue(':editie_numar', $editieNumar, SQLITE3_INTEGER);
        return $statement->execute();
    }

    public function queryArticoleDinEditie($editieId)
    {
        $statement = $this->db->prepare("
            SELECT a.*,
            e.an, e.luna,
            r.revista_nume
            FROM articole a
            LEFT JOIN editii e USING (editie_id)
            LEFT JOIN reviste r USING (revista_id)
            WHERE editie_id = :editie_id
            ORDER BY a.pg_toc
        ");
        $statement->bindValue(':editie_id', $editieId, SQLITE3_INTEGER);
        return $statement->execute();
    }

    public function queryDownloadsDinEditie($editieId)
    {
        $linksAlias = self::DLD_LINKS;
        $statement = $this->db->prepare("
            SELECT categorie, item, group_concat(link,',') $linksAlias
            FROM downloads
            WHERE editie_id = :editie_id
            AND link <> ''
            GROUP BY categorie, item
        ");
        $statement->bindValue(':editie_id', $editieId, SQLITE3_INTEGER);
        return $statement->execute();
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

