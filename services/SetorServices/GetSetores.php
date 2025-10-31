<?php
class GetSetores extends MainModel
{
    public function getSetores($filtros = array())
    {
        $where = '';

        if (!empty($filtros["status"])) {
            if (!empty($where)) {
                $where .= " AND ";
            } else {
                $where = " WHERE ";
            }

            $where .= "(`status` = '" . $filtros['status'] . "')";
        }

        $sql = "SELECT * FROM `tblSetor`";
        $sql .= " $where ";
        $sql .= "ORDER BY `nomeSetor` ASC";

        $query = $this->eventoDB->query($sql);


        if (!$query) {
            return array();
        }

        return $query->fetchAll();
    }
}
