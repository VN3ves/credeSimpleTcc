<?php

class UpdateCredencial extends MainModel
{
    /**
     * Atualiza uma credencial existente
     * 
     * @param int $id ID da credencial
     * @param array $data Dados atualizados da credencial
     * @return bool True se a atualização foi bem-sucedida, false caso contrário
     */
    public function updateCredencial($id, $data)
    {
        if (empty($id) || empty($data['nomeCredencial']) || empty($data['codigoCredencial']) || empty($data['docPessoa'])) {
            return false;
        }

        $query =  $this->eventoDB->update('tblCredencial', 'id', $id, array(
            'docPessoa' => $data['docPessoa'],
            'nomeCredencial' => $data['nomeCredencial'],
            'codigoCredencial' => $data['codigoCredencial'],
            'observacao' => $data['observacao'],
            'detalhes' => $data['detalhes']
        ));


        return $query !== false;
    }
}
