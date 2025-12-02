<?php

class WhatsappSendBiz
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db;

        if (empty($this->db)) {
            $this->db = DBInstance::getInstance();
        }
    }

    public function enviarLinkRecuperacaoSenha($telefone, $mensagem)
    {
        $whatsUrl = WHATSAPP_SEND_BIZ_URL;
        $whatsToken = WHATSAPP_SEND_BIZ_TOKEN;

        // Verifica se o número de telefone tem o codigo do país
        if (substr($telefone, 0, 2) != '55') {
            $telefone = '55' . $telefone;
        }

        // Dados da mensagem a ser enviada
        $data = [
            'body' => $mensagem,
            'number' => $telefone
        ];

        // Configurar a requisição cURL
        $ch = curl_init($whatsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer $whatsToken"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Enviar a requisição e obter a resposta
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function enviarWhatsapp($idEvento, $mensagem, $telefone)
    {
        // Recuperar a URL e o token do banco de dados
        $whatsDados = $this->db->query(
            "SELECT * FROM tblEventoIntegracoes WHERE tipoIntegracao = 'whatsapp' AND idEvento = :idEvento",
            array(
                $idEvento
            )
        );
        $whatsDados->fetch(PDO::FETCH_ASSOC);
        $whatsUrl = $whatsDados['url'];
        $whatsToken = $whatsDados['token'];

        // Verifica se o número de telefone tem o codigo do país
        if (substr($telefone, 0, 2) != '55') {
            $telefone = '55' . $telefone;
        }

        // Dados da mensagem a ser enviada
        $data = [
            'body' => $mensagem,
            'number' => $telefone
        ];

        // Configurar a requisição cURL
        $ch = curl_init($whatsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer $whatsToken"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Enviar a requisição e obter a resposta
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


}
