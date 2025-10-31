<?php if (!defined('ABSPATH')) exit;

// Dashboard simplificado - sem empresas
$dadosDashboard = $modelo->getDadosDashboard();
?>

<div class="content-wrapper">
    <!-- Cabeçalho de conteúdo -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
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
            <!-- Cards de estatísticas -->
            <div class="row">
                <!-- Total de Eventos -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo isset($dadosDashboard['totalEventos']) ? $dadosDashboard['totalEventos'] : 0; ?></h3>
                            <p>Total de Eventos</p>
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
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo isset($dadosDashboard['totalCredenciais']) ? $dadosDashboard['totalCredenciais'] : 0; ?></h3>
                            <p>Total de Credenciais</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-id-badge"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/credenciais" class="small-box-footer">
                            Ver todos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total de Pessoas -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo isset($dadosDashboard['totalPessoas']) ? $dadosDashboard['totalPessoas'] : 0; ?></h3>
                            <p>Total de Pessoas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/pessoas" class="small-box-footer">
                            Ver todos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total de Usuários -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo isset($dadosDashboard['totalUsuarios']) ? $dadosDashboard['totalUsuarios'] : 0; ?></h3>
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
            </div>

            <!-- Eventos em andamento -->
            <?php if (isset($dadosDashboard['eventosAtivos']) && count($dadosDashboard['eventosAtivos']) > 0): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-check"></i> Eventos em Andamento</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($dadosDashboard['eventosAtivos'] as $evento): ?>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo $evento['nomeEvento']; ?></span>
                                            <span class="info-box-number">
                                                <?php echo date('d/m/Y', strtotime($evento['dataInicio'])); ?> - 
                                                <?php echo date('d/m/Y', strtotime($evento['dataFim'])); ?>
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
        // Dashboard pronto
    });
</script>