<?php

class GetPeriodosCredencial extends MainModel
{
    /**
     * Busca os períodos de uma credencial
     * 
     * @param int $idCredencial ID da credencial
     * @return array Array com os dados dos períodos
     */
    public function getPeriodosCredencial($idCredencial)
    {
        $query = $this->eventoDB->query(
            "SELECT tblCredencialPeriodo.*
            FROM tblCredencialPeriodo 
            WHERE tblCredencialPeriodo.idCredencial = ?",
            array($idCredencial)
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}