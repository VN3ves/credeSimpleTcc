<?php

class CreateEvento extends MainModel
{

    public function createEvento($idCategoria, $idLocal, $nomeEvento, $observacao, $dataInicio, $dataFim, $licenca, $dataInicioCredenciamento, $dataFimCredenciamento)
    {
        if (empty($idCategoria) || empty($nomeEvento) || empty($dataInicio) || empty($dataFim)) {
            return false;
        }

        $query = $this->db->insert('tblEvento', array(
            'idCategoria' => $idCategoria,
            'idLocal' => $idLocal,
            'nomeEvento' => $nomeEvento,
            'observacao' => $observacao,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'licenca' => $licenca,
            'dataInicioCredenciamento' => $dataInicioCredenciamento,
            'dataFimCredenciamento' => $dataFimCredenciamento,
            'status' => 'F',
            'aprovado' => 'T'
        ));

        if (!$query) {
            return false;
        }

        return $query;
    }
}
