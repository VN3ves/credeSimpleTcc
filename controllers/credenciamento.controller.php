<?php
class CredenciamentoController extends MainController
{
    public $login_required = true;

    public function index()
    {
        $this->title = SYS_NAME . ' - Credenciamento';

        if (!$this->logged_in) {
            $this->logout();

            $this->goto_login();

            return;
        }

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        $modeloEvento = $this->load_model('eventos/eventos');
        $modeloCredenciais = $this->load_model('credenciais/credenciais');
        $modeloLotes = $this->load_model('credenciais/lotes');
        $modeloSetores = $this->load_model('recursos/setores');

        $modeloCredenciamento = $this->load_model('credenciamento/credenciamento');

        if (chk_array($this->parametros, 0) == 'sas') {
        } else {
            $conteudo = ABSPATH . '/views/credenciamento/credenciamento.view.php';
        }


        require ABSPATH . '/views/painel/painel.view.php';
    }

    public function buscaDoc()
    {
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        $doc = chk_array($this->parametros, 0);
        $hashEvento = chk_array($this->parametros, 1);
        $modeloCredenciamento = $this->load_model('credenciamento/credenciamento');

        $idEvento = decryptHash($hashEvento);
        
        $resultado = $modeloCredenciamento->buscaDoc($doc, $idEvento);

        echo json_encode($resultado);
    }
    
    public function cadastrarPessoa()
    {
        // Define que é uma requisição AJAX
        header('Content-Type: application/json');
        
        // Valida se é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        // Pega os dados do POST
        $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
        $sobrenome = isset($_POST['sobrenome']) ? trim($_POST['sobrenome']) : '';
        $cpf = isset($_POST['cpf']) ? preg_replace('/[^0-9]/', '', $_POST['cpf']) : '';
        $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        
        // Validações básicas
        if (empty($nome) || empty($sobrenome) || empty($cpf)) {
            echo json_encode(['success' => false, 'message' => 'Nome, sobrenome e CPF são obrigatórios']);
            return;
        }
        
        // Carrega o modelo de pessoas
        $modeloPessoas = $this->loadDefaultModel('PessoasModel');
        
        // Cria o array de dados da pessoa
        $dadosPessoa = [
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'cpf' => $cpf,
            'telefone' => $telefone,
            'email' => $email,
            'status' => 'T' // Ativo
        ];
        
        // Tenta cadastrar a pessoa
        $resultado = $modeloPessoas->cadastrarPessoa($dadosPessoa);
        
        if ($resultado && isset($resultado['id'])) {
            echo json_encode([
                'success' => true,
                'message' => 'Participante cadastrado com sucesso',
                'pessoa' => [
                    'id' => $resultado['id'],
                    'nome' => $nome,
                    'sobrenome' => $sobrenome
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar participante']);
        }
    }
    
    public function lotesDisponiveis()
    {
        // Define que é uma requisição AJAX
        header('Content-Type: application/json');
        
        // Carrega o modelo de lotes
        $modeloLotes = $this->load_model('credenciais/lotes');
        
        // Busca lotes disponíveis para o evento atual
        $idEvento = isset($_SESSION['idEventoHash']) ? decryptHash($_SESSION['idEventoHash']) : null;
        
        if (!$idEvento) {
            echo json_encode([]);
            return;
        }
        
        // Busca os lotes
        $lotes = $modeloLotes->getLotesDisponiveis($idEvento);
        
        echo json_encode($lotes);
    }
    
    public function salvar()
    {
        // Define que é uma requisição AJAX
        header('Content-Type: application/json');
        
        // Valida se é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        // Pega os dados do POST
        $participante_id = isset($_POST['participante_id']) ? intval($_POST['participante_id']) : 0;
        $lote_id = isset($_POST['lote_id']) ? intval($_POST['lote_id']) : 0;
        $foto = isset($_POST['foto']) ? $_POST['foto'] : '';
        $action_type = isset($_POST['action_type']) ? $_POST['action_type'] : 'new';
        
        // Validações
        if (!$participante_id || !$lote_id) {
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            return;
        }
        
        // Carrega os modelos necessários
        $modeloCredenciais = $this->load_model('credenciais/credenciais');
        $idEvento = isset($_SESSION['idEventoHash']) ? decryptHash($_SESSION['idEventoHash']) : null;
        
        if (!$idEvento) {
            echo json_encode(['success' => false, 'message' => 'Evento não selecionado']);
            return;
        }
        
        // Se tem foto, processa o upload
        $fotoPath = '';
        if (!empty($foto)) {
            // Remove o prefixo data:image/jpeg;base64,
            $foto = str_replace('data:image/jpeg;base64,', '', $foto);
            $foto = str_replace('data:image/png;base64,', '', $foto);
            $foto = str_replace(' ', '+', $foto);
            
            // Decodifica
            $data = base64_decode($foto);
            
            // Define o caminho do arquivo (mesma estrutura do upload normal)
            $uploadDir = ABSPATH . '/midia/pessoas/' . $participante_id . '/imagens/avatar/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = 'avatar_' . time() . '.jpg';
            $filePath = $uploadDir . $fileName;
            
            // Salva o arquivo
            if (file_put_contents($filePath, $data)) {
                $fotoPath = '/midia/pessoas/' . $participante_id . '/imagens/avatar/' . $fileName;
                
                // IMPORTANTE: Registra na tabela tblArquivo (igual ao upload normal)
                $this->db->insert('tblArquivo', array(
                    'idReferencia' => $participante_id,
                    'tipoReferencia' => 'PESSOA',
                    'pathLocal' => $fotoPath,
                    'tipoArquivo' => 'AVATAR',
                    'observacao' => 'Upload via credenciamento'
                ));
                
                Log::info("Foto de credenciamento salva: $fotoPath para pessoa ID: $participante_id");
            } else {
                Log::error("Erro ao salvar foto de credenciamento para pessoa ID: $participante_id");
            }
        }
        
        // Prepara os dados da credencial
        $dadosCredencial = [
            'idPessoa' => $participante_id,
            'idEvento' => $idEvento,
            'idLote' => $lote_id,
            'foto' => $fotoPath,
            'status' => 'T' // Ativo
        ];
        
        // Salva ou atualiza a credencial
        if ($action_type === 'change') {
            // Atualiza credencial existente
            $resultado = $modeloCredenciais->atualizarCredencial($participante_id, $idEvento, $dadosCredencial);
        } else {
            // Cria nova credencial
            $resultado = $modeloCredenciais->criarCredencial($dadosCredencial);
        }
        
        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => $action_type === 'change' ? 'Credencial trocada com sucesso' : 'Credenciamento realizado com sucesso'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar credenciamento']);
        }
    }
}
