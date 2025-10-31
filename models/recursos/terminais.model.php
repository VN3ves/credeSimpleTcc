<?php 
class TerminaisModel extends MainModel {
    public $form_data;
	public $form_msg;
	public $db;
    public $eventoDB;

    private $idEvento;
    private $idSetor;
    private $nomeTerminal;
    private $tipo;
    private $ip;
    private $deviceId;
    private $observacao;
    private $status;
	
    private $erro;

    public function __construct($db = false, $controller = null, $eventoDB = null){
		$this->eventoDB = $eventoDB;
		
		$this->controller = $controller;
		
		$this->parametros = $this->controller->parametros;

		$this->userdata = $this->controller->userdata;
	}

    public function validarFormTerminal() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        $this->idSetor = isset($_POST['idSetor']) ? $_POST['idSetor'] : null;
        $this->nomeTerminal = isset($_POST['nomeTerminal']) ? $_POST['nomeTerminal'] : null;
        $this->tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'AMBOS';
        $this->ip = isset($_POST['ip']) ? $_POST['ip'] : null;
        $this->deviceId = isset($_POST['deviceId']) ? $_POST['deviceId'] : null;
        $this->observacao = isset($_POST['observacao']) ? $_POST['observacao'] : null;
        $this->status = isset($_POST['status']) ? $_POST['status'] : 'F';
        $this->idEvento = isset($_POST['idEvento']) ? $_POST['idEvento'] : null;

        if (empty($this->idSetor)) {
            $this->erro .= "<br>Selecione um setor.";
        }

        if (empty($this->nomeTerminal)) {
            $this->erro .= "<br>Preencha o nome do terminal.";
        }

        $this->form_data = array(
            'idSetor' => $this->idSetor,
            'nomeTerminal' => $this->nomeTerminal,
            'tipo' => $this->tipo,
            'ip' => $this->ip,
            'deviceId' => $this->deviceId,
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
            $this->editarTerminal();
            return;
        } else {
            $this->adicionarTerminal();
            return;
        }
    }

    private function editarTerminal() {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $updateTerminalService = $this->getService('TerminalServices', 'UpdateTerminal');
            $query = $updateTerminalService->updateTerminal($id, $this->form_data);

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Terminal editado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/recursos/terminais">';
                return;
            }
        }
    }

    private function adicionarTerminal() {
        $createTerminalService = $this->getService('TerminalServices', 'CreateTerminal');
        $query = $createTerminalService->createTerminal($this->form_data);

        if (!$query) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Terminal cadastrado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/recursos/terminais">';
            return;
        }
    }

    public function getTerminal($id = false) {
        if (empty($id)) {
            return;
        }
        $getTerminalService = $this->getService('TerminalServices', 'GetTerminal');
        $registro = $getTerminalService->getTerminal($id);

        if (empty($registro)) {
            $this->form_msg = $this->controller->Messages->error('Terminal inexistente.');
            return;
        }

        return $registro;
    }

    public function getTerminais($filtros = array()) {
        $getTerminaisService = $this->getService('TerminalServices', 'GetTerminais');
        return $getTerminaisService->getTerminais($filtros);
    }
}