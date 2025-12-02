<?php

class UpdateComponente extends MainModel
{

    public function updateComponente($id, $dados)
    {
        if (empty($id)) {
            return false;
        }

        $query = $this->db->update('tblEventoComponentes', 'id', $id, array(
            'qtdSetores' => $dados['qtdSetores'],
            'usaLeitorFacial' => $dados['usaLeitorFacial'],
            'qtdLeitoresFaciais' => $dados['qtdLeitoresFaciais'],
            'credenciaisImpressas' => $dados['credenciaisImpressas'],
            'qtdLotesCredenciais' => $dados['qtdLotesCredenciais']
        ));

        return $query;
    }
} 