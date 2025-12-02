<?php

class UpdatePessoa
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function updatePessoa($id, $nome, $sobrenome, $apelido, $genero, $dataNascimento, $observacoes)
    {
        if (empty($id) && empty($nome) && empty($sobrenome) && empty($apelido) && empty($genero)) {
            return false;
        }

        $dataNascimento = implode("-", array_reverse(explode("/", $dataNascimento)));


        $query = $this->db->update(
            'tblPessoa',
            'id',
            $id,
            array(
                'nome' => $nome,
                'sobrenome' => $sobrenome,
                'apelido' => $apelido,
                'genero' => $genero,
                'dataNascimento' => $dataNascimento,
                'observacoes' => $observacoes
            )
        );

        if (!$query) {
            return false;
        }

        return $query;
    }
}
