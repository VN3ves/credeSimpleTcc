<?php
class CreateTerminal extends MainModel
{

    public function createTerminal($data)
    {
        if (empty($data['nomeTerminal']) || empty($data['idSetor'])) {
            return false;
        }

        $query = $this->eventoDB->insert('tblTerminal', array(
            'idEvento' => $data['idEvento'],
            'idSetor' => $data['idSetor'],
            'nomeTerminal' => $data['nomeTerminal'],
            'tipo' => $data['tipo'],
            'ip' => $data['ip'],
            'deviceId' => $data['deviceId'],
            'observacao' => $data['observacao'],
            'status' => $data['status']
        ));

        if (!$query) {
            return false;
        }

        return $query;
    }
}
