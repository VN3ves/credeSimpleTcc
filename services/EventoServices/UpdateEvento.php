<?php

class UpdateEvento extends MainModel
{

    public function updateEvento($id, $idCategoria, $idLocal, $nomeEvento, $observacao, $dataInicio, $dataFim)
    {
        if (empty($id) || empty($idCategoria) || empty($idLocal) || empty($nomeEvento) || empty($observacao) || empty($dataInicio) || empty($dataFim)) {
            return false;
        }

        $query = $this->db->update('tblEvento', 'id', $id, array(
            'idCategoria' => $idCategoria,
            'idLocal' => $idLocal,
            'nomeEvento' => $nomeEvento,
            'observacao' => $observacao,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim
        ));

        if (!$query) {
            return false;
        }

        return $query;
    }
}
