<?php
class UpdateSetor extends MainModel
{

    public function updateSetor($id, $nomeSetor, $observacao, $status)
    {
        if (empty($id) || empty($nomeSetor)) {
            return false;
        }

        $query = $this->eventoDB->update('tblSetor', 'id', $id, array(
            'nomeSetor' => $nomeSetor,
            'observacao' => $observacao,
            'status' => $status
        ));

        if (!$query) {
            return false;
        }

        return $query;
    }
}
