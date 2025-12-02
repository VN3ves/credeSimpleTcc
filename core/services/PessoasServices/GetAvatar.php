<?php

class GetAvatar
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAvatar($id = null, $tn = false)
    {
        try {

            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                return "midia/noPictureProfile.png";
            }
            // Buscar o avatar mais recente na tabela de arquivos
            $query = $this->db->query(
                "SELECT pathLocal 
				 FROM tblArquivo 
				 WHERE idReferencia = ? 
				 AND tipoReferencia = 'PESSOA' 
				 AND tipoArquivo = 'AVATAR'
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
                $pathThumb = str_replace('/avatar/', '/avatar/thumb/', $path);
                if (file_exists(ABSPATH . $pathThumb)) {
                    return $pathThumb;
                }
            }

            if (!file_exists(ABSPATH . $path)) {
                return "midia/noPictureProfile.png";
            }

            return $path;
        } catch (Exception $e) {
            error_log("Erro ao buscar avatar: " . $e->getMessage());
            return "midia/noPictureProfile.png";
        }
    }
}
