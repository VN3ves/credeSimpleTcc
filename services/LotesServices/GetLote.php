<?php

class GetLote extends MainModel
{
    /**
     * Obtém um lote específico do banco de dados
     * 
     * @param int $id ID do lote a ser obtido
     * @return array|bool Retorna os dados do lote ou false se não encontrado
     */
    public function getLote($id)
    {
        if (empty($id)) {
            return false;
        }

        $query =  $this->eventoDB->query(
            "SELECT l.*
             FROM tblLote l
             WHERE l.id = ?",
            array($id)
        );

        if (!$query || $query->rowCount() == 0) {
            return false;
        }

        return $query->fetch(PDO::FETCH_ASSOC);
    }
} 