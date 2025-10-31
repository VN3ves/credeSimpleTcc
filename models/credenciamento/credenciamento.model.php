<?php
class CredenciamentoModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $db;

    public function __construct($db = null, $controller = null)
    {
        $this->db = $db;
        $this->controller = $controller;
        $this->parametros = $this->controller->parametros;
        $this->userdata = $this->controller->userdata;
    }

    public function buscaDoc($doc, $idEvento)
    {
        // Retira pontuação do documento
        $doc = preg_replace('/[^\d]/', '', $doc);


        // Busca a pessoa pelo documento de qualquer forma  
        $sql = "SELECT tblPessoa.* FROM tblPessoa 
        INNER JOIN tblDocumento ON tblPessoa.id = tblDocumento.idPessoa
        WHERE tblDocumento.documento LIKE ? AND (tblDocumento.tipo = 'CPF' OR tblDocumento.tipo = 'Passaporte')";

        $query = $this->db->query($sql, array("%$doc%"));

        $resultado = $query->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Existindo a pessoa ja cadastrada, verificamos se ja esta cadastrada no evento
            $buscaPessoaEvento = $this->getService('CredenciamentoServices', 'BuscaPessoaEvento');
            $idPessoa = $resultado['id'];
            $resultado2 = $buscaPessoaEvento->BuscaPessoaEvento($idPessoa, $idEvento);
        }

        $resposta = array(
            'pessoa' => $resultado,
            'credencial' => isset($resultado2) ? $resultado2 : null
        );

        return $resposta;
    }
}
