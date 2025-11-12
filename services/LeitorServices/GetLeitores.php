<?php
class GetLeitores extends MainModel
{

    public function getLeitores()
    {
        $sql = "
            SELECT l.*, s.nomeSetor
            FROM `tblLeitor` l
            LEFT JOIN `tblSetor` s ON s.id = l.idSetor
            ORDER BY l.`nomeLeitor` ASC";

        $query = $this->eventoDB->query($sql);

        if (!$query) {
            return array();
        }

        return $query->fetchAll();
    }
}
