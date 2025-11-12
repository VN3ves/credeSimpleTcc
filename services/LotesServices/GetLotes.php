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

        $dadosLotes = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!$dadosLotes) {
            return array();
        }

        foreach ($dadosLotes as $lote) {
            // Consulta para calcular o total de credenciais geradas no lote
            $queryTotalCredenciais = $this->eventoDB->query(
                "SELECT COUNT(*) as total FROM tblCredencial WHERE idLote = ?",
                array($lote['id'])
            );
            $totalCredenciais = $queryTotalCredenciais->fetch(PDO::FETCH_ASSOC);
            $lote['credenciaisGeradas'] = $totalCredenciais['total'];
            $lotes[] = $lote;
        }

        return $lotes;
    }
}
