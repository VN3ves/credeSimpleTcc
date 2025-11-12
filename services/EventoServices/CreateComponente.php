<?php

class CreateComponente extends MainModel
{

    public function createComponente($dados)
    {
        if (empty($dados['idEvento'])) {
            return false;
        }

        $query = $this->db->insert('tblEventoComponentes', array(
            'idEvento' => $dados['idEvento'],
            'qtdSetores' => $dados['qtdSetores'],
            'usaLeitorFacial' => $dados['usaLeitorFacial'],
            'qtdLeitoresFaciais' => $dados['qtdLeitoresFaciais'],
            'credenciaisImpressas' => $dados['credenciaisImpressas'],
            'qtdLotesCredenciais' => $dados['qtdLotesCredenciais']
        ));

        return $query;
    }
} 