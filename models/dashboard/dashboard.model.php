<?php
class DashboardModel extends MainModel
{
	public $form_data;
	public $form_msg;
	public $db;

	private $erro;

	public function __construct($db = false, $controller = null)
	{
		$this->db = $db;
		$this->controller = $controller;
		$this->parametros = $this->controller->parametros;
		$this->userdata = $this->controller->userdata;
	}

	/**
	 * Retorna dados simplificados do dashboard
	 */
	public function getDadosDashboard()
	{
		try {
			// Total de eventos
			$queryEventos = $this->db->query("SELECT COUNT(*) as total FROM tblEvento WHERE status = 'T'");
			$totalEventos = $queryEventos->fetch()['total'];
			
			// Total de credenciais
			$queryCredenciais = $this->db->query("SELECT COUNT(*) as total FROM tblCredencial WHERE status = 'T'");
			$totalCredenciais = $queryCredenciais->fetch()['total'];
			
			// Total de pessoas
			$queryPessoas = $this->db->query("SELECT COUNT(*) as total FROM tblPessoa WHERE status = 'T'");
			$totalPessoas = $queryPessoas->fetch()['total'];
			
			// Total de usuários
			$queryUsuarios = $this->db->query("SELECT COUNT(*) as total FROM tblUsuario WHERE status = 'T'");
			$totalUsuarios = $queryUsuarios->fetch()['total'];
			
			// Eventos ativos (em andamento ou próximos)
			$queryEventosAtivos = $this->db->query("
				SELECT * FROM tblEvento 
				WHERE status = 'T' 
				AND dataFim >= CURDATE()
				ORDER BY dataInicio ASC
				LIMIT 6
			");
			$eventosAtivos = $queryEventosAtivos->fetchAll(PDO::FETCH_ASSOC);
			
			return [
				'totalEventos' => $totalEventos,
				'totalCredenciais' => $totalCredenciais,
				'totalPessoas' => $totalPessoas,
				'totalUsuarios' => $totalUsuarios,
				'eventosAtivos' => $eventosAtivos
			];
			
		} catch (Exception $e) {
			Log::error('Erro ao buscar dados do dashboard: ' . $e->getMessage());
			return [
				'totalEventos' => 0,
				'totalCredenciais' => 0,
				'totalPessoas' => 0,
				'totalUsuarios' => 0,
				'eventosAtivos' => []
			];
		}
	}
	
	/**
	 * Método antigo - mantido por compatibilidade mas retorna vazio
	 */
	public function getDadosDashboardParceiro($idEmpresa = 0, $ano = 0)
	{
		return [
			'paginasUsuario' => ['labels' => [], 'data' => []],
			'paginasMes' => ['labels' => [], 'data' => []],
			'paginasImpressora' => ['labels' => [], 'data' => []],
			'grayscale' => [0, 0],
			'duplex' => [0, 0],
			'documentos' => [],
			'anos' => [],
			'anoSelecionado' => date('Y')
		];
	}
	
	/**
	 * Método antigo - mantido vazio
	 */
	private function getOldDashboardData($idEmpresa = 0, $ano = 0)
	{

		$where = " WHERE tblImpressoes.idEmpresa = $idEmpresa ";

		// Primeiro busca os anos disponíveis
		$queryAnos = $this->db->query("
			SELECT DISTINCT YEAR(dataCadastro) as ano
			FROM tblImpressoes
			$where
			ORDER BY ano DESC
		");
		$anos = $queryAnos->fetchAll(PDO::FETCH_COLUMN);

		// Pega o primeiro ano da lista (mais recente)
		$anoSelecionado = $anos[0];

		if ($ano > 0) {
			$anoSelecionado = $ano;
		}

		$where .= " AND YEAR(dataCadastro) = " . $anoSelecionado;

		// Páginas por Usuário
		$queryPaginasUsuario = $this->db->query("
			SELECT 
				nomeUsuario as usuario,
				SUM(paginas) as total
			FROM tblImpressoes
			$where
			GROUP BY nomeUsuario
			ORDER BY total DESC
			LIMIT 15
		");
		$paginasUsuario = $queryPaginasUsuario->fetchAll(PDO::FETCH_ASSOC);

		// Páginas por Mês (últimos 12 meses)
		$queryPaginasMes = $this->db->query("
			SELECT 
				DATE_FORMAT(dataCadastro, '%b') as mes,
				SUM(paginas) as total
			FROM tblImpressoes
			$where
			AND dataCadastro >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
			GROUP BY MONTH(dataCadastro), YEAR(dataCadastro)
			ORDER BY YEAR(dataCadastro) DESC, MONTH(dataCadastro) DESC
			LIMIT 12
		");
		$paginasMes = $queryPaginasMes->fetchAll(PDO::FETCH_ASSOC);

		// Páginas por Impressora
		$queryPaginasImpressora = $this->db->query("
			SELECT 
				nomeImpressora as impressora,
				SUM(paginas) as total,
				COUNT(*) as documentos
			FROM tblImpressoes
			$where
			GROUP BY nomeImpressora
			ORDER BY total DESC
		");
		$paginasImpressora = $queryPaginasImpressora->fetchAll(PDO::FETCH_ASSOC);

		// Grayscale vs Colorido
		$queryGrayscale = $this->db->query("
			SELECT 
				monocromatico,
				COUNT(*) as total,
				SUM(paginas) as paginas
			FROM tblImpressoes
			$where
			GROUP BY monocromatico
		");
		$grayscale = $queryGrayscale->fetchAll(PDO::FETCH_ASSOC);

		// Duplex vs Simplex
		$queryDuplex = $this->db->query("
			SELECT 
				duplex,
				COUNT(*) as total,
				SUM(paginas) as paginas
			FROM tblImpressoes
			$where
			GROUP BY duplex
		");
		$duplex = $queryDuplex->fetchAll(PDO::FETCH_ASSOC);

		// Documentos Recentes
		$queryDocumentos = $this->db->query("
			SELECT 
				DATE_FORMAT(dataCadastro, '%d/%m/%Y %H:%i:%s') as data,
				nomeUsuario as usuario,
				nomeArquivo as documento,
				nomeImpressora as impressora,
				paginas,
				duplex,
				monocromatico,
				custoTotal
			FROM tblImpressoes
			$where
			ORDER BY dataCadastro DESC
			LIMIT 50
		");
		$documentos = $queryDocumentos->fetchAll(PDO::FETCH_ASSOC);

		// Calcula percentuais para gráficos com validação
		$totalPaginasImpressora = array_sum(array_column($paginasImpressora, 'total'));
		foreach ($paginasImpressora as &$impressora) {
			$impressora['percentual'] = $totalPaginasImpressora > 0 ?
				round(($impressora['total'] / $totalPaginasImpressora) * 100, 2) : 0;
		}

		// Processa dados de grayscale com validação
		$grayscaleData = [
			'GRAYSCALE' => array_sum(array_column(array_filter($grayscale, function ($item) {
				return $item['monocromatico'] == 'GRAYSCALE';
			}), 'paginas')),
			'NOT GRAYSCALE' => array_sum(array_column(array_filter($grayscale, function ($item) {
				return $item['monocromatico'] == 'NOT GRAYSCALE';
			}), 'paginas'))
		];

		$totalGrayscale = $grayscaleData['GRAYSCALE'] + $grayscaleData['NOT GRAYSCALE'];

		if ($totalGrayscale > 0) {
			$grayscalePercentual = [
				round(($grayscaleData['GRAYSCALE'] / $totalGrayscale) * 100, 2),
				round(($grayscaleData['NOT GRAYSCALE'] / $totalGrayscale) * 100, 2)
			];
		} else {
			$grayscalePercentual = [0, 0];
		}

		// Processa dados de duplex com validação
		$duplexData = [
			'DUPLEX' => array_sum(array_column(array_filter($duplex, function ($item) {
				return $item['duplex'] == 'DUPLEX';
			}), 'paginas')),
			'NOT DUPLEX' => array_sum(array_column(array_filter($duplex, function ($item) {
				return $item['duplex'] == 'NOT DUPLEX';
			}), 'paginas'))
		];

		$totalDuplex = $duplexData['DUPLEX'] + $duplexData['NOT DUPLEX'];

		if ($totalDuplex > 0) {
			$duplexPercentual = [
				round(($duplexData['DUPLEX'] / $totalDuplex) * 100, 2),
				round(($duplexData['NOT DUPLEX'] / $totalDuplex) * 100, 2)
			];
		} else {
			$duplexPercentual = [0, 0];
		}

		// Se não tiver dados para o ano, retorna arrays vazios
		if (empty($paginasUsuario)) {
			return [
				'paginasUsuario' => [
					'labels' => [],
					'data' => []
				],
				'paginasMes' => [
					'labels' => [],
					'data' => []
				],
				'paginasImpressora' => [
					'labels' => [],
					'data' => []
				],
				'grayscale' => [0, 0],
				'duplex' => [0, 0],
				'documentos' => [],
				'anos' => $anos,
				'anoSelecionado' => $anoSelecionado
			];
		}

		return [
			'paginasUsuario' => [
				'labels' => array_column($paginasUsuario, 'usuario'),
				'data' => array_column($paginasUsuario, 'total')
			],
			'paginasMes' => [
				'labels' => array_reverse(array_column($paginasMes, 'mes')),
				'data' => array_reverse(array_column($paginasMes, 'total'))
			],
			'paginasImpressora' => [
				'labels' => array_column($paginasImpressora, 'impressora'),
				'data' => array_column($paginasImpressora, 'percentual')
			],
			'grayscale' => $grayscalePercentual,
			'duplex' => $duplexPercentual,
			'documentos' => $documentos,
			'anos' => $anos,
			'anoSelecionado' => $anoSelecionado
		];
	}

	public function getAnosDisponiveis($idParceiro = 0)
	{
		$where = " WHERE 1=1 ";
		if ($idParceiro > 0) {
			$where .= " AND idParceiro = " . $idParceiro . " ";
		}

		$queryAnos = $this->db->query("
			SELECT DISTINCT YEAR(dataCadastro) as ano
			FROM tblImpressoes
			$where
			ORDER BY ano DESC
		");
		return $queryAnos->fetchAll(PDO::FETCH_COLUMN);
	}
}
