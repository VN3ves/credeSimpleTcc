<?php
class UpdateTerminal extends MainModel
{

    public function updateTerminal($id, $data)
    {
        if (empty($id) || empty($data['nomeTerminal']) || empty($data['idSetor'])) {
            return false;
        }

        $query = $this->eventoDB->update('tblTerminal', 'id', $id, array(
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
