<?php
class LeitoresModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $db;
    public $eventoDB;

    private $idSetor;
    private $idTerminal;
    private $nomeLeitor;
    private $ip;
    private $usuario;
    private $senha;
    private $deviceId;
    private $serverId;
    private $session;
    private $observacao;
    private $status;
    private $idEvento;

    private $erro;

    public function __construct($db = false, $controller = null, $eventoDB = null)
    {
        $this->eventoDB = $eventoDB;

        $this->controller = $controller;

        $this->parametros = $this->controller->parametros;

        $this->userdata = $this->controller->userdata;
    }

    public function validarFormLeitor()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        $this->idSetor = isset($_POST['idSetor']) ? $_POST['idSetor'] : null;
        $this->idTerminal = isset($_POST['idTerminal']) ? $_POST['idTerminal'] : null;
        $this->nomeLeitor = isset($_POST['nomeLeitor']) ? $_POST['nomeLeitor'] : null;
        $this->ip = isset($_POST['ip']) ? $_POST['ip'] : null;
        $this->usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
        $this->senha = isset($_POST['senha']) ? $_POST['senha'] : null;
        $this->deviceId = isset($_POST['deviceId']) ? $_POST['deviceId'] : null;
        $this->serverId = isset($_POST['serverId']) ? $_POST['serverId'] : null;
        $this->session = isset($_POST['session']) ? $_POST['session'] : null;
        $this->observacao = isset($_POST['observacao']) ? $_POST['observacao'] : null;
        $this->status = isset($_POST['status']) ? $_POST['status'] : 'F';
        $this->idEvento = isset($_POST['idEvento']) ? $_POST['idEvento'] : null;

        if (empty($this->idSetor)) {
            $this->erro .= "<br>Selecione um setor.";
        }

        if (empty($this->nomeLeitor)) {
            $this->erro .= "<br>Preencha o nome do leitor.";
        }

        if (empty($this->ip)) {
            $this->erro .= "<br>Preencha o IP do leitor.";
        }

        $this->form_data = array(
            'idSetor' => $this->idSetor,
            'idTerminal' => $this->idTerminal,
            'nomeLeitor' => $this->nomeLeitor,
            'ip' => $this->ip,
            'usuario' => $this->usuario,
            'senha' => $this->senha,
            'deviceId' => $this->deviceId,
            'serverId' => $this->serverId,
            'session' => $this->session,
            'observacao' => $this->observacao,
            'status' => $this->status,
            'idEvento' => $this->idEvento,
        );

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Os seguintes erros foram encontrados:</strong>' . $this->erro);
            return;
        }

        if (empty($this->form_data)) {
            return;
        }

        if (chk_array($this->parametros, 0) == 'editar') {
            $this->editarLeitor();
            return;
        } else {
            $this->adicionarLeitor();
            return;
        }
    }

    private function editarLeitor()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $updateLeitorService = $this->getService('LeitorServices', 'UpdateLeitor');
            $query = $updateLeitorService->updateLeitor($id, $this->form_data);

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Leitor editado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/recursos/leitores">';
                return;
            }
        }
    }

    private function adicionarLeitor()
    {
        $createLeitorService = $this->getService('LeitorServices', 'CreateLeitor');
        $query = $createLeitorService->createLeitor($this->form_data);

        if (!$query) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Leitor cadastrado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/recursos/leitores">';
            return;
        }
    }

    public function getLeitor($id = false)
    {
        if (empty($id)) {
            return;
        }
        $getLeitorService = $this->getService('LeitorServices', 'GetLeitor');
        $registro = $getLeitorService->getLeitor($id);

        if (empty($registro)) {
            $this->form_msg = $this->controller->Messages->error('Leitor inexistente.');
            return;
        }

        return $registro;
    }

    public function getLeitores()
    {
        $getLeitoresService = $this->getService('LeitorServices', 'GetLeitores');
        return $getLeitoresService->getLeitores();
    }
}
