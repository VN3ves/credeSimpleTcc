<?php
class UploadModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $db;
    private $uploadHandler;
    private $erro;

    public function __construct($db = false, $controller = null)
    {
        $this->db = $db;

        $this->controller = $controller;

        $this->parametros = $this->controller->parametros;

        $this->userdata = $this->controller->userdata;
    }

    public function avatar($tipo = null, $diretorio = null)
    {
        if (!$tipo || !$diretorio) {
            return ['success' => false, 'error' => 'Parâmetros inválidos'];
        }

        // Garantir que os diretórios existam
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }
        if (!is_dir($diretorio . '/thumb')) {
            mkdir($diretorio . '/thumb', 0777, true);
        }

        $options = [
            'upload_dir' => $diretorio . '/',
            'upload_url' => str_replace(ABSPATH, HOME_URI, $diretorio) . '/',
            'param_name' => 'midia',
            'image_versions' => [
                // Versão original
                '' => [
                    'auto_orient' => true,
                    'max_width' => 1920,
                    'max_height' => 1080,
                    'jpeg_quality' => 95
                ],
                // Versão thumbnail
                'thumbnail' => [
                    'crop' => false,
                    'max_width' => 150,
                    'max_height' => 150,
                    'jpeg_quality' => 80,
                    'upload_dir' => $diretorio . '/thumb/',
                    'upload_url' => str_replace(ABSPATH, HOME_URI, $diretorio) . '/thumb/'
                ]
            ],
            'accept_file_types' => '/\.(gif|jpe?g|png)$/i',
            'max_file_size' => 5 * 1024 * 1024, // 5MB
            'image_library' => 0, // 0 = GD, 1 = Imagick
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            'print_response' => true,
            'mkdir_mode' => 0777,
            'orient_image' => true
        ];

        try {
            // Verificar permissões
            if (!is_writable($diretorio)) {
                Log::error("Diretório não tem permissão de escrita: " . $diretorio);
                return ['success' => false, 'error' => "Diretório não tem permissão de escrita: " . $diretorio];
            }

            if (!is_writable($diretorio . '/thumb')) {
                Log::error("Diretório thumb não tem permissão de escrita: " . $diretorio . '/thumb');
                return ['success' => false, 'error' => "Diretório thumb não tem permissão de escrita: " . $diretorio . '/thumb'];
            }

            // Verificar extensão GD
            if (!extension_loaded('gd')) {
                Log::error("Extensão GD não está carregada. Extensão necessária para o redimensionamento de imagens.");
                return ['success' => false, 'error' => "Extensão GD não está carregada. Extensão necessária para o redimensionamento de imagens."];
            }

            $this->uploadHandler = new UploadHandler($options);
            $response = $this->uploadHandler->get_response();

            // Se a resposta já for uma string JSON, decodificar
            if (is_string($response)) {
                $response = json_decode($response, true);
            } 
            // Se for objeto, converter para array
            else if (is_object($response)) {
                $response = json_decode(json_encode($response), true);
            }
            
            // Ajuste para tratar o formato correto da resposta
            if (isset($response['midia']) && !empty($response['midia'])) {
                $file = is_array($response['midia'][0]) ? $response['midia'][0] : json_decode(json_encode($response['midia'][0]), true);
                
                if (!isset($file['error'])) {
                    return [
                        'success' => true,
                        'path' => str_replace(ABSPATH, '', $diretorio . '/' . $file['name'])
                    ];
                } else {
                    Log::error("Erro no upload do arquivo: " . json_encode($file['error']));
                    return [
                        'success' => false,
                        'error' => $file['error']
                    ];
                }
            }

            Log::error("Resposta do upload inválida: " . json_encode($response));
            return [
                'success' => false,
                'error' => 'Resposta do upload inválida'
            ];

        } catch (Exception $e) {
            Log::error("Erro no upload: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function diversos($tipo = null, $diretorio = null)
    {
        if (!$tipo || !$diretorio) {
            return ['success' => false, 'error' => 'Parâmetros inválidos'];
        }

        $options = [
            'upload_dir' => $diretorio . '/',
            'upload_url' => HOME_URI . str_replace(ABSPATH, '', $diretorio) . '/',
            'accept_file_types' => '/\.(gif|jpe?g|png|pdf|doc|docx|xls|xlsx)$/i',
            'max_file_size' => 10 * 1024 * 1024, // 10MB
            'param_name' => 'midia',
            'image_versions' => [], // Sem versões de imagem para diversos
            'print_response' => false // Alterado para false para controlar a resposta
        ];

        try {
            $this->uploadHandler = new UploadHandler($options);
            $response = $this->uploadHandler->get_response();

            // Se a resposta já for uma string JSON, decodificar
            if (is_string($response)) {
                $response = json_decode($response, true);
            } 
            // Se for objeto, converter para array
            else if (is_object($response)) {
                $response = json_decode(json_encode($response), true);
            }

            // Ajuste para tratar o formato correto da resposta
            if (isset($response['midia']) && !empty($response['midia'])) {
                $file = is_array($response['midia'][0]) ? $response['midia'][0] : json_decode(json_encode($response['midia'][0]), true);
                
                if (!isset($file['error'])) {
                    return [
                        'success' => true,
                        'path' => str_replace(ABSPATH, '', $diretorio . '/' . $file['name'])
                    ];
                } else {
                    Log::error("Erro no upload do arquivo: " . json_encode($file['error']));
                    return [
                        'success' => false,
                        'error' => $file['error']
                    ];
                }
            }

            Log::error("Resposta do upload inválida: " . json_encode($response));
            return [
                'success' => false,
                'error' => 'Resposta do upload inválida'
            ];

        } catch (Exception $e) {
            Log::error("Erro no upload: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
