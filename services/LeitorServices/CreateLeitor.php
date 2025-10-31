<?php
class CreateLeitor extends MainModel
{
    public function createLeitor($data)
    {
        if (empty($data['nomeLeitor']) || empty($data['idSetor'])) {
            return false;
        }

        $query = $this->eventoDB->insert('tblLeitor', array(
            'idEvento' => $data['idEvento'],
            'idSetor' => $data['idSetor'],
            'idTerminal' => $data['idTerminal'],
            'nomeLeitor' => $data['nomeLeitor'],
            'ip' => $data['ip'],
            'usuario' => $data['usuario'],
            'senha' => $data['senha'],
            'deviceId' => $data['deviceId'],
            'serverId' => $data['serverId'],
            'session' => $data['session'],
            'observacao' => $data['observacao'],
            'status' => $data['status']
        ));

        if (!$query) {
            return false;
        }

        return $query;
    }
}
