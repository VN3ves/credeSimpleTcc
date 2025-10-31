<?php
class MainModel
{
    public $form_data;
    public $form_msg;
    public $form_confirma;
    public $db;
    public $eventoDB;
    public $internalEventoDB;
    public $controller;
    public $parametros;
    public $userdata;
    public $services;

    public function __construct($db = null, $controller = null)
    {
        //$this->db = new DBmethods();
        $this->db = $db ?? DBInstance::getInstance(); // Usa a instância existente

        $this->parametros = $this->controller->parametros ?? array();
        $this->userdata = $this->controller->userdata ?? array();
        $this->getEventoDB();
    }

    /**
     * Gera um UUID v4 (aleatório) de forma nativa.
     *
     * @return string O UUID v4 gerado.
     * @throws Exception Se não for possível gerar bytes aleatórios.
     */
    public function generateUUIDv4(): string
    {
        try {
            // Gera 16 bytes aleatórios (128 bits)
            $bytes = random_bytes(16);

            // Define os bits da versão (4 para UUID v4)
            // O 6º byte é alterado para 0100 (binário) = 4 (decimal)
            $bytes[6] = chr(ord($bytes[6]) & 0x0F | 0x40);

            // Define os bits da variante (para garantir que seja uma variante RFC 4122)
            // O 8º byte é alterado para 10xx (binário)
            $bytes[8] = chr(ord($bytes[8]) & 0x3F | 0x80);

            // Formata os bytes em uma string hexadecimal com hífens
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
        } catch (Exception $e) {
            Log::error("Erro ao gerar UUID v4: " . $e->getMessage());
            return '';
        }
    }

    public function getEventoDB()
    {
        $this->eventoDB = $this->db;
        return $this->eventoDB;
    }

    public function inverteData($data = null)
    {
        $nova_data = null;

        if ($data) {
            $data = preg_split('/\-|\/|\s|:/', $data);

            $data = array_map('trim', $data);

            $nova_data .= chk_array($data, 2) . '-';
            $nova_data .= chk_array($data, 1) . '-';
            $nova_data .= chk_array($data, 0);

            if (chk_array($data, 3)) {
                $nova_data .= ' ' . chk_array($data, 3);
            }

            if (chk_array($data, 4)) {
                $nova_data .= ':' . chk_array($data, 4);
            }

            if (chk_array($data, 5)) {
                $nova_data .= ':' . chk_array($data, 5);
            }
        }

        return $nova_data;
    }

    public function loadModel($model_name = false)
    {
        if (!$model_name) return;

        $model_name = strtolower($model_name);

        $model_path = ABSPATH . '/models/' . $model_name . '.model.php';

        if (file_exists($model_path)) {
            require_once $model_path;

            $model_name = explode('/', $model_name);

            $model_name = end($model_name) . "model";

            $model_name = preg_replace('/[^a-zA-Z0-9]/is', '', $model_name);

            if (class_exists($model_name)) {
                return new $model_name($this->db, $this);
            }

            return;
        }
    }

    /**
     * Carrega dinamicamente um serviço com base no namespace.
     *
     * @param string $category Categoria do serviço (ex.: 'PessoasServices').
     * @param string $service Nome do serviço (ex.: 'CreatePessoa').
     * @return object Instância do serviço solicitado.
     * @throws Exception Caso o serviço não exista.
     */
    public function getService(string $category, string $service)
    {
        // Chave única para armazenar o serviço
        $key = "{$category}_{$service}";

        // Verifica se o serviço já foi instanciado
        if (!isset($this->services[$key])) {
            // Determina o caminho completo do namespace
            $className = "{$service}";

            $file = ABSPATH . '/services/' . $category . '/' . $service . '.php';
            if (!file_exists($file)) {
                Log::info('file_exists');
                $errorController = new ErrorController();
                $errorController->index();
                return;
            }
            require_once $file;
            // Verifica se a classe existe
            if (!class_exists($className)) {
                Log::info('class_exists');
                $errorController = new ErrorController();
                $errorController->index();
                return;
            }

            // Instancia e armazena o serviço
            $this->services[$key] = new $className();
        }

        return $this->services[$key];
    }

    public function getDefaultService(string $category, string $service)
    {
        // Chave única para armazenar o serviço
        $key = "{$category}_{$service}";
        // Verifica se o serviço já foi instanciado
        if (!isset($this->services[$key])) {
            // Determina o caminho completo do namespace
            $className = "{$service}";

            $file = ABSPATH . '/core/services/' . $category . '/' . $service . '.php';
            if (!file_exists($file)) {
                Log::info('file_exists');
                $errorController = new ErrorController();
                $errorController->index();
                return;
            }
            require_once $file;
            // Verifica se a classe existe
            if (!class_exists($className)) {
                Log::info('class_exists');
                $errorController = new ErrorController();
                $errorController->index();
                return;
            }

            // Instancia e armazena o serviço
            $this->services[$key] = new $className($this->db);
        }

        return $this->services[$key];
    }
}
