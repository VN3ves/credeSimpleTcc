<?php
class CreateSetor extends MainModel{

    public function createSetor($nomeSetor, $observacao, $status, $idEvento) {
        if (empty($nomeSetor)) {
            return false;
        }

        $query = $this->eventoDB->insert('tblSetor', array(
            'nomeSetor' => $nomeSetor,
            'observacao' => $observacao,
            'status' => $status,
            'idEvento' => $idEvento
        ));


        if (!$query) {
            return false;
        }

        return $query;
    }
} 