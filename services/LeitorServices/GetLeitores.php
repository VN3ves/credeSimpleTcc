<?php
class GetLeitores extends MainModel
{

    public function getLeitores()
    {
        $sql = "
            SELECT l.*, s.nomeSetor, t.nomeTerminal 
            FROM `tblLeitor` l
            LEFT JOIN `tblSetor` s ON s.id = l.idSetor
            LEFT JOIN `tblTerminal` t ON t.id = l.idTerminal
            ORDER BY l.`nomeLeitor` ASC";

        $query = $this->eventoDB->query($sql);

        if (!$query) {
            return array();
        }

        return $query->fetchAll();
    }
}
