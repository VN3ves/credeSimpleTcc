<?php

class GetEvento extends MainModel
{

    public function getEvento($id = false)
    {
        if (empty($id)) {
            return;
        }

        $s_id = (int)$id;

        $sql = "SELECT * FROM vwEventos WHERE idEvento = ?";

        $query = $this->db->query($sql, array($s_id));

        if (!$query) {
            return array();
        }

        $evento = $query->fetch(PDO::FETCH_ASSOC);
        return $evento;
    }
}