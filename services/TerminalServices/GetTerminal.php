<?php
class GetTerminal extends MainModel{

    public function getTerminal($id = false) {
        $s_id = false;
        
        if (!empty($id)) {
            $s_id = (int) $id;
        }
        
        if (empty($s_id)) {
            return;
        }

        $query = $this->eventoDB->query('
            SELECT t.*, s.nomeSetor 
            FROM `tblTerminal` t
            LEFT JOIN `tblSetor` s ON s.id = t.idSetor
            WHERE t.`id` = ?', 
            array($s_id)
        );
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }
} 