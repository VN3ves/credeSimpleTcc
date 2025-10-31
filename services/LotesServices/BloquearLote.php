<?php

class BloquearLote extends MainModel
{
    /**
     * Bloqueia um lote alterando seu status para 'F' (false)
     * 
     * @param int $id ID do lote a ser bloqueado
     * @return bool Retorna true se o bloqueio foi bem-sucedido, false caso contrário
     */
    public function bloquearLote($id)
    {
        if (empty($id)) {
            return false;
        }

        $query =  $this->eventoDB->update('tblLote', 'id', $id, array('status' => 'F'));

        if (!$query) {
            return false;
        }

        return true;
    }
} 