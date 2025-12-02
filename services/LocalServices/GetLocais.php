<?php
class GetLocais extends MainModel
{

    public function getLocais($filtros = null)
    {
        $where = "";

        if (!empty($filtros["q"])) {
            $where = " WHERE (
                `nomeOficial` LIKE '%" . _otimizaBusca($filtros['q']) . "%' OR
                `logradouro` LIKE '%" . _otimizaBusca($filtros['q']) . "%' OR
                `bairro` LIKE '%" . _otimizaBusca($filtros['q']) . "%' OR
                `cidade` LIKE '%" . _otimizaBusca($filtros['q']) . "%' OR
                `estado` LIKE '%" . _otimizaBusca($filtros['q']) . "%'
            )";
        }

        $sql = "SELECT * FROM `tblEventoLocal`";
        $sql .= $where;
        $sql .= " ORDER BY `nomeOficial` ASC";

        $query = $this->db->query($sql);

        if (!$query) {
            return array();
        }

        return $query->fetchAll();
    }
} 