<!-- Registros de Entrada -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-door-open"></i>
                        Registros de Entrada
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Entradas</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Estatísticas -->
            <?php
            // Pega o evento da sessão
            $idEvento = isset($_SESSION['idEventoHash']) ? decryptHash($_SESSION['idEventoHash']) : null;
            $stats = $modeloEntradas->getEstatisticas($idEvento);
            ?>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= number_format($stats['total']) ?></h3>
                            <p>Total de Registros</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= number_format($stats['permitidas']) ?></h3>
                            <p>Entradas Permitidas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= number_format($stats['negadas']) ?></h3>
                            <p>Entradas Negadas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= number_format($stats['entradas']) ?></h3>
                            <p>Entradas no Evento</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter"></i>
                        Filtros
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formFiltros" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo</label>
                                    <select class="form-control" name="tipoEntrada">
                                        <option value="">Todos</option>
                                        <option value="ENTRADA" <?= (isset($_GET['tipoEntrada']) && $_GET['tipoEntrada'] == 'ENTRADA') ? 'selected' : '' ?>>Entrada</option>
                                        <option value="SAIDA" <?= (isset($_GET['tipoEntrada']) && $_GET['tipoEntrada'] == 'SAIDA') ? 'selected' : '' ?>>Saída</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="permitida">
                                        <option value="">Todos</option>
                                        <option value="T" <?= (isset($_GET['permitida']) && $_GET['permitida'] == 'T') ? 'selected' : '' ?>>Permitida</option>
                                        <option value="F" <?= (isset($_GET['permitida']) && $_GET['permitida'] == 'F') ? 'selected' : '' ?>>Negada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Data Início</label>
                                    <input type="date" class="form-control" name="dataInicio" value="<?= isset($_GET['dataInicio']) ? $_GET['dataInicio'] : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Data Fim</label>
                                    <input type="date" class="form-control" name="dataFim" value="<?= isset($_GET['dataFim']) ? $_GET['dataFim'] : '' ?>">
                                </div>
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Buscar</label>
                                    <input type="text" class="form-control" name="q" placeholder="Buscar por nome, CPF ou código de credencial..." value="<?= isset($_GET['q']) ? htmlentities($_GET['q']) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <a href="<?php echo HOME_URI; ?>/entradas" class="btn btn-default btn-block">
                                        <i class="fas fa-eraser"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Registros -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i>
                        Listagem de Entradas
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-success" onclick="exportarDados()">
                            <i class="fas fa-file-excel"></i> Exportar
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php
                    // Busca os registros com os filtros (evento vem da sessão)
                    $filtros = array(
                        'idEvento' => $idEvento, // Pega da sessão
                        'tipoEntrada' => isset($_GET['tipoEntrada']) ? $_GET['tipoEntrada'] : null,
                        'permitida' => isset($_GET['permitida']) ? $_GET['permitida'] : null,
                        'dataInicio' => isset($_GET['dataInicio']) ? $_GET['dataInicio'] : null,
                        'dataFim' => isset($_GET['dataFim']) ? $_GET['dataFim'] : null,
                        'q' => isset($_GET['q']) ? $_GET['q'] : null,
                        'limit' => 100 // Limita para performance
                    );
                    
                    $entradas = $modeloEntradas->getEntradas($filtros);
                    $total = $modeloEntradas->countEntradas($filtros);
                    ?>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Data/Hora</th>
                                    <th>Evento</th>
                                    <th>Participante</th>
                                    <th>CPF</th>
                                    <th>Credencial</th>
                                    <th>Setor</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Mensagem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($entradas)): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Nenhum registro encontrado</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($entradas as $entrada): ?>
                                        <tr>
                                            <td><?= $entrada['id'] ?></td>
                                            <td>
                                                <small>
                                                    <?= date('d/m/Y', strtotime($entrada['dataTentativa'])) ?><br>
                                                    <strong><?= date('H:i:s', strtotime($entrada['dataTentativa'])) ?></strong>
                                                </small>
                                            </td>
                                            <td><?= htmlentities($entrada['nomeEvento'] ?? '') ?></td>
                                            <td><?= htmlentities($entrada['nomePessoa'] . ' ' . $entrada['sobrenomePessoa']) ?></td>
                                            <td><?= htmlentities($entrada['cpf'] ?? '-') ?></td>
                                            <td><code><?= htmlentities($entrada['credencial'] ?? '-') ?></code></td>
                                            <td><?= htmlentities($entrada['nomeSetor'] ?? '-') ?></td>
                                            <td>
                                                <?php if ($entrada['tipoEntrada'] == 'ENTRADA'): ?>
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-sign-in-alt"></i> Entrada
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-sign-out-alt"></i> Saída
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($entrada['permitida'] == 'T'): ?>
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Permitida
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times"></i> Negada
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?= htmlentities($entrada['mensagem'] ?? '') ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-left">
                        <small class="text-muted">
                            Mostrando <?= count($entradas) ?> de <?= number_format($total) ?> registros
                            <?php if ($total > 100): ?>
                                <br><em>Use os filtros para refinar a busca</em>
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Não precisa mais de Select2 pois removemos o filtro de evento
});

function exportarDados() {
    // Pega os parâmetros da URL atual
    const params = new URLSearchParams(window.location.search);
    params.append('exportar', 'excel');
    
    // Abre em nova aba
    window.open('<?php echo HOME_URI; ?>/entradas?' + params.toString(), '_blank');
    
    Swal.fire({
        icon: 'info',
        title: 'Exportando...',
        text: 'A exportação será aberta em uma nova aba',
        timer: 2000,
        showConfirmButton: false
    });
}
</script>

