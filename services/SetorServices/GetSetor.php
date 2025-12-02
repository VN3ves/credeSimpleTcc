<?php
class GetSetor extends MainModel
{

    public function getSetor($id = false)
    {
        $s_id = false;

        if (!empty($id)) {
            $s_id = (int) $id;
        }

        if (empty($s_id)) {
            return;
        }

        $query = $this->eventoDB->query('SELECT * FROM `tblSetor` WHERE `id` = ?', array($s_id));

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
