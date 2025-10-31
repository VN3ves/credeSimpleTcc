<?php

class GetPessoas
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPessoas($filtros = null)
{
    $where = " WHERE 1=1 ";
    $limit = null;
    $orderby = "ORDER BY tblPessoa.nome, tblPessoa.sobrenome"; // padrão

    if (!empty($filtros["genero"])) {
        $where .= " AND tblPessoa.genero = '" . $filtros['genero'] . "'";
    }

    if (!empty($filtros["status"])) {
        $where .= " AND tblPessoa.status = '" . $filtros['status'] . "'";
    }

    if (!empty($filtros["q"])) {
        $q = _otimizaBusca($filtros["q"]);
        $where .= " AND (
            tblPessoa.nome LIKE '%$q%' OR
            tblPessoa.sobrenome LIKE '%$q%' OR
            tblPessoa.apelido LIKE '%$q%' OR
            tblPessoa.genero LIKE '%$q%' OR
            tblPessoa.dataNascimento LIKE '%$q%' OR
            tblPessoa.observacoes LIKE '%$q%' OR
            EXISTS (
                SELECT 1 FROM tblDocumento WHERE tblDocumento.idPessoa = tblPessoa.id AND (
                    tblDocumento.tipo LIKE '%$q%' OR
                    tblDocumento.titulo LIKE '%$q%' OR
                    tblDocumento.documento LIKE '%$q%'
                )
            ) OR
            EXISTS (
                SELECT 1 FROM tblEndereco WHERE tblEndereco.idPessoa = tblPessoa.id AND (
                    tblEndereco.titulo LIKE '%$q%' OR
                    tblEndereco.cep LIKE '%$q%' OR
                    tblEndereco.logradouro LIKE '%$q%' OR
                    tblEndereco.numero LIKE '%$q%' OR
                    tblEndereco.bairro LIKE '%$q%' OR
                    tblEndereco.cidade LIKE '%$q%' OR
                    tblEndereco.estado LIKE '%$q%' OR
                    tblEndereco.complemento LIKE '%$q%' OR
                    tblEndereco.zona LIKE '%$q%' OR
                    tblEndereco.latitude LIKE '%$q%' OR
                    tblEndereco.longitude LIKE '%$q%'
                )
            ) OR
            EXISTS (
                SELECT 1 FROM tblEmail WHERE tblEmail.idPessoa = tblPessoa.id AND tblEmail.email LIKE '%$q%'
            ) OR
            EXISTS (
                SELECT 1 FROM tblTelefone WHERE tblTelefone.idPessoa = tblPessoa.id AND (
                    tblTelefone.telefone LIKE '%$q%' OR tblTelefone.tipo LIKE '%$q%'
                )
            )
        )";
    }

    if (!empty($filtros["limite"])) {
        $limit = "LIMIT " . intval($filtros["limite"]);
    }

    if (!empty($filtros["ordena"]) && !empty($filtros["ordem"])) {
        $ordena = preg_replace('/[^a-zA-Z0-9_]/', '', $filtros["ordena"]);
        $ordem = strtoupper($filtros["ordem"]) === 'DESC' ? 'DESC' : 'ASC';
        $orderby = "ORDER BY $ordena $ordem";
    }

    $sql = "SELECT 
        tblPessoa.*,
        (
            SELECT email 
            FROM tblEmail 
            WHERE tblEmail.idPessoa = tblPessoa.id 
            ORDER BY tblEmail.id ASC 
            LIMIT 1
        ) AS email,
        (
            SELECT telefone 
            FROM tblTelefone 
            WHERE tblTelefone.idPessoa = tblPessoa.id 
            ORDER BY tblTelefone.id ASC 
            LIMIT 1
        ) AS telefone
    FROM tblPessoa
    $where
    $orderby
    $limit;";

    $query = $this->db->query($sql);

    if (!$query) {
        return array();
    }

    return $query->fetchAll();
}

}
