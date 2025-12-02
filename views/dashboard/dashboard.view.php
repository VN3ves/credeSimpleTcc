<?php if (!defined('ABSPATH')) exit;

// Dashboard completo com estatísticas detalhadas
$dadosDashboard = $modelo->getDadosDashboard();
$stats = $dadosDashboard['estatisticasEntradas'];
?>

<style>
.dashboard-stat-card {
    transition: transform 0.2s;
}
.dashboard-stat-card:hover {
    transform: translateY(-5px);
}
.chart-container {
    position: relative;
    height: 300px;
}
.activity-item {
    border-left: 3px solid #007bff;
    padding-left: 15px;
    margin-bottom: 15px;
}
.activity-item.success {
    border-left-color: #28a745;
}
.activity-item.danger {
    border-left-color: #dc3545;
}
</style>

<div class="content-wrapper">
    <!-- Cabeçalho de conteúdo -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-tachometer-alt"></i> Dashboard - Visão Geral do Sistema</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Início</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Conteúdo principal -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Linha 1: Cards Principais -->
            <div class="row">
                <!-- Total de Eventos -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info dashboard-stat-card">
                        <div class="inner">
                            <h3><?php echo number_format($dadosDashboard['totalEventos']); ?></h3>
                            <p>Eventos Ativos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/eventos" class="small-box-footer">
                            Ver todos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total de Credenciais -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success dashboard-stat-card">
                        <div class="inner">
                            <h3><?php echo number_format($dadosDashboard['totalCredenciais']); ?></h3>
                            <p>Credenciais Emitidas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-id-badge"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/credenciamento" class="small-box-footer">
                            Ver todas <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total de Pessoas -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning dashboard-stat-card">
                        <div class="inner">
                            <h3><?php echo number_format($dadosDashboard['totalPessoas']); ?></h3>
                            <p>Pessoas Cadastradas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/pessoas" class="small-box-footer">
                            Ver todas <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Entradas nas últimas 24h -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary dashboard-stat-card">
                        <div class="inner">
                            <h3><?php echo number_format($dadosDashboard['entradasHoje']); ?></h3>
                            <p>Entradas (24h)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/entradas" class="small-box-footer">
                            Ver todas <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Linha 2: Estatísticas de Entradas -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Entradas Permitidas</span>
                            <span class="info-box-number"><?php echo number_format($stats['permitidas']); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $stats['total'] > 0 ? round(($stats['permitidas'] / $stats['total']) * 100) : 0; ?>%"></div>
                            </div>
                            <span class="progress-description">
                                <?php echo $stats['total'] > 0 ? round(($stats['permitidas'] / $stats['total']) * 100) : 0; ?>% do total
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box bg-gradient-danger">
                        <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Entradas Negadas</span>
                            <span class="info-box-number"><?php echo number_format($stats['negadas']); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $stats['total'] > 0 ? round(($stats['negadas'] / $stats['total']) * 100) : 0; ?>%"></div>
                            </div>
                            <span class="progress-description">
                                <?php echo $stats['total'] > 0 ? round(($stats['negadas'] / $stats['total']) * 100) : 0; ?>% do total
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-sign-in-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Entradas</span>
                            <span class="info-box-number"><?php echo number_format($stats['entradas']); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                No sistema
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box bg-gradient-secondary">
                        <span class="info-box-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Saídas</span>
                            <span class="info-box-number"><?php echo number_format($stats['saidas']); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                Registradas
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Linha 3: Recursos do Sistema -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-purple dashboard-stat-card">
                        <div class="inner">
                            <h3><?php echo number_format($dadosDashboard['totalSetores']); ?></h3>
                            <p>Setores Ativos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/recursos/index/setores" class="small-box-footer">
                            Gerenciar <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-teal dashboard-stat-card">
                        <div class="inner">
                            <h3><?php echo number_format($dadosDashboard['totalLeitores']); ?></h3>
                            <p>Leitores Instalados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tablet-alt"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/recursos/index/leitores" class="small-box-footer">
                            Gerenciar <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger dashboard-stat-card">
                        <div class="inner">
                            <h3><?php echo number_format($dadosDashboard['totalUsuarios']); ?></h3>
                            <p>Usuários do Sistema</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/usuarios" class="small-box-footer">
                            Ver todos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-indigo dashboard-stat-card">
                        <div class="inner">
                            <h3><?php echo number_format($stats['total']); ?></h3>
                            <p>Total de Registros</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/entradas" class="small-box-footer">
                            Ver registros <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Linha 4: Gráficos -->
            <div class="row">
                <!-- Gráfico de Entradas por Dia -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title"><i class="fas fa-chart-line"></i> Entradas dos Últimos 7 Dias</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartEntradasPorDia"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico Pizza: Permitidas vs Negadas -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title"><i class="fas fa-chart-pie"></i> Status das Entradas</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartStatusEntradas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Linha 5: Top Setores e Últimas Entradas -->
            <div class="row">
                <!-- Top 5 Setores -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-trophy"></i> Top 5 Setores Mais Acessados</h3>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($dadosDashboard['topSetores'])): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($dadosDashboard['topSetores'] as $index => $setor): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <?php if ($index === 0): ?>
                                            <i class="fas fa-medal text-warning"></i>
                                        <?php elseif ($index === 1): ?>
                                            <i class="fas fa-medal" style="color: silver;"></i>
                                        <?php elseif ($index === 2): ?>
                                            <i class="fas fa-medal" style="color: #cd7f32;"></i>
                                        <?php else: ?>
                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                        <?php endif; ?>
                                        <?php echo htmlentities($setor['nome']); ?>
                                    </span>
                                    <span class="badge badge-primary badge-pill"><?php echo number_format($setor['total']); ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else: ?>
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Nenhum dado disponível</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Últimas Entradas -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-clock"></i> Últimas Entradas Registradas</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-primary" onclick="location.reload()">
                                    <i class="fas fa-sync-alt"></i> Atualizar
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            <?php if (!empty($dadosDashboard['ultimasEntradas'])): ?>
                                <?php foreach ($dadosDashboard['ultimasEntradas'] as $entrada): ?>
                                <div class="activity-item <?php echo $entrada['permitida'] == 'T' ? 'success' : 'danger'; ?>">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>
                                                <?php echo htmlentities($entrada['nomePessoa'] . ' ' . $entrada['sobrenomePessoa']); ?>
                                            </strong>
                                            <?php if ($entrada['permitida'] == 'T'): ?>
                                                <span class="badge badge-success ml-2">
                                                    <i class="fas fa-check"></i> Permitida
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-danger ml-2">
                                                    <i class="fas fa-times"></i> Negada
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($entrada['tipoEntrada'] == 'ENTRADA'): ?>
                                                <span class="badge badge-primary ml-1">
                                                    <i class="fas fa-sign-in-alt"></i> Entrada
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary ml-1">
                                                    <i class="fas fa-sign-out-alt"></i> Saída
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i:s', strtotime($entrada['dataTentativa'])); ?>
                                        </small>
                                    </div>
                                    <div class="mt-1">
                                        <small>
                                            <i class="fas fa-map-marker-alt"></i> 
                                            <?php echo htmlentities($entrada['nomeSetor'] ?? 'Sem setor'); ?>
                                            <?php if ($entrada['nomeEvento']): ?>
                                                | <i class="fas fa-calendar"></i> <?php echo htmlentities($entrada['nomeEvento']); ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <?php if ($entrada['mensagem']): ?>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> <?php echo htmlentities($entrada['mensagem']); ?>
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Nenhuma entrada registrada ainda</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Linha 6: Entradas por Hora (Hoje) -->
            <?php if (!empty($dadosDashboard['entradasPorHora'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-clock"></i> Distribuição de Entradas por Hora (Hoje)</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartEntradasPorHora"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Eventos em andamento -->
            <?php if (isset($dadosDashboard['eventosAtivos']) && count($dadosDashboard['eventosAtivos']) > 0): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-check"></i> Eventos em Andamento ou Próximos</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($dadosDashboard['eventosAtivos'] as $evento): ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-gradient-info elevation-1">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlentities($evento['nomeEvento']); ?>">
                                                <?php echo htmlentities($evento['nomeEvento']); ?>
                                            </span>
                                            <span class="info-box-number" style="font-size: 14px;">
                                                <i class="fas fa-calendar-day"></i> <?php echo date('d/m/Y', strtotime($evento['dataInicio'])); ?><br>
                                                <i class="fas fa-calendar-check"></i> <?php echo date('d/m/Y', strtotime($evento['dataFim'])); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Prepara dados para os gráficos
    
    // Gráfico de Entradas por Dia
    <?php 
    $diasLabels = [];
    $diasTotal = [];
    $diasPermitidas = [];
    $diasNegadas = [];
    
    foreach ($dadosDashboard['entradasPorDia'] as $dia) {
        $diasLabels[] = date('d/m', strtotime($dia['data']));
        $diasTotal[] = $dia['total'];
        $diasPermitidas[] = $dia['permitidas'];
        $diasNegadas[] = $dia['negadas'];
    }
    ?>
    
    var ctxDia = document.getElementById('chartEntradasPorDia');
    if (ctxDia) {
        var chartDia = new Chart(ctxDia.getContext('2d'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($diasLabels); ?>,
                datasets: [{
                    label: 'Permitidas',
                    data: <?php echo json_encode($diasPermitidas); ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true
                }, {
                    label: 'Negadas',
                    data: <?php echo json_encode($diasNegadas); ?>,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
    
    // Gráfico Pizza de Status
    var ctxStatus = document.getElementById('chartStatusEntradas');
    if (ctxStatus) {
        var chartStatus = new Chart(ctxStatus.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Permitidas', 'Negadas'],
                datasets: [{
                    data: [<?php echo $stats['permitidas']; ?>, <?php echo $stats['negadas']; ?>],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    // Gráfico de Entradas por Hora
    <?php 
    $horasLabels = [];
    $horasTotal = [];
    
    // Preenche todas as horas do dia
    for ($h = 0; $h < 24; $h++) {
        $horasLabels[] = sprintf('%02d:00', $h);
        $horasTotal[$h] = 0;
    }
    
    // Preenche com os dados reais
    foreach ($dadosDashboard['entradasPorHora'] as $hora) {
        $horasTotal[intval($hora['hora'])] = intval($hora['total']);
    }
    ?>
    
    var ctxHora = document.getElementById('chartEntradasPorHora');
    if (ctxHora) {
        var chartHora = new Chart(ctxHora.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($horasLabels); ?>,
                datasets: [{
                    label: 'Entradas',
                    data: <?php echo json_encode(array_values($horasTotal)); ?>,
                    backgroundColor: '#007bff',
                    borderColor: '#0056b3',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>