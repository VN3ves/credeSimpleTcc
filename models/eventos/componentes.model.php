<?php

class ComponentesModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $db;
    private $erro;

    public function __construct($db = false, $controller = null)
    {
        $this->db = $db;
        $this->controller = $controller;
        $this->parametros = $this->controller->parametros;
        $this->userdata = $this->controller->userdata;
    }

    public function validarFormComponentes()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        // Captura os dados do formulário
        $this->form_data['idEvento'] = decryptHash(chk_array($this->parametros, 1));
        $this->form_data['qtdTerminais'] = isset($_POST['qtdTerminais']) ? $_POST['qtdTerminais'] : 0;
        $this->form_data['qtdSetores'] = isset($_POST['qtdSetores']) ? $_POST['qtdSetores'] : 0;
        $this->form_data['usaLeitorFacial'] = isset($_POST['usaLeitorFacial']) ? 'S' : 'N';
        $this->form_data['qtdLeitoresFaciais'] = isset($_POST['qtdLeitoresFaciais']) ? $_POST['qtdLeitoresFaciais'] : 0;
        $this->form_data['credenciaisImpressas'] = isset($_POST['credenciaisImpressas']) ? 'S' : 'N';
        $this->form_data['qtdLotesCredenciais'] = isset($_POST['qtdLotesCredenciais']) ? $_POST['qtdLotesCredenciais'] : 0;

        // Validações básicas
        if ($this->form_data['usaLeitorFacial'] == 'S' && empty($this->form_data['qtdLeitoresFaciais'])) {
            $this->erro .= "<br>Informe a quantidade de leitores faciais.";
        }

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Erros encontrados:</strong>' . $this->erro);
            return;
        }

        // Verifica se já existe componente para este evento
        $componenteExistente = $this->getComponente($this->form_data['idEvento']);

        if ($componenteExistente) {
            $this->atualizarComponente($componenteExistente['id']);
        } else {
            $this->criarComponente();
        }
    }

    public function getComponente($idEvento)
    {
        $getComponenteService = $this->getService('EventoServices', 'GetComponente');
        return $getComponenteService->getComponente($idEvento);
    }

    private function criarComponente()
    {
        $detalhes = base64_encode(serialize($this->form_data));

        $response = $this->createComponente($detalhes);

        if (!$response) {
            $this->form_msg = $this->controller->Messages->error('Erro ao criar componente.');
            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Componente criado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/eventos/index/editar/' . chk_array($this->parametros, 1) . '/componentes">';
            $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/eventos/index/editar/' . chk_array($this->parametros, 1) . '/componentes";</script>';
            return;
        }
    }

    private function atualizarComponente($id)
    {

        $this->form_data['id'] = $id;
        $detalhes = base64_encode(serialize($this->form_data));

        $response = $this->updateComponente($detalhes);

        if (!$response) {
            $this->form_msg = $this->controller->Messages->error('Erro ao atualizar componente.');
            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Componente atualizado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/eventos/index/editar/' . chk_array($this->parametros, 1) . '/componentes">';
            $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/eventos/index/editar/' . chk_array($this->parametros, 1) . '/componentes";</script>';
            return;
        }
    }

    public function base64ToFormData($base64)
    {
        $formData = unserialize(base64_decode($base64));
        return $formData;
    }

    public function createComponente($data)
    {
        $this->form_data = $this->base64ToFormData($data);
        $createComponenteService = $this->getService('EventoServices', 'CreateComponente');
        $query = $createComponenteService->createComponente($this->form_data);

        if (!$query) {
            return false;
        } else {
            return true;
        }
    }

    public function updateComponente($data)
    {
        $this->form_data = $this->base64ToFormData($data);
        $updateComponenteService = $this->getService('EventoServices', 'UpdateComponente');
        $query = $updateComponenteService->updateComponente($this->form_data['id'], $this->form_data);

        if (!$query) {
            return false;
        } else {
            return true;
        }
    }
}
