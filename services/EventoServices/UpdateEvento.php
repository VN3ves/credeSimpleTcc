<?php

class UpdateEvento extends MainModel
{

    public function updateEvento($id, $idCategoria, $idLocal, $nomeEvento, $observacao, $dataInicio, $dataFim, $licenca, $dataInicioCredenciamento, $dataFimCredenciamento)
    {
        if (empty($id) || empty($idCategoria) || empty($idLocal) || empty($nomeEvento) || empty($observacao) || empty($dataInicio) || empty($dataFim) || empty($licenca) || empty($dataInicioCredenciamento) || empty($dataFimCredenciamento)) {
            return false;
        }

        $query = $this->db->update('tblEvento', 'id', $id, array(
            'idCategoria' => $idCategoria,
            'idLocal' => $idLocal,
            'nomeEvento' => $nomeEvento,
            'observacao' => $observacao,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'licenca' => $licenca,
            'dataInicioCredenciamento' => $dataInicioCredenciamento,
            'dataFimCredenciamento' => $dataFimCredenciamento
        ));

        if (!$query) {
            return false;
        }

        return $query;
    }
}
