<?php

class DesbloquearCredencial extends MainModel
{
    /**
     * Desbloqueia um lote alterando seu status para 'T' (true)
     * 
     * @param int $id ID do lote a ser desbloqueado
     * @return bool Retorna true se o desbloqueio foi bem-sucedido, false caso contrário
     */
    public function desbloquearCredencial($id)
    {
        if (empty($id)) {
            return false;
        }

        $query =  $this->eventoDB->update('tblCredencial', 'id', $id, array('status' => 'T'));

        if (!$query) {
            return false;
        }

        return true;
    }
} 