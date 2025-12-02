<?php

class GetSetoresLote extends MainModel
{
    /**
     * Obtém os setores de um lote
     * 
     * @param int $idLote ID do lote
     * @return array Retorna um array com os setores do lote
     */
    public function getSetoresLote($idLote)
    {
        if (empty($idLote)) {
            return array();
        }

        $query =  $this->eventoDB->query(
            "SELECT ls.*, s.nomeSetor 
             FROM tblRelLoteSetor ls
             LEFT JOIN tblSetor s ON ls.idSetor = s.id
             WHERE ls.idLote = ?
             ORDER BY s.nomeSetor",
            array($idLote)
        );

        if (!$query || $query->rowCount() == 0) {
            return array();
        }

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
} 