<?php
require_once ABSPATH . '/core/utils/global-functions.php';
class GetEventos extends MainModel
{

    public function getEventos($filtros = null)
    {
        $where = array();
        
        // Validação de permissão
        $permissao = isset($_SESSION['userdata']['idPermissao']) ? $_SESSION['userdata']['idPermissao'] : null;
        
        // Se for ADMIN de evento, filtra apenas eventos que administra
        if ($permissao == 2 && isset($_SESSION['userdata']['idEvento'])) {
            $where[] = "e.id = " . $_SESSION['userdata']['idEvento'];
        }
        // SUPERADMIN e USUARIO vêem todos os eventos

        // Resto dos filtros
        if (!empty($filtros["dataInicioFim"])) {
            $datas = explode(" - ", $filtros["dataInicioFim"]);
            $dataInicio = implode("-", array_reverse(explode("/", $datas[0])));
            $dataFim = implode("-", array_reverse(explode("/", $datas[1])));

            if ($dataInicio == $dataFim) {
                $where[] = "DATE(e.dataInicio) = '" . $dataInicio . "'";
            } else {
                $where[] = "(DATE(e.dataInicio) BETWEEN '" . $dataInicio . "' AND '" . $dataFim . "')";
            }
        }

        if (!empty($filtros["categoria"])) {
            $where[] = "e.idCategoria = " . $filtros['categoria'];
        }

        if (!empty($filtros["q"])) {
            $busca = _otimizaBusca($filtros['q']);
            $where[] = "(e.nomeEvento LIKE '%" . $busca . "%' OR 
                      e.nomeLocal LIKE '%" . $busca . "%' OR 
                      e.nomeCategoria LIKE '%" . $busca . "%')";
        }

        // Monta a query
        $whereStr = !empty($where) ? " WHERE " . implode(" AND ", $where) : "";
        
        // Conta total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM vwEventos e" . $whereStr;
        $queryCount = $this->db->query($sqlCount);
        $result = $queryCount->fetch();
        $total = $result ? $result['total'] : 0;
       

        // Paginação
        $page = isset($filtros['page']) ? (int)$filtros['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Query principal com LIMIT
        $sql = "SELECT e.*
                FROM vwEventos e" . 
                $whereStr . " 
                ORDER BY e.dataInicio DESC 
                LIMIT " . $offset . ", " . $limit;

        $query = $this->db->query($sql);
        
        return array(
            'eventos' => $query->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        );
    }
}
