<?php
class GetTerminais extends MainModel
{

    public function getTerminais($filtros = array())

    {
        $where = '';

        if (!empty($filtros["status"])) {
            if (!empty($where)) {
                $where .= " AND ";
            } else {
                $where = " WHERE ";
            }

            $where .= "(t.`status` = '" . $filtros['status'] . "')";
        }

        $sql = "
            SELECT t.*, s.nomeSetor 
            FROM `tblTerminal` t
            LEFT JOIN `tblSetor` s ON s.id = t.idSetor
            $where
            ORDER BY t.`nomeTerminal` ASC";

        $query = $this->eventoDB->query($sql);


        if (!$query) {
            return array();
        }

        return $query->fetchAll();
    }
}
