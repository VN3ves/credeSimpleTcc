<?php
class CredenciaisModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $db;

    private $id;
    private $idLote;
    private $idEvento;
    private $idPessoa;
    private $docPessoa;
    private $nomeCredencial;
    private $codigoCredencial;
    private $observacao;
    private $detalhes;
    private $impresso;
    private $status;
    private $erro;

    public function __construct($db = null, $controller = null)
    {
        $this->db = $db;
        $this->controller = $controller;
        $this->parametros = $this->controller->parametros;
        $this->userdata = $this->controller->userdata;
    }

    /**
     * Valida o formulário de credenciais
     */
    public function validarFormCredenciais()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        // Captura os dados do formulário
        $this->idLote = isset($_POST["idLote"]) ? $_POST["idLote"] : null;
        $this->idEvento = isset($_POST["idEvento"]) ? $_POST["idEvento"] : null;
        $this->idPessoa = isset($_POST["idPessoa"]) ? $_POST["idPessoa"] : null;
        $this->docPessoa = isset($_POST["docPessoa"]) ? $_POST["docPessoa"] : null;
        $this->nomeCredencial = isset($_POST["nomeCredencial"]) ? $_POST["nomeCredencial"] : null;
        $this->codigoCredencial = isset($_POST["codigoCredencial"]) ? $_POST["codigoCredencial"] : null;
        $this->observacao = isset($_POST["observacao"]) ? $_POST["observacao"] : null;
        $this->detalhes = isset($_POST["detalhes"]) ? $_POST["detalhes"] : null;

        // Validações
        if (empty($this->idLote)) {
            $this->erro .= "<br>ID do lote não encontrado.";
        }
        if (empty($this->idEvento)) {
            $this->erro .= "<br>ID do evento não encontrado.";
        }
        if (empty($this->nomeCredencial)) {
            $this->erro .= "<br>Preencha o nome da credencial.";
        }
        if (empty($this->codigoCredencial)) {
            $this->erro .= "<br>Preencha o código da credencial.";
        }
        if (empty($this->docPessoa)) {
            $this->erro .= "<br>Preencha o documento da pessoa.";
        }

        // Prepara os dados para o formulário
        $this->form_data['idLote'] = $this->idLote;
        $this->form_data['idEvento'] = $this->idEvento;
        $this->form_data['idPessoa'] = $this->idPessoa;
        $this->form_data['docPessoa'] = $this->docPessoa;
        $this->form_data['nomeCredencial'] = $this->nomeCredencial;
        $this->form_data['codigoCredencial'] = $this->codigoCredencial;
        $this->form_data['observacao'] = $this->observacao;
        $this->form_data['detalhes'] = $this->detalhes;

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Os seguintes erros foram encontrados:</strong>' . $this->erro);
            return;
        }

        if (empty($this->form_data)) {
            return;
        }

        if (chk_array($this->parametros, 0) == 'editarCredencial') {
            $this->editarCredencial();
            return;
        } else {
            $this->adicionarCredencial();
            return;
        }
    }

    /**
     * Adiciona uma nova credencial
     */
    private function adicionarCredencial()
    {
        $createCredencialService = $this->getService('CredenciaisServices', 'CreateCredencial');
        $query = $createCredencialService->createCredencial($this->form_data);

        if (!$query) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Credencial cadastrada com sucesso.');
            $hash = encryptId($query);
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/credenciais/index/lotes/' . encryptId($this->form_data['idLote']) . '">';
            $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/credenciais/index/lotes/' . encryptId($this->form_data['idLote']) . '";</script>';

            $this->form_data = null;
            return;
        }
    }

    /**
     * Edita uma credencial existente
     */
    private function editarCredencial()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $updateCredencialService = $this->getService('CredenciaisServices', 'UpdateCredencial');
            $query = $updateCredencialService->updateCredencial($id, $this->form_data);

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Credencial editada com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/credenciais/index/lotes/' . encryptId($this->form_data['idLote']) . '">';
                $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/credenciais/index/lotes/' . encryptId($this->form_data['idLote']) . '";</script>';

                return;
            }
        }
    }

    /**
     * Obtém uma credencial específica
     */
    public function getCredencial($id = false)
    {
        if (empty($id)) {
            return;
        }

        $getCredencialService = $this->getService('CredenciaisServices', 'GetCredencial');
        $registro = $getCredencialService->getCredencial($id);

        if (empty($registro)) {
            $this->form_msg = $this->controller->Messages->error('Registro inexistente.');
            return;
        }

        foreach ($registro as $key => $value) {
            $this->form_data[$key] = $value;
        }

        return $registro;
    }

    public function getSetoresCredencial($idCredencial = false)
    {
        if (empty($idCredencial)) {
            return array();
        }

        $getSetoresCredencialService = $this->getService('CredenciaisServices', 'GetSetoresCredencial');
        $setores = $getSetoresCredencialService->getSetoresCredencial($idCredencial);

        return $setores;
    }

    public function getPeriodosCredencial($idCredencial = false)
    {
        if (empty($idCredencial)) {
            return array();
        }

        $getPeriodosCredencialService = $this->getService('CredenciaisServices', 'GetPeriodosCredencial');
        $periodos = $getPeriodosCredencialService->getPeriodosCredencial($idCredencial);

        return $periodos;
    }

    /**
     * Obtém todas as credenciais de um lote
     */
    public function getCredenciaisLote($idLote = false)
    {
        if (empty($idLote)) {
            return array();
        }

        $getCredenciaisLoteService = $this->getService('CredenciaisServices', 'GetCredenciaisLote');
        return $getCredenciaisLoteService->getCredenciaisLote($idLote);
    }

    /**
     * Bloqueia uma credencial
     */
    public function bloquearCredencial()
    {
        $id = null;

        if (chk_array($this->parametros, 3)) {
            $hash = chk_array($this->parametros, 3);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $bloquearCredencialService = $this->getService('CredenciaisServices', 'BloquearCredencial');
            $query = $bloquearCredencialService->bloquearCredencial($id);

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro ao bloquear o credencial.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Credencial bloqueado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/credenciais/index/lotes/' . chk_array($this->parametros, 1) . '">';
                return;
            }
        }
    }

    /**
     * Desbloqueia um lote
     */
    public function desbloquearCredencial()
    {
        $id = null;

        if (chk_array($this->parametros, 3)) {
            $hash = chk_array($this->parametros, 3);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $desbloquearCredencialService = $this->getService('CredenciaisServices', 'DesbloquearCredencial');
            $query = $desbloquearCredencialService->desbloquearCredencial($id);

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro ao desbloquear o credencial.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Credencial desbloqueado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/credenciais/index/lotes/' . chk_array($this->parametros, 1) . '">';
                return;
            }
        }
    }
    
    /**
     * Cria uma nova credencial
     */
    public function criarCredencial($dados)
    {
        try {
            // Busca informações do lote
            $queryLote = $this->db->query("SELECT * FROM tblLote WHERE id = ?", array($dados['idLote']));
            $lote = $queryLote->fetch(PDO::FETCH_ASSOC);
            
            if (!$lote) {
                return false;
            }
            
            // Gera código da credencial
            $codigo = $this->gerarCodigoCredencial($lote);
            
            // Dados da credencial
            $dadosCredencial = [
                'idLote' => $dados['idLote'],
                'idEvento' => $dados['idEvento'],
                'idPessoa' => $dados['idPessoa'],
                'nomeCredencial' => $lote['nomeLote'],
                'codigoCredencial' => $codigo,
                'observacao' => isset($dados['observacao']) ? $dados['observacao'] : '',
                'detalhes' => isset($dados['detalhes']) ? $dados['detalhes'] : '',
                'impresso' => 0,
                'status' => 'T',
                'dataCadastro' => date('Y-m-d H:i:s'),
                'idUsuarioCadastro' => $_SESSION['userdata']['id']
            ];
            
            $query = $this->db->insert('tblCredencial', $dadosCredencial);
            
            return $query;
            
        } catch (Exception $e) {
            Log::error('Erro ao criar credencial: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualiza uma credencial existente
     */
    public function atualizarCredencial($idPessoa, $idEvento, $dados)
    {
        try {
            // Primeiro desativa a credencial antiga
            $sql = "UPDATE tblCredencial SET status = 'F' WHERE idPessoa = ? AND idEvento = ? AND status = 'T'";
            $this->db->query($sql, array($idPessoa, $idEvento));
            
            // Cria nova credencial
            return $this->criarCredencial($dados);
            
        } catch (Exception $e) {
            Log::error('Erro ao atualizar credencial: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gera código da credencial baseado nas configurações do lote
     */
    private function gerarCodigoCredencial($lote)
    {
        // Se tem autonumeração
        if ($lote['autonumeracao']) {
            // Busca último número usado
            $sql = "SELECT MAX(CAST(codigoCredencial AS UNSIGNED)) as ultimo FROM tblCredencial WHERE idLote = ?";
            $query = $this->db->query($sql, array($lote['id']));
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            
            $proximo = ($resultado['ultimo'] ? $resultado['ultimo'] : 0) + 1;
            
            // Formata com zeros à esquerda se necessário
            if ($lote['qtdDigitos']) {
                return str_pad($proximo, $lote['qtdDigitos'], '0', STR_PAD_LEFT);
            }
            
            return $proximo;
        }
        
        // Gera código aleatório
        return strtoupper(uniqid());
    }

}
