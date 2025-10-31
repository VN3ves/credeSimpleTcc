<?php 
class SetoresModel extends MainModel {
    public $form_data;
	public $form_msg;
	public $db;
    public $eventoDB;

    private $nomeSetor;
    private $observacao;
    private $status;

    private $erro;

    public function __construct($db = false, $controller = null, $eventoDB = null){
		$this->eventoDB = $eventoDB;
		
		$this->controller = $controller;
		
		$this->parametros = $this->controller->parametros;

		$this->userdata = $this->controller->userdata;
	}

    public function validarFormSetor() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        $this->nomeSetor = isset($_POST['nomeSetor']) ? $_POST['nomeSetor'] : null;
        $this->observacao = isset($_POST['observacao']) ? $_POST['observacao'] : null;
        $this->status = isset($_POST['status']) ? $_POST['status'] : 'F';

        if (empty($this->nomeSetor)) {
            $this->erro .= "<br>Preencha o nome do setor.";
        }

        $this->form_data['nomeSetor'] = $this->nomeSetor;
        $this->form_data['observacao'] = $this->observacao;
        $this->form_data['status'] = $this->status;

        $idEvento = decryptHash($_SESSION['idEventoHash']);
        $this->form_data['idEvento'] = $idEvento;

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Os seguintes erros foram encontrados:</strong>' . $this->erro);

            return;
        }

        if (empty($this->form_data)) {
            return;
        }

        if (chk_array($this->parametros, 0) == 'editar') {
            $this->editarSetor();
            return;
        } else {
            $this->adicionarSetor();
            return;
        }
    }

    private function editarSetor() {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $updateSetorService = $this->getService('SetorServices', 'UpdateSetor');
            $query = $updateSetorService->updateSetor(
                $id,
                chk_array($this->form_data, 'nomeSetor'),
                chk_array($this->form_data, 'observacao'),
                chk_array($this->form_data, 'status')
            );

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Setor editado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/recursos/setores">';
                return;
            }
        }
    }

    private function adicionarSetor() {
        $createSetorService = $this->getService('SetorServices', 'CreateSetor');
        $query = $createSetorService->createSetor(
            chk_array($this->form_data, 'nomeSetor'),
            chk_array($this->form_data, 'observacao'),
            chk_array($this->form_data, 'status'),
            chk_array($this->form_data, 'idEvento'),
        );

        if (!$query) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Setor cadastrado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/recursos/setores">';
            return;
        }
    }

    public function getSetor($id = false) {
        if (empty($id)) {
            return;
        }
        $getSetorService = $this->getService('SetorServices', 'GetSetor');
        $registro = $getSetorService->getSetor($id);

        if (empty($registro)) {
            $this->form_msg = $this->controller->Messages->error('Setor inexistente.');
            return;
        }

        return $registro;
    }

    public function getSetores($filtros = array()) {
        $getSetoresService = $this->getService('SetorServices', 'GetSetores');
        return $getSetoresService->getSetores($filtros);
    }

}