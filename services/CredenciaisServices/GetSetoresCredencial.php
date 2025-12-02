<?php

class GetSetoresCredencial extends MainModel
{
    /**
     * Busca os setores de uma credencial
     * 
     * @param int $idCredencial ID da credencial
     * @return array Array com os dados dos períodos
     */
    public function getSetoresCredencial($idCredencial)
    {
        $query = $this->eventoDB->query(
            "SELECT tblRelCredencialSetor.*
            FROM tblRelCredencialSetor 
            WHERE tblRelCredencialSetor.idCredencial = ?",
            array($idCredencial)
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}