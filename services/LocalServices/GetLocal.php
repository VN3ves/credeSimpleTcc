<?php

class GetLocal extends MainModel
{

    public function getLocal($id = false)
    {
        $s_id = false;
        
        if (!empty($id)) {
            $s_id = (int) $id;
        }
        
        if (empty($s_id)) {
            return;
        }
        
        $query = $this->db->query('SELECT * FROM `tblEventoLocal` WHERE `id` = ?', array($s_id));
        
        $registro = $query->fetch(PDO::FETCH_ASSOC);
    
        return $registro;
    }
} 