<?php
class EntradasModel extends MainModel
{
    /**
     * Busca registros de entrada com filtros
     */
    public function getEntradas($filtros = array())
    {
        try {
            $where = array('1=1');
            $params = array();
            
            // Filtro por evento
            if (!empty($filtros['idEvento'])) {
                $where[] = 'e.idEvento = ?';
                $params[] = $filtros['idEvento'];
            }
            
            // Filtro por tipo de entrada
            if (!empty($filtros['tipoEntrada'])) {
                $where[] = 'e.tipoEntrada = ?';
                $params[] = $filtros['tipoEntrada'];
            }
            
            // Filtro por permitida (sucesso/falha)
            if (isset($filtros['permitida']) && $filtros['permitida'] !== '') {
                $where[] = 'e.permitida = ?';
                $params[] = $filtros['permitida'];
            }
            
            // Filtro por data
            if (!empty($filtros['dataInicio'])) {
                $where[] = 'DATE(e.dataTentativa) >= ?';
                $params[] = $filtros['dataInicio'];
            }
            
            if (!empty($filtros['dataFim'])) {
                $where[] = 'DATE(e.dataTentativa) <= ?';
                $params[] = $filtros['dataFim'];
            }
            
            // Filtro por busca (nome, CPF, credencial)
            if (!empty($filtros['q'])) {
                $busca = '%' . $filtros['q'] . '%';
                $where[] = '(p.nome LIKE ? OR p.sobrenome LIKE ? OR d.documento LIKE ? OR e.credencial LIKE ?)';
                $params[] = $busca;
                $params[] = $busca;
                $params[] = $busca;
                $params[] = $busca;
            }
            
            $whereClause = implode(' AND ', $where);
            
            // Paginação
            $limit = '';
            if (!empty($filtros['limit'])) {
                $offset = !empty($filtros['offset']) ? intval($filtros['offset']) : 0;
                $limit = 'LIMIT ' . intval($filtros['limit']) . ' OFFSET ' . $offset;
            }
            
            $sql = "SELECT 
                        e.id,
                        e.idEvento,
                        e.idPessoa,
                        e.idCredencial,
                        e.idSetor,
                        e.credencial,
                        e.tipoEntrada,
                        e.dataTentativa,
                        e.permitida,
                        e.mensagem,
                        e.dataCadastro,
                        e.idArquivo,
                        a.pathLocal AS pathLocalArquivo,
                        ev.nomeEvento,
                        p.nome AS nomePessoa,
                        p.sobrenome AS sobrenomePessoa,
                        s.nomeSetor AS nomeSetor,
                        l.nomeLote AS nomeLote,
                        (SELECT documento FROM tblDocumento d WHERE d.idPessoa = e.idPessoa AND d.tipo = 'CPF' LIMIT 1) AS cpf
                    FROM tblEntradas e
                    LEFT JOIN tblEvento ev ON e.idEvento = ev.id
                    LEFT JOIN tblPessoa p ON e.idPessoa = p.id
                    LEFT JOIN tblSetor s ON e.idSetor = s.id
                    LEFT JOIN tblDocumento d ON d.idPessoa = e.idPessoa AND d.tipo = 'CPF'
                    LEFT JOIN tblArquivo a ON a.id = e.idArquivo
                    LEFT JOIN tblLote l ON l.id = e.idLote
                    WHERE {$whereClause}
                    ORDER BY e.dataTentativa DESC
                    {$limit}";
            
            $query = $this->db->query($sql, $params);
            
            if (!$query) {
                return array();
            }
            
            return $query->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            Log::error('Erro ao buscar entradas: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Conta total de registros (para paginação)
     */
    public function countEntradas($filtros = array())
    {
        try {
            $where = array('1=1');
            $params = array();
            
            // Mesmos filtros do getEntradas
            if (!empty($filtros['idEvento'])) {
                $where[] = 'e.idEvento = ?';
                $params[] = $filtros['idEvento'];
            }
            
            if (!empty($filtros['tipoEntrada'])) {
                $where[] = 'e.tipoEntrada = ?';
                $params[] = $filtros['tipoEntrada'];
            }
            
            if (isset($filtros['permitida']) && $filtros['permitida'] !== '') {
                $where[] = 'e.permitida = ?';
                $params[] = $filtros['permitida'];
            }
            
            if (!empty($filtros['dataInicio'])) {
                $where[] = 'DATE(e.dataTentativa) >= ?';
                $params[] = $filtros['dataInicio'];
            }
            
            if (!empty($filtros['dataFim'])) {
                $where[] = 'DATE(e.dataTentativa) <= ?';
                $params[] = $filtros['dataFim'];
            }
            
            if (!empty($filtros['q'])) {
                $busca = '%' . $filtros['q'] . '%';
                $where[] = '(p.nome LIKE ? OR p.sobrenome LIKE ? OR d.documento LIKE ? OR e.credencial LIKE ?)';
                $params[] = $busca;
                $params[] = $busca;
                $params[] = $busca;
                $params[] = $busca;
            }
            
            $whereClause = implode(' AND ', $where);
            
            $sql = "SELECT COUNT(*) as total
                    FROM tblEntradas e
                    LEFT JOIN tblPessoa p ON e.idPessoa = p.id
                    LEFT JOIN tblDocumento d ON d.idPessoa = e.idPessoa AND d.tipo = 'CPF'
                    WHERE {$whereClause}";
            
            $query = $this->db->query($sql, $params);
            
            if (!$query) {
                return 0;
            }
            
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return intval($result['total']);
            
        } catch (Exception $e) {
            Log::error('Erro ao contar entradas: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Registra uma tentativa de entrada
     */
    public function registrarEntrada($dados)
    {
        try {
            $insert = array(
                'idEvento' => isset($dados['idEvento']) ? $dados['idEvento'] : null,
                'idPessoa' => isset($dados['idPessoa']) ? $dados['idPessoa'] : null,
                'idCredencial' => isset($dados['idCredencial']) ? $dados['idCredencial'] : null,
                'idSetor' => isset($dados['idSetor']) ? $dados['idSetor'] : null,
                'idLeitor' => isset($dados['idLeitor']) ? $dados['idLeitor'] : null,
                'credencial' => isset($dados['credencial']) ? $dados['credencial'] : null,
                'tipoEntrada' => isset($dados['tipoEntrada']) ? $dados['tipoEntrada'] : 'ENTRADA',
                'permitida' => isset($dados['permitida']) ? $dados['permitida'] : 'F',
                'mensagem' => isset($dados['mensagem']) ? $dados['mensagem'] : null,
                'dataTentativa' => isset($dados['dataTentativa']) ? $dados['dataTentativa'] : date('Y-m-d H:i:s')
            );
            
            $result = $this->db->insert('tblEntradas', $insert);
            
            if ($result) {
                Log::info('Entrada registrada com sucesso - ID: ' . $result);
                return $result;
            }
            
            return false;
            
        } catch (Exception $e) {
            Log::error('Erro ao registrar entrada: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Busca estatísticas de entrada
     */
    public function getEstatisticas($idEvento = null)
    {
        try {
            $where = '1=1';
            $params = array();
            
            if ($idEvento) {
                $where = 'idEvento = ?';
                $params[] = $idEvento;
            }
            
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN permitida = 'T' THEN 1 ELSE 0 END) as permitidas,
                        SUM(CASE WHEN permitida = 'F' THEN 1 ELSE 0 END) as negadas,
                        SUM(CASE WHEN tipoEntrada = 'ENTRADA' THEN 1 ELSE 0 END) as entradas,
                        SUM(CASE WHEN tipoEntrada = 'SAIDA' THEN 1 ELSE 0 END) as saidas
                    FROM tblEntradas
                    WHERE {$where}";
            
            $query = $this->db->query($sql, $params);
            
            if (!$query) {
                return array(
                    'total' => 0,
                    'permitidas' => 0,
                    'negadas' => 0,
                    'entradas' => 0,
                    'saidas' => 0
                );
            }
            
            return $query->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            Log::error('Erro ao buscar estatísticas: ' . $e->getMessage());
            return array(
                'total' => 0,
                'permitidas' => 0,
                'negadas' => 0,
                'entradas' => 0,
                'saidas' => 0
            );
        }
    }
}

