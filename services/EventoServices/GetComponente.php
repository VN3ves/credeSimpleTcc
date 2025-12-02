<?php

class GetComponente extends MainModel
{

    public function getComponente($idEvento)
    {
        if (empty($idEvento)) {
            return null;
        }

        $sql = "SELECT * FROM tblEventoComponentes WHERE idEvento = ?";
        $query = $this->db->query($sql, array($idEvento));

        if (!$query) {
            return null;
        }

        return $query->fetch(PDO::FETCH_ASSOC);
    }
} 