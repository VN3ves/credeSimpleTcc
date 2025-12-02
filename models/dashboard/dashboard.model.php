<?php
class DashboardModel extends MainModel
{
	public $form_data;
	public $form_msg;
	public $db;

	private $erro;

	public function __construct($db = null, $controller = null)
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

			// Total de setores
			$querySetores = $this->db->query("SELECT COUNT(*) as total FROM tblSetor WHERE status = 'T'");
			$totalSetores = $querySetores->fetch()['total'];

			// Total de leitores
			$queryLeitores = $this->db->query("SELECT COUNT(*) as total FROM tblLeitor WHERE status = 'T'");
			$totalLeitores = $queryLeitores->fetch()['total'];

			// Estatísticas de entradas
			$queryEntradas = $this->db->query("
				SELECT 
					COUNT(*) as total,
					SUM(CASE WHEN permitida = 'T' THEN 1 ELSE 0 END) as permitidas,
					SUM(CASE WHEN permitida = 'F' THEN 1 ELSE 0 END) as negadas,
					SUM(CASE WHEN tipoEntrada = 'ENTRADA' THEN 1 ELSE 0 END) as entradas,
					SUM(CASE WHEN tipoEntrada = 'SAIDA' THEN 1 ELSE 0 END) as saidas
				FROM tblEntradas
			");
			$estatisticasEntradas = $queryEntradas->fetch(PDO::FETCH_ASSOC);

			// Entradas nas últimas 24 horas
			$queryEntradasHoje = $this->db->query("
				SELECT COUNT(*) as total 
				FROM tblEntradas 
				WHERE dataTentativa >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
			");
			$entradasHoje = $queryEntradasHoje->fetch()['total'];

			// Entradas por dia (últimos 7 dias)
			$queryEntradasPorDia = $this->db->query("
				SELECT 
					DATE(dataTentativa) as data,
					COUNT(*) as total,
					SUM(CASE WHEN permitida = 'T' THEN 1 ELSE 0 END) as permitidas,
					SUM(CASE WHEN permitida = 'F' THEN 1 ELSE 0 END) as negadas
				FROM tblEntradas
				WHERE dataTentativa >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
				GROUP BY DATE(dataTentativa)
				ORDER BY data ASC
			");
			$entradasPorDia = $queryEntradasPorDia->fetchAll(PDO::FETCH_ASSOC);

			// Entradas por hora (hoje)
			$queryEntradasPorHora = $this->db->query("
				SELECT 
					HOUR(dataTentativa) as hora,
					COUNT(*) as total
				FROM tblEntradas
				WHERE DATE(dataTentativa) = CURDATE()
				GROUP BY HOUR(dataTentativa)
				ORDER BY hora ASC
			");
			$entradasPorHora = $queryEntradasPorHora->fetchAll(PDO::FETCH_ASSOC);

			// Top 5 setores com mais entradas
			$queryTopSetores = $this->db->query("
				SELECT 
					s.nomeSetor,
					COUNT(*) as total
				FROM tblEntradas e
				INNER JOIN tblSetor s ON e.idSetor = s.id
				WHERE s.status = 'T'
				GROUP BY e.idSetor
				ORDER BY total DESC
				LIMIT 5
			");
			$topSetores = $queryTopSetores->fetchAll(PDO::FETCH_ASSOC);

			// Últimas 10 entradas
			$queryUltimasEntradas = $this->db->query("
				SELECT 
					e.id,
					e.dataTentativa,
					e.tipoEntrada,
					e.permitida,
					e.mensagem,
					p.nome AS nomePessoa,
					p.sobrenome AS sobrenomePessoa,
					s.nomeSetor AS nomeSetor,
					ev.nomeEvento
				FROM tblEntradas e
				LEFT JOIN tblPessoa p ON e.idPessoa = p.id
				LEFT JOIN tblSetor s ON e.idSetor = s.id
				LEFT JOIN tblEvento ev ON e.idEvento = ev.id
				ORDER BY e.dataTentativa DESC
				LIMIT 10
			");
			$ultimasEntradas = $queryUltimasEntradas->fetchAll(PDO::FETCH_ASSOC);

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
				'totalSetores' => $totalSetores,
				'totalLeitores' => $totalLeitores,
				'estatisticasEntradas' => $estatisticasEntradas,
				'entradasHoje' => $entradasHoje,
				'entradasPorDia' => $entradasPorDia,
				'entradasPorHora' => $entradasPorHora,
				'topSetores' => $topSetores,
				'ultimasEntradas' => $ultimasEntradas,
				'eventosAtivos' => $eventosAtivos
			];
		} catch (Exception $e) {
			Log::error('Erro ao buscar dados do dashboard: ' . $e->getMessage());
			return [
				'totalEventos' => 0,
				'totalCredenciais' => 0,
				'totalPessoas' => 0,
				'totalUsuarios' => 0,
				'totalSetores' => 0,
				'totalLeitores' => 0,
				'estatisticasEntradas' => [
					'total' => 0,
					'permitidas' => 0,
					'negadas' => 0,
					'entradas' => 0,
					'saidas' => 0
				],
				'entradasHoje' => 0,
				'entradasPorDia' => [],
				'entradasPorHora' => [],
				'topSetores' => [],
				'ultimasEntradas' => [],
				'eventosAtivos' => []
			];
		}
	}
}
