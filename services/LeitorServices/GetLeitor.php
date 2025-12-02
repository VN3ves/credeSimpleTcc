<?php
class GetLeitor extends MainModel{

    public function getLeitor($id = false) {
        $s_id = false;
        
        if (!empty($id)) {
            $s_id = (int) $id;
        }
        
        if (empty($s_id)) {
            return;
        }

        $query = $this->eventoDB->query('
            SELECT l.*, s.nomeSetor
            FROM `tblLeitor` l
            LEFT JOIN `tblSetor` s ON s.id = l.idSetor
            WHERE l.`id` = ?', 
            array($s_id)
        );
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }
} 