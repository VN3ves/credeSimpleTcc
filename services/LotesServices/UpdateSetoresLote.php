<?php

class UpdateSetoresLote extends MainModel
{
    /**
     * Atualiza os setores de um lote
     * 
     * @param int $idLote ID do lote
     * @param array $setores Array com os setores a serem atualizados
     * @param int $idEvento ID do evento
     * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário
     */
    public function updateSetoresLote($idLote, $setores, $idEvento)
    {
        if (empty($idLote) || empty($setores)) {
            return false;
        }

        // Primeiro, remove todos os setores existentes do lote
        $queryDelete =  $this->eventoDB->query(
            "DELETE FROM tblRelLoteSetor WHERE idLote = ?",
            array($idLote)
        );

        if (!$queryDelete) {
            return false;
        }

        // Agora, insere os novos setores
        $success = true;
        foreach ($setores as $idSetor => $setor) {
            if (empty($idSetor) || empty($setor['status'])) {
                continue;
            }

            // Verifica se os campos adicionais estão definidos
            $permiteSaida = isset($setor['permiteSaida']) ? ($setor['permiteSaida'] == 'T' ? 1 : 0) : 0;
            $permiteReentrada = isset($setor['permiteReentrada']) ? ($setor['permiteReentrada'] == 'T' ? 1 : 0) : 0;
            $tipoReentrada = isset($setor['tipoReentrada']) ? $setor['tipoReentrada'] : null;

            $queryInsert =  $this->eventoDB->insert('tblRelLoteSetor', array(
                'idLote' => $idLote,
                'idSetor' => $idSetor,
                'status' => $setor['status'],
                'permiteSaida' => $permiteSaida,
                'permiteReentrada' => $permiteReentrada,
                'tipoReentrada' => $tipoReentrada,
                'idEvento' => $idEvento,
            ));

            if (!$queryInsert) {
                $success = false;
                break;
            }
        }

        return $success;
    }
} 