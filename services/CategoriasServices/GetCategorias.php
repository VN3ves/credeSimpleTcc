<?php

class GetCategorias extends MainModel
{

    public function getCategorias($filtros = null)
    {

        $where = " WHERE `tblEventoCategoria`.`status` = 'T'";

        if (!empty($filtros["q"])) {
            $where .= " AND (`tblEventoCategoria`.`nomeCategoria` LIKE '%" . _otimizaBusca($filtros['q']) . "%')";
        }

        $sql = "SELECT `tblEventoCategoria`.* FROM `tblEventoCategoria`";
        $sql .= $where;
        $sql .= " ORDER BY `tblEventoCategoria`.`nomeCategoria` ASC";

        $query = $this->db->query($sql);

        if (!$query) {
            return array();
        }

        $categorias = $query->fetchAll(PDO::FETCH_ASSOC);
        return $categorias;
    }
}
