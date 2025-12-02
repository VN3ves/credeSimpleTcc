<?php

class LocaisModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $db;

    private $nomeOficial;
    private $cep;
    private $logradouro;
    private $numero;
    private $bairro;
    private $cidade;
    private $estado;
    private $complemento;
    private $latitude;
    private $longitude;
    
    private $erro;

    public function __construct($db = false, $controller = null)
    {
        $this->db = $db;
        $this->controller = $controller;
        $this->parametros = $this->controller->parametros;
        $this->userdata = $this->controller->userdata;
    }

    public function validarFormLocal()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        $this->nomeOficial = isset($_POST['nomeOficial']) ? $_POST['nomeOficial'] : null;
        $this->cep = isset($_POST['cep']) ? $_POST['cep'] : null;
        $this->logradouro = isset($_POST['logradouro']) ? $_POST['logradouro'] : null;
        $this->numero = isset($_POST['numero']) ? $_POST['numero'] : null;
        $this->bairro = isset($_POST['bairro']) ? $_POST['bairro'] : null;
        $this->cidade = isset($_POST['cidade']) ? $_POST['cidade'] : null;
        $this->estado = isset($_POST['estado']) ? $_POST['estado'] : null;
        $this->complemento = isset($_POST['complemento']) ? $_POST['complemento'] : null;
        $this->latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
        $this->longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;

        if (empty($this->nomeOficial)) {
            $this->erro .= "<br>Preencha o nome do local.";
        }

        if (!empty($this->cep)) {
            if (!preg_match('/^[0-9]{5}-[0-9]{3}$/', $this->cep)) {
                $this->erro .= "<br>CEP inválido.";
            }
        }

        $this->form_data['nomeOficial'] = $this->nomeOficial;
        $this->form_data['cep'] = $this->cep;
        $this->form_data['logradouro'] = $this->logradouro;
        $this->form_data['numero'] = $this->numero;
        $this->form_data['bairro'] = $this->bairro;
        $this->form_data['cidade'] = $this->cidade;
        $this->form_data['estado'] = $this->estado;
        $this->form_data['complemento'] = $this->complemento;
        $this->form_data['latitude'] = $this->latitude;
        $this->form_data['longitude'] = $this->longitude;

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Os seguintes erros foram encontrados:</strong>' . $this->erro);
            return;
        }

        if (empty($this->form_data)) {
            return;
        }

        if (chk_array($this->parametros, 0) == 'editar') {
            $this->editarLocal();
            return;
        } else {
            $this->adicionarLocal();
            return;
        }
    }

    private function editarLocal()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $updateLocalService = $this->getService('LocalServices', 'UpdateLocal');
            $query = $updateLocalService->updateLocal(
                $id,
                chk_array($this->form_data, 'nomeOficial'),
                chk_array($this->form_data, 'cep'),
                chk_array($this->form_data, 'logradouro'),
                chk_array($this->form_data, 'numero'),
                chk_array($this->form_data, 'bairro'),
                chk_array($this->form_data, 'cidade'),
                chk_array($this->form_data, 'estado'),
                chk_array($this->form_data, 'complemento'),
                chk_array($this->form_data, 'latitude'),
                chk_array($this->form_data, 'longitude')
            );

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Local editado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/eventos/locais/editar/' . chk_array($this->parametros, 1) . '">';
                $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/eventos/locais/editar/' . chk_array($this->parametros, 1) . '";</script>';
                return;
            }
        }
    }

    private function adicionarLocal()
    {
        $createLocalService = $this->getService('LocalServices', 'CreateLocal');
        $query = $createLocalService->createLocal(
            chk_array($this->form_data, 'nomeOficial'),
            chk_array($this->form_data, 'cep'),
            chk_array($this->form_data, 'logradouro'),
            chk_array($this->form_data, 'numero'),
            chk_array($this->form_data, 'bairro'),
            chk_array($this->form_data, 'cidade'),
            chk_array($this->form_data, 'estado'),
            chk_array($this->form_data, 'complemento'),
            chk_array($this->form_data, 'latitude'),
            chk_array($this->form_data, 'longitude')
        );

        $idLocal = $this->db->lastInsertId();

        if (!$query) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Local cadastrado com sucesso.');
            $hash = encryptId($idLocal);
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/eventos/locais/editar/' . $hash . '">';
            $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/eventos/locais/editar/' . $hash . '";</script>';
            $this->form_data = null;
            return;
        }
    }

    public function getLocal($id = false)
    {
        if (empty($id)) {
            Log::error('ID do local não informado no getLocal.');
            return;
        }
        $getLocalService = $this->getService('LocalServices', 'GetLocal');
        $registro = $getLocalService->getLocal($id);


        if (empty($registro)) {
            $this->form_msg = $this->controller->Messages->error('Local inexistente.');
            return;
        }

        return $registro;
    }

    public function getLocais($filtros = null)
    {
        $getLocaisService = $this->getService('LocalServices', 'GetLocais');
        return $getLocaisService->getLocais($filtros);
    }

    public function bloquearLocal()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $query = $this->db->update('tblEventoLocal', 'id', $id, array('status' => 'F'));

            $this->form_msg = $this->controller->Messages->success('Local bloqueado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/eventos/locais">';

            return;
        }
    }

    public function desbloquearLocal()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $query = $this->db->update('tblEventoLocal', 'id', $id, array('status' => 'T'));

            $this->form_msg = $this->controller->Messages->success('Local desbloqueado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/eventos/locais">';

            return;
        }
    }

}
