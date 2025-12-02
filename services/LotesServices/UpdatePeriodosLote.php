<?php

class UpdatePeriodosLote extends MainModel
{
    /**
     * Atualiza os períodos de um lote
     * 
     * @param int $idLote ID do lote
     * @param array $periodos Array com os períodos a serem atualizados
     * @param int $idEvento ID do evento
     * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário
     */
    public function updatePeriodosLote($idLote, $periodos, $idEvento)
    {
        if (empty($idLote) || empty($periodos)) {
            return false;
        }

        // Primeiro, remove todos os períodos existentes do lote
        $queryDelete =  $this->eventoDB->query(
            "DELETE FROM tblLotePeriodo WHERE idLote = ?",
            array($idLote)
        );

        if (!$queryDelete) {
            return false;
        }

        // Agora, insere os novos períodos
        $success = true;
        foreach ($periodos as $periodo) {
            if (empty($periodo['dataInicio']) || empty($periodo['dataTermino'])) {
                continue;
            }

            // Extrair data e hora dos campos corretos
            $dataInicio = date('Y-m-d', strtotime($periodo['dataInicio']));
            $dataTermino = date('Y-m-d', strtotime($periodo['dataTermino']));

            // CORREÇÃO: Ler as horas dos campos de hora
            $horaInicio = date('H:i:s', strtotime($periodo['horaInicio']));
            $horaTermino = date('H:i:s', strtotime($periodo['horaTermino']));

            $queryInsert =  $this->eventoDB->insert('tblLotePeriodo', array(
                'idLote' => $idLote,
                'dataInicio' => $dataInicio,
                'dataTermino' => $dataTermino,
                'horaInicio' => $horaInicio,
                'horaTermino' => $horaTermino,
                'status' => 'T',
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
