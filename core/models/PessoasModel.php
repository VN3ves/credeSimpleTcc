<?php
class PessoasModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $db;

    private $id;
    private $nome;
    private $sobrenome;
    private $apelido;
    private $genero;
    private $dataNascimento;
    private $observacoes;
    private $avatar;

    private $erro;

    public function __construct($db = false, $controller = null)
    {
        $this->db = $db;

        $this->controller = $controller;

        $this->parametros = $this->controller->parametros;

        $this->userdata = $this->controller->userdata;
    }

    public function validarFormPessoa()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        $this->nome = isset($_POST["nome"]) ? $_POST["nome"] : null;
        $this->sobrenome = isset($_POST["sobrenome"]) ? $_POST["sobrenome"] : null;
        $this->apelido = isset($_POST["apelido"]) ? $_POST["apelido"] : null;
        $this->genero = isset($_POST["genero"]) ? $_POST["genero"] : null;
        $this->dataNascimento = isset($_POST["dataNascimento"]) ? $_POST["dataNascimento"] : null;
        $this->avatar = isset($_POST["diretorioAvatar"]) ? $_POST["diretorioAvatar"] : null;
        $this->observacoes = isset($_POST["observacoes"]) ? $_POST["observacoes"] : null;

        if (empty($this->nome)) {
            $this->erro .= "<br>Preencha o nome.";
        }
        if (!empty($this->nome)) {
            if (strlen($this->nome) > 255) {
                $this->erro .= "<br>O nome não pode ultrapassar o limite de 255 caracteres.";
            }
        }
        if (empty($this->sobrenome)) {
            $this->erro .= "<br>Preencha o sobrenome.";
        }
        if (!empty($this->sobrenome)) {
            if (strlen($this->sobrenome) > 255) {
                $this->erro .= "<br>O sobrenome não pode ultrapassar o limite de 255 caracteres.";
            }
        }
        if (empty($this->apelido)) {
            $this->erro .= "<br>Preencha o nome social ou tratamento.";
        }
        if (!empty($this->apelido)) {
            if (strlen($this->apelido) > 255) {
                $this->erro .= "<br>O nome social ou tratamento não pode ultrapassar o limite de 255 caracteres.";
            }
        }
        if (empty($this->genero)) {
            $this->erro .= "<br>Selecione o gênero.";
        }
        if (empty($this->dataNascimento)) {
            $this->erro .= "<br>Selecione a data da nascimento.";
        }
        if (!empty($this->dataNascimento)) {
            if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $this->dataNascimento)) {
                $this->erro .= "<br>Data de nascimento inválida.";
            }
        }

        $this->form_data['nome'] = trim($this->nome);
        $this->form_data['sobrenome'] = trim($this->sobrenome);
        $this->form_data['apelido'] = trim($this->apelido);
        $this->form_data['genero'] = trim($this->genero);
        $this->form_data['dataNascimento'] = trim($this->dataNascimento);
        $this->form_data['observacoes'] = trim($this->observacoes);
        $this->form_data['avatar'] = trim($this->avatar);

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Os seguintes erros foram encontrados:</strong>' . $this->erro);
            return;
        }

        if (empty($this->form_data)) {
            return;
        }

        if (chk_array($this->parametros, 0) == 'editar') {
            $this->editarPessoa();

            return;
        } else {
            $this->adicionarPessoa();

            return;
        }
    }

    private function editarPessoa()
    {

        $hash = chk_array($this->parametros, 1);
        $this->id = decryptHash($hash);

        $updatePessoaService = $this->getDefaultService('PessoasServices', 'UpdatePessoa');
        $editaPessoa = $updatePessoaService->updatePessoa(
            $this->id,
            chk_array($this->form_data, 'nome'),
            chk_array($this->form_data, 'sobrenome'),
            chk_array($this->form_data, 'apelido'),
            chk_array($this->form_data, 'genero'),
            chk_array($this->form_data, 'dataNascimento'),
            chk_array($this->form_data, 'observacoes')
        );

        if (!$editaPessoa) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
        } else {
            $this->form_msg = $this->controller->Messages->success('Registro editado com sucesso. Aguarde, você será redirecionado...');
            $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/pessoas/index/editar/' . chk_array($this->parametros, 1) . '">';
        }

        return;
    }

    private function adicionarPessoa()
    {

        $createPessoaService = $this->getDefaultService('PessoasServices', 'CreatePessoa');
        $inserePessoa = $createPessoaService->createPessoa(
            chk_array($this->form_data, 'nome'),
            chk_array($this->form_data, 'sobrenome'),
            chk_array($this->form_data, 'apelido'),
            chk_array($this->form_data, 'genero'),
            chk_array($this->form_data, 'dataNascimento'),
            chk_array($this->form_data, 'observacoes')
        );

        $this->id = $this->db->lastInsertId();

        if (!$inserePessoa) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
        } else {
            $this->form_msg = $this->controller->Messages->success('Registro cadastrado com sucesso. Aguarde, você será redirecionado...');
            $hash = encryptId($this->id);
            $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/pessoas/index/editar/' . $hash . '">';
            $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/pessoas/index/editar/' . $hash . '";</script>';

            $this->form_data = null;
        }

        return;
    }

    public function getPessoa($id = false)
    {
        if (empty($id)) {
            return;
        }

        $getPessoaService = $this->getDefaultService('PessoasServices', 'GetPessoa');
        $registro = $getPessoaService->getPessoa($id);

        if (empty($registro)) {
            $this->form_msg = $this->controller->Messages->error('Registro inexistente.');

            return;
        }

        foreach ($registro as $key => $value) {
            $this->form_data[$key] = $value;
        }

        return;
    }

    public function getPessoas($filtros = null)
    {
        $getPessoasService = $this->getDefaultService('PessoasServices', 'GetPessoas');
        return $getPessoasService->getPessoas($filtros);
    }

    public function getAvatar($id = null, $tn = false)
    {
        $getAvatarService = $this->getDefaultService('PessoasServices', 'GetAvatar');
        return $getAvatarService->getAvatar($id, $tn);
    }

    public function bloquearPessoa()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $query = $this->db->update('tblPessoa', 'id', $id, array('status' => 'F'));

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
            } else {
                $this->form_msg = $this->controller->Messages->success('Registro bloqueado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/pessoas/">';
            }

            return;
        }
    }

    public function desbloquearPessoa()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $query = $this->db->update('tblPessoa', 'id', $id, array('status' => 'T'));

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
            } else {
                $this->form_msg = $this->controller->Messages->success('Registro desbloqueado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/pessoas/">';
            }

            return;
        }
    }

    public function getEmpresasRelacionadas($idPessoa)
    {
        // Removido pois não temos mais tabela de empresa
        return array();
    }
    
    /**
     * Método simplificado para cadastrar pessoa pelo credenciamento
     */
    public function cadastrarPessoa($dados)
    {
        try {
            // Primeiro insere a pessoa
            $dadosPessoa = [
                'nome' => $dados['nome'],
                'sobrenome' => $dados['sobrenome'],
                'apelido' => isset($dados['apelido']) ? $dados['apelido'] : $dados['nome'],
                'genero' => isset($dados['genero']) ? $dados['genero'] : null,
                'dataNascimento' => isset($dados['dataNascimento']) ? $dados['dataNascimento'] : '1990-01-01',
                'status' => 'T',
                'observacoes' => isset($dados['observacoes']) ? $dados['observacoes'] : ''
            ];
            
            $query = $this->db->insert('tblPessoa', $dadosPessoa);
            
            if (!$query) {
                return false;
            }
            
            $idPessoa = $this->db->lastInsertId();
            
            // Se tem CPF, insere documento
            if (!empty($dados['cpf'])) {
                $dadosDocumento = [
                    'idPessoa' => $idPessoa,
                    'tipo' => 'CPF',
                    'documento' => $dados['cpf'],
                    'status' => 'T'
                ];
                $this->db->insert('tblDocumento', $dadosDocumento);
            }
            
            // Se tem telefone, insere telefone
            if (!empty($dados['telefone'])) {
                $dadosTelefone = [
                    'idPessoa' => $idPessoa,
                    'tipo' => 'Celular',
                    'telefone' => $dados['telefone'],
                    'status' => 'T'
                ];
                $this->db->insert('tblTelefone', $dadosTelefone);
            }
            
            // Se tem email, insere email
            if (!empty($dados['email'])) {
                $dadosEmail = [
                    'idPessoa' => $idPessoa,
                    'email' => $dados['email'],
                    'status' => 'T'
                ];
                $this->db->insert('tblEmail', $dadosEmail);
            }
            
            return ['id' => $idPessoa];
            
        } catch (Exception $e) {
            Log::error('Erro ao cadastrar pessoa: ' . $e->getMessage());
            return false;
        }
    }
}
