<?php

class CreateLote extends MainModel
{
    /**
     * Cria um novo lote no banco de dados
     * 
     * @param int $idEvento ID do evento ao qual o lote pertence
     * @param string $nomeLote Nome do lote
     * @param string $observacao Observação sobre o lote
     * @return mixed ID do lote criado ou false em caso de falha
     */
    public function createLote(
        $idEvento,
        $data
    ) {
        if (empty($idEvento) || empty($data['nomeLote'])) {
            return false;
        }

        $query =  $this->eventoDB->insert('tblLote', array(
            'idEvento' => $idEvento,
            'nomeLote' => $data['nomeLote'],
            'observacao' => $data['observacao'],
            'qtdTempoEntreLeitura' => $data['qtdTempoEntreLeitura'],
            'status' => 'T'
        ));

        if (!$query) {
            return false;
        }

        return  $this->eventoDB->lastInsertId();
    }
}
