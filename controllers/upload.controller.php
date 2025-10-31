<?php
class UploadController extends MainController
{

    private $modeloUpload;
    private $idElemento = null; // ID do elemento que será feito o upload
    public $db;

    // Método para gereciar o upload para pessoas
    public function pessoas()
    {
        $hash = chk_array($this->parametros, 1);
        $this->idElemento = decryptHash($hash);

        if ($this->idElemento == null) {
            return json_encode(['error' => 'ID inválido']);
        }

        $this->modeloUpload = $this->load_model('utils/upload');

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        // Criar diretório base usando apenas o ID
        $baseDir = UP_ABSPATH . '/pessoas/' . $this->idElemento;

        if (chk_array($parametros, 0) == 'avatar') {
            $uploadDir = $baseDir . '/imagens/avatar';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $resultado = $this->modeloUpload->avatar('pessoa', $uploadDir);

            // Se o upload foi bem sucedido, registrar na tabela
            if ($resultado['success']) {
                $this->db->insert('tblArquivo', array(
                    'idReferencia' => $this->idElemento,
                    'tipoReferencia' => 'PESSOA',
                    'pathLocal' => $resultado['path'],
                    'tipoArquivo' => 'AVATAR',
                    'observacao' => 'Upload de avatar'
                ));
            } else {
                Log::error("Erro no upload de avatar: " . json_encode($resultado));
                return json_encode(['error' => $resultado]);
            }
        } else {
            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0777, true);
            }
            $resultado = $this->modeloUpload->diversos('pessoa', $baseDir);

            if ($resultado['success']) {
                $this->db->insert('tblArquivo', array(
                    'idReferencia' => $this->idElemento,
                    'tipoReferencia' => 'PESSOA',
                    'pathLocal' => $resultado['path'],
                    'tipoArquivo' => 'DOCUMENTO',
                    'observacao' => 'Upload de documento'
                ));
            } else {
                Log::error("Erro no upload de documento: " . json_encode($resultado));
                return json_encode(['error' => $resultado]);
            }
        }
    }

    // Método para gereciar o upload para empresas
    public function empresas()
    {
        $hash = chk_array($this->parametros, 1);
        $this->idElemento = decryptHash($hash);

        if ($this->idElemento == null) {
            return json_encode(['error' => 'ID inválido']);
        }

        $this->modeloUpload = $this->load_model('utils/upload');

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        // Criar diretório base usando apenas o ID
        $baseDir = UP_ABSPATH . '/empresas/' . $this->idElemento;

        if (chk_array($parametros, 0) == 'avatar') {
            $uploadDir = $baseDir . '/imagens/avatar';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $resultado = $this->modeloUpload->avatar('empresa', $uploadDir);

            if ($resultado['success']) {
                $insert = $this->db->insert('tblArquivo', array(
                    'idReferencia' => $this->idElemento,
                    'tipoReferencia' => 'EMPRESA',
                    'pathLocal' => $resultado['path'],
                    'tipoArquivo' => 'AVATAR',
                    'observacao' => 'Upload de avatar'
                ));

                if (!$insert) {
                    Log::error("Erro ao inserir registro na tblArquivo: " . json_encode($this->db->error()));
                    return json_encode(['error' => 'Erro ao salvar registro do arquivo']);
                }
            } else {
                Log::error("Erro no upload de avatar: " . json_encode($resultado));
                return json_encode(['error' => $resultado]);
            }
        } else {
            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0777, true);
            }
            $resultado = $this->modeloUpload->diversos('empresa', $baseDir);

            if ($resultado['success']) {
                $this->db->insert('tblArquivo', array(
                    'idReferencia' => $this->idElemento,
                    'tipoReferencia' => 'EMPRESA',
                    'pathLocal' => $resultado['path'],
                    'tipoArquivo' => 'DOCUMENTO',
                    'observacao' => 'Upload de documento'
                ));
            } else {
                return json_encode(['error' => $resultado]);
            }
        }
    }

    public function eventos()
    {
        $hash = chk_array($this->parametros, 1);
        $this->idElemento = decryptHash($hash);

        if ($this->idElemento == null) {
            return json_encode(['error' => 'ID inválido']);
        }

        $this->modeloUpload = $this->load_model('utils/upload');

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        // Criar diretório base usando apenas o ID
        $baseDir = UP_ABSPATH . '/eventos/' . $this->idElemento;

        Log::error('error' . 'Base dir: ' . $baseDir);

        if (chk_array($parametros, 0) == 'logo') {
            $uploadDir = $baseDir . '/imagens/logo';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $resultado = $this->modeloUpload->avatar('evento', $uploadDir);

            if ($resultado['success']) {
                $this->db->insert('tblArquivo', array(
                    'idReferencia' => $this->idElemento,
                    'tipoReferencia' => 'EVENTO',
                    'pathLocal' => $resultado['path'],
                    'tipoArquivo' => 'LOGO',
                    'observacao' => 'Upload de logo'
                ));
            } else {
                Log::error("Erro no upload de logo: " . json_encode($resultado));
                return json_encode(['error' => $resultado]);
            }
        } else if (chk_array($parametros, 0) == 'banner') {
            $uploadDir = $baseDir . '/imagens/banner';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $resultado = $this->modeloUpload->avatar('evento', $uploadDir);

            if ($resultado['success']) {
                $this->db->insert('tblArquivo', array(
                    'idReferencia' => $this->idElemento,
                    'tipoReferencia' => 'EVENTO',
                    'pathLocal' => $resultado['path'],
                    'tipoArquivo' => 'BANNER',
                    'observacao' => 'Upload de banner'
                ));
            } else {
                Log::error("Erro no upload de banner: " . json_encode($resultado));
                return json_encode(['error' => $resultado]);
            }
        }
    }
}
