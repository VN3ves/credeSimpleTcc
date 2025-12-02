<?php

class BuscaPessoaEvento extends MainModel
{
    /**
     * Busca uma credencial pelo ID
     * 
     * @param int $idPessoa ID da pessoa
     * @param int $idEvento ID do evento
     * @return array|false Array com os dados da credencial ou false se não encontrada
     */
    public function BuscaPessoaEvento($idPessoa, $idEvento)
    {
        try{
        if (empty($idPessoa) || empty($idEvento)) {
            Log::error('BuscaPessoaEvento', 'ID da pessoa ou ID do evento não informado');
            return [];
        }

        $query = $this->eventoDB->query(
            "SELECT tblCredencial.*, tblLote.*
            FROM tblCredencial
            INNER JOIN tblLote ON tblCredencial.idLote = tblLote.id
            WHERE tblCredencial.idPessoa = ? AND tblCredencial.idEvento = ? AND tblCredencial.status = 'T'",
            array($idPessoa, $idEvento)
        );
        Log::info($idEvento . ' - ' . $idPessoa);
        $resultado = $query->fetch(PDO::FETCH_ASSOC);

        Log::info('BuscaPessoaEvento Resultado: ' . json_encode($resultado));

        // Se não encontrou, busca a ultima credencial da pessoa no evento
        if (!$resultado) {
            $query = $this->eventoDB->query(
                "SELECT tblCredencial.*, tblLote.*
                FROM  tblCredencial 
                INNER JOIN tblLote ON tblCredencial.idLote = tblLote.id
                WHERE tblCredencial.idPessoa = ? AND tblCredencial.idEvento = ? 
                ORDER BY tblCredencial.dataCadastro DESC LIMIT 1",
                array($idPessoa, $idEvento)
            );
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
        }

        return $resultado;
        } catch (Exception $e) {
            Log::error('BuscaPessoaEvento Erro ao buscar pessoa no evento: ' . $e->getMessage());
            return [];
        }
    }
}
