<?php

class CreateCredencial extends MainModel
{
    /**
     * Cria uma nova credencial
     * 
     * @param array $data Dados da credencial
     * @return bool|int ID da credencial criada ou false em caso de erro
     */
    public function createCredencial($data)
    {
        if (
            empty($data['idLote']) || empty($data['nomeLote']) ||
            empty($data['idEvento']) || empty($data['nomeCredencial']) ||
            empty($data['codigoCredencial']) || empty($data['docPessoa'])
        ) {
            return false;
        }

        $query =  $this->eventoDB->insert('tblCredencialLote', array(
            'idEvento' => $data['idEvento'],
            'idLote' => $data['idLote'],
            'idPessoa' => $data['idPessoa'],
            'docPessoa' => $data['docPessoa'],
            'nomeCredencial' => $data['nomeCredencial'],
            'codigoCredencial' => $data['codigoCredencial'],
            'observacao' => $data['observacao'],
            'detalhes' => $data['detalhes'],
            'impresso' => 'F',
            'status' => 'T'
        ));

        if ($query === false) {
            return false;
        }

        return $this->eventoDB->lastInsertId();
    }
}
