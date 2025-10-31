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
            SELECT l.*, s.nomeSetor, t.nomeTerminal 
            FROM `tblLeitor` l
            LEFT JOIN `tblSetor` s ON s.id = l.idSetor
            LEFT JOIN `tblTerminal` t ON t.id = l.idTerminal
            WHERE l.`id` = ?', 
            array($s_id)
        );
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }
} 