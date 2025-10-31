<?php

class GetLotes extends MainModel
{
    /**
     * Obtém todos os lotes de um evento
     * 
     * @param int $idEvento ID do evento
     * @return array Retorna um array com os lotes do evento
     */
    public function getLotes($idEvento)
    {
        if (empty($idEvento)) {
            return array();
        }

        $query =  $this->eventoDB->query(
            "SELECT l.*
             FROM tblLote l
             WHERE l.idEvento = ?
             ORDER BY l.nomeLote",
            array($idEvento)
        );

        if (!$query || $query->rowCount() == 0) {
            return array();
        }

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
} 