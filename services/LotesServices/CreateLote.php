<?php

class CreateLote extends MainModel
{
    /**
     * Cria um novo lote no banco de dados
     * 
     * @param int $idEvento ID do evento ao qual o lote pertence
     * @param string $nomeLote Nome do lote
     * @param string $observacao Observação sobre o lote
     * @param bool $permiteDuplicidade Permite duplicidade de credenciais
     * @param bool $autonumeracao Permite autonumeração
     * @param string $numeroLetras Número de letras
     * @param int $qtdDigitos Quantidade de dígitos
     * @param string $tipoCodigo Tipo de código 
     * @param string $tipoCredencial Tipo de credencial
     * @param bool $permiteAcessoFacial Permite acesso facial
     * @param bool $permiteImpressao Permite impressão
     * @return mixed ID do lote criado ou false em caso de falha
     */
    public function createLote(
        $idEvento,
        $data
    ) {
        if (empty($idEvento) || empty($data['nomeLote']) || empty($data['tipoCodigo']) || empty($data['tipoCredencial'])) {
            return false;
        }

        $query =  $this->eventoDB->insert('tblLote', array(
            'idEvento' => $idEvento,
            'nomeLote' => $data['nomeLote'],
            'observacao' => $data['observacao'],
            'permiteDuplicidade' => $data['permiteDuplicidade'],
            'autonumeracao' => $data['autonumeracao'],
            'numeroLetras' => $data['numeroLetras'],
            'qtdDigitos' => $data['qtdDigitos'],
            'tipoCodigo' => $data['tipoCodigo'],
            'tipoCredencial' => $data['tipoCredencial'],
            'permiteAcessoFacial' => $data['permiteAcessoFacial'],
            'permiteImpressao' => $data['permiteImpressao'],
            'status' => 'T'
        ));

        if (!$query) {
            return false;
        }

        return  $this->eventoDB->lastInsertId();
    }
}
