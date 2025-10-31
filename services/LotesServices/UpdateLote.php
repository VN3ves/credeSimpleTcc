<?php

class UpdateLote extends MainModel
{
    /**
     * Atualiza um lote existente no banco de dados
     * 
     * @param int $id ID do lote a ser atualizado
     * @param string $nomeLote Nome do lote
     * @param int $idTipoCodigo ID do tipo de código
     * @param int $idTipoCredencial ID do tipo de credencial
     * @param int $permiteAcessoFacial Se permite acesso facial (0 ou 1)
     * @param int $permiteImpressao Se permite impressão (0 ou 1)
     * @param int $temAutonumeracao Se tem autonumeração (0 ou 1)
     * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário
     */
    public function updateLote(
        $id,
        $data
    ) {
        if (empty($id) || empty($data['nomeLote']) || empty($data['tipoCodigo']) || empty($data['tipoCredencial'])) {
            return false;
        }

        $query =  $this->eventoDB->update('tblLote', 'id', $id, array(
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
        ));

        if (!$query) {
            return false;
        }

        return true;
    }
}
