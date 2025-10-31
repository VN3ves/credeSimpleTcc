<?php

class EventosModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $db;

    private $evento;
    private $idCategoria;
    private $dataInicio;
    private $dataFim;
    private $licenca;
    private $dataInicioCredenciamento;
    private $dataFimCredenciamento;
    private $idLocal;
    private $observacao;

    // Dados para novo local se necessário
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

    private $cliente;
    private $dataInicioFim;
    private $valorContrato;
    private $imposto;
    private $verba;
    private $escritorio;
    private $midia;
    private $local;

    private $erro;

    public function __construct($db = false, $controller = null)
    {
        $this->db = $db;

        $this->controller = $controller;

        $this->parametros = $this->controller->parametros;

        $this->userdata = $this->controller->userdata;
    }

    public function validarFormEventos()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        // Captura os dados do formulário
        $this->evento = isset($_POST["nomeEvento"]) ? $_POST["nomeEvento"] : null;
        $this->idCategoria = isset($_POST["idCategoria"]) ? $_POST["idCategoria"] : null;
        $this->idLocal = isset($_POST["idLocal"]) ? $_POST["idLocal"] : null;
        $this->dataInicio = isset($_POST["dataInicio"]) ? $_POST["dataInicio"] : null;
        $this->dataFim = isset($_POST["dataFim"]) ? $_POST["dataFim"] : null;
        $this->dataInicioCredenciamento = isset($_POST["dataInicioCredenciamento"]) ? $_POST["dataInicioCredenciamento"] : null;
        $this->dataFimCredenciamento = isset($_POST["dataFimCredenciamento"]) ? $_POST["dataFimCredenciamento"] : null;
        $this->observacao = isset($_POST["observacao"]) ? $_POST["observacao"] : null;
        $this->licenca = isset($_POST["licenca"]) ? $_POST["licenca"] : null;

        // Dados para novo local se necessário
        $localData = isset($_POST["local"]) ? $_POST["local"] : array();

        $this->nomeOficial = isset($localData["nomeOficial"]) ? $localData["nomeOficial"] : null;
        $this->cep = isset($localData["cep"]) ? $localData["cep"] : null;
        $this->logradouro = isset($localData["logradouro"]) ? $localData["logradouro"] : null;
        $this->numero = isset($localData["numero"]) ? $localData["numero"] : null;
        $this->bairro = isset($localData["bairro"]) ? $localData["bairro"] : null;
        $this->cidade = isset($localData["cidade"]) ? $localData["cidade"] : null;
        $this->estado = isset($localData["estado"]) ? $localData["estado"] : null;
        $this->complemento = isset($localData["complemento"]) ? $localData["complemento"] : null;
        $this->latitude = isset($localData["latitude"]) ? $localData["latitude"] : null;
        $this->longitude = isset($localData["longitude"]) ? $localData["longitude"] : null;

        // Validações
        if (empty($this->evento)) {
            $this->erro .= "<br>Preencha o nome do evento.";
        }
        if (empty($this->idCategoria)) {
            $this->erro .= "<br>Selecione a categoria.";
        }
        if (empty($this->dataInicio)) {
            $this->erro .= "<br>Preencha a data de início do evento.";
        }
        if (empty($this->dataFim)) {
            $this->erro .= "<br>Preencha a data de término do evento.";
        }

        // Validação das datas
        if (!empty($this->dataInicio) && !empty($this->dataFim)) {
            if (strtotime($this->dataFim) < strtotime($this->dataInicio)) {
                $this->erro .= "<br>Data de término deve ser maior que a data de início.";
            }
        }

        // Validação do local
        if (empty($this->idLocal) && empty($this->nomeOficial)) {
            $this->erro .= "<br>Selecione um local ou preencha os dados para um novo local.";
        }

        // Se não tem idLocal mas tem nome oficial, valida os campos obrigatórios do novo local
        if (empty($this->idLocal) && !empty($this->nomeOficial)) {
            if (empty($this->cep)) {
                $this->erro .= "<br>Preencha o CEP do local.";
            }
            if (empty($this->logradouro)) {
                $this->erro .= "<br>Preencha o logradouro do local.";
            }
            if (empty($this->numero)) {
                $this->erro .= "<br>Preencha o número do local.";
            }
            if (empty($this->bairro)) {
                $this->erro .= "<br>Preencha o bairro do local.";
            }
            if (empty($this->cidade)) {
                $this->erro .= "<br>Preencha a cidade do local.";
            }
            if (empty($this->estado)) {
                $this->erro .= "<br>Preencha o estado do local.";
            }
        }

        // Prepara os dados para o formulário
        $this->form_data['nomeEvento'] = $this->evento;
        $this->form_data['idCategoria'] = $this->idCategoria;
        $this->form_data['idLocal'] = $this->idLocal;
        $this->form_data['dataInicio'] = $this->dataInicio;
        $this->form_data['dataFim'] = $this->dataFim;
        $this->form_data['dataInicioCredenciamento'] = $this->dataInicioCredenciamento;
        $this->form_data['dataFimCredenciamento'] = $this->dataFimCredenciamento;
        $this->form_data['observacao'] = $this->observacao;
        $this->form_data['licenca'] = $this->licenca;

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Os seguintes erros foram encontrados:</strong>' . $this->erro);
            return;
        }

        if (empty($this->form_data)) {
            return;
        }

        // Se não tiver idLocal mas tiver dados do local, cria um novo local
        if (empty($this->idLocal) && !empty($this->nomeOficial)) {
            $createLocalService = $this->getService('LocalServices', 'CreateLocal');
            $query = $createLocalService->createLocal(
                $this->nomeOficial,
                $this->cep,
                $this->logradouro,
                $this->numero,
                $this->bairro,
                $this->cidade,
                $this->estado,
                $this->complemento,
                $this->latitude,
                $this->longitude
            );

            $error = $this->db->error();
            if ($query) {
                $this->form_data['idLocal'] = $this->db->lastInsertId();
            } else {
                $this->form_msg = $this->controller->Messages->error('Erro ao criar local.');
                return;
            }
        }

        if (chk_array($this->parametros, 0) == 'editar') {
            $this->editarEvento();
            return;
        } else {
            $this->adicionarEvento();
            return;
        }
    }

    private function editarEvento()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $updateEventoService = $this->getService('EventoServices', 'UpdateEvento');
            $query = $updateEventoService->updateEvento(
                $id,
                chk_array($this->form_data, 'idCategoria'),
                chk_array($this->form_data, 'idLocal'),
                chk_array($this->form_data, 'nomeEvento'),
                chk_array($this->form_data, 'observacao'),
                chk_array($this->form_data, 'dataInicio'),
                chk_array($this->form_data, 'dataFim'),
                chk_array($this->form_data, 'licenca'),
                chk_array($this->form_data, 'dataInicioCredenciamento'),
                chk_array($this->form_data, 'dataFimCredenciamento')
            );

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Registro editado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/eventos/index/editar/' . chk_array($this->parametros, 1) . '">';
                $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/eventos/index/editar/' . chk_array($this->parametros, 1)  . '">';

                return;
            }
        }
    }

    private function adicionarEvento()
    {
        $createEventoService = $this->getService('EventoServices', 'CreateEvento');
        $query = $createEventoService->createEvento(
            chk_array($this->form_data, 'idCategoria'),
            chk_array($this->form_data, 'idLocal'),
            chk_array($this->form_data, 'nomeEvento'),
            chk_array($this->form_data, 'observacao'),
            chk_array($this->form_data, 'dataInicio'),
            chk_array($this->form_data, 'dataFim'),
            chk_array($this->form_data, 'licenca'),
            chk_array($this->form_data, 'dataInicioCredenciamento'),
            chk_array($this->form_data, 'dataFimCredenciamento')
        );

        $idEvento = $this->db->lastInsertId();

        if (!$query) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');

            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Registro cadastrado com sucesso.');
            $hash = encryptId($idEvento);
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/eventos/index/editar/' . $hash . '">';
            $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/eventos/index/editar/' . $hash . '";</script>';

            $this->form_data = null;

            return;
        }
    }

    public function getEvento($id = false)
    {
        if (empty($id)) {
            return;
        }

        $getEventoService = $this->getService('EventoServices', 'GetEvento');
        $registro = $getEventoService->getEvento($id);

        if (empty($registro)) {
            $this->form_msg = $this->controller->Messages->error('Registro inexistente.');
            return;
        }

        foreach ($registro as $key => $value) {
            $this->form_data[$key] = $value;
        }

        return $registro;
    }

    public function getEventos($filtros = null)
    {
        $getEventosService = $this->getService('EventoServices', 'GetEventos');
        return $getEventosService->getEventos($filtros);
    }

    public function setEvento($idPost, $nomeDB)
    {
        $hash = encryptId($idPost);
        if ($idPost == 0) {
            $hash = 0;
        }
        if ($_SESSION['idEventoHash'] != $hash) {
            $_SESSION['idEventoHash'] = $hash;
        }


        return;
    }


    public function checkEventoDB($idEvento = null)
    {
        return true;
    }

    public function getAvatar($id = null, $tn = false)
    {
        try {
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                return "midia/noPictureProfile.png";
            }

            // Buscar a logo mais recente na tabela de arquivos
            $query = $this->db->query(
                "SELECT pathLocal 
                 FROM tblArquivo 
                 WHERE idReferencia = ? 
                 AND tipoReferencia = 'EVENTO' 
                 AND tipoArquivo = 'LOGO'
                 ORDER BY dataCadastro DESC 
                 LIMIT 1",
                array($id)
            );

            if (!$query || $query->rowCount() == 0) {
                return "midia/noPictureProfile.png";
            }

            $arquivo = $query->fetch();
            $path = $arquivo['pathLocal'];

            // Se solicitado thumbnail
            if ($tn) {
                $pathThumb = str_replace('/logo/', '/logo/thumb/', $path);
                if (file_exists(ABSPATH . $pathThumb)) {
                    return $pathThumb;
                }
            }

            if (!file_exists(ABSPATH . $path)) {
                return "midia/noPictureProfile.png";
            }

            return $path;
        } catch (Exception $e) {
            error_log("Erro ao buscar logo: " . $e->getMessage());
            return "midia/noPictureProfile.png";
        }
    }

    public function getBanner($id = null)
    {
        try {
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                return "midia/noBanner.png";
            }

            // Buscar o banner mais recente na tabela de arquivos
            $query = $this->db->query(
                "SELECT pathLocal 
                 FROM tblArquivo 
                 WHERE idReferencia = ? 
                 AND tipoReferencia = 'EVENTO' 
                 AND tipoArquivo = 'BANNER'
                 ORDER BY dataCadastro DESC 
                 LIMIT 1",
                array($id)
            );

            if (!$query || $query->rowCount() == 0) {
                return "midia/noBanner.png";
            }

            $arquivo = $query->fetch();
            $path = $arquivo['pathLocal'];

            if (!file_exists(ABSPATH . $path)) {
                return "midia/noBanner.png";
            }

            return $path;
        } catch (Exception $e) {
            error_log("Erro ao buscar banner: " . $e->getMessage());
            return "midia/noBanner.png";
        }
    }

    public function bloquearEvento()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $query = $this->db->update('tblEvento', 'id', $id, array('status' => 'F'));

            $this->form_msg = $this->controller->Messages->success('Evento bloqueado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/eventos">';

            return;
        }
    }

    public function desbloquearEvento()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $query = $this->db->update('tblEvento', 'id', $id, array('status' => 'T'));

            $this->form_msg = $this->controller->Messages->success('Evento desbloqueado com sucesso.');
            $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/eventos">';

            return;
        }
    }
}
