<?php

class GetCredenciaisLote extends MainModel
{
    /**
     * Obtém todas as credenciais de um lote
     * 
     * @param int $idLote ID do lote
     * @return array Array com as credenciais do lote
     */
    public function getCredenciaisLote($idLote)
    {
        if (empty($idLote)) {
            return array();
        }

        $query = $this->eventoDB->query(
            "SELECT * FROM tblCredencial WHERE idLote = ? ORDER BY nomeCredencial",
            array($idLote)
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
} 