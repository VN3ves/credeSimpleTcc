<?php

class GetPeriodosLote extends MainModel
{
    /**
     * Obtém os períodos de um lote
     * 
     * @param int $idLote ID do lote
     * @return array Retorna um array com os períodos do lote
     */
    public function getPeriodosLote($idLote)
    {
        if (empty($idLote)) {
            return array();
        }

        $query =  $this->eventoDB->query(
            "SELECT * FROM tblLotePeriodo WHERE idLote = ? ORDER BY dataInicio, horaInicio",
            array($idLote)
        );

        if (!$query || $query->rowCount() == 0) {
            return array();
        }

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
} 