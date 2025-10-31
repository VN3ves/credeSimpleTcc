<?php

class GetCredencial extends MainModel
{
    /**
     * Busca uma credencial pelo ID
     * 
     * @param int $id ID da credencial
     * @return array|false Array com os dados da credencial ou false se não encontrada
     */
    public function getCredencial($id)
    {
        if (empty($id)) {
            return false;
        }

        $query = $this->eventoDB->query(
            "SELECT tblCredencial.*
            FROM tblCredencial 
            WHERE tblCredencial.id = ?",
            array($id)
        );

        $resultadoCredencial = $query->fetch(PDO::FETCH_ASSOC);

        $query = $this->db->query(
            "SELECT tblPessoa.*
            FROM tblPessoa 
            WHERE tblPessoa.id = ?",
            array($resultadoCredencial['idPessoa'])
        );
        $resultado2 = $query->fetch(PDO::FETCH_ASSOC);

        $resultadoCredencial['nomePessoa'] =  $resultado2['nome'] . ' ' . $resultado2['sobrenome'];

        return $resultadoCredencial;
    }
}
