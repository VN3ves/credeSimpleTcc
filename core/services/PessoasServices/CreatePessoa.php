<?php

class CreatePessoa
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createPessoa(
        $nome = null,
        $sobrenome = null,
        $apelido = null,
        $genero = null,
        $dataNascimento = null,
        $observacoes = null,
    ) {
        if (empty($nome) && empty($sobrenome) && empty($apelido) && empty($genero) && empty($dataNascimento)) {
            return false;
        }

        $dataNascimento = implode("-", array_reverse(explode("/", $dataNascimento)));

        $query = $this->db->insert('tblPessoa', array(
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'apelido' => $apelido,
            'genero' => $genero,
            'dataNascimento' => $dataNascimento,
            'observacoes' => $observacoes
        ));

        if (!$query) {
            return false;
        }

        return $query;
    }
}
