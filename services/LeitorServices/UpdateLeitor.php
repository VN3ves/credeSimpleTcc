<?php
class UpdateLeitor extends MainModel
{

    public function updateLeitor($id, $data)
    {
        if (empty($id) || empty($data['nomeLeitor']) || empty($data['idSetor'])) {
            return false;
        }

        $query = $this->eventoDB->update('tblLeitor', 'id', $id, array(
            'idSetor' => $data['idSetor'],
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
