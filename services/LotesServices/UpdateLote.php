<?php

class UpdateLote extends MainModel
{
    /**
     * Atualiza um lote existente no banco de dados
     * 
     * @param int $id ID do lote a ser atualizado
     * @param string $nomeLote Nome do lote
     * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário
     */
    public function updateLote(
        $id,
        $data
    ) {
        if (empty($id) || empty($data['nomeLote'])) {
            return false;
        }

        $query =  $this->eventoDB->update('tblLote', 'id', $id, array(
            'nomeLote' => $data['nomeLote'],
            'observacao' => $data['observacao'],
            'qtdTempoEntreLeitura' => $data['qtdTempoEntreLeitura'],
        ));

        if (!$query) {
            return false;
        }

        return true;
    }
}
