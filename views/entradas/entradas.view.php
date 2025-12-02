<!-- Registros de Entrada -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Registros de Entrada</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Entradas</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Estatísticas Compactas -->
            <?php
            $idEvento = isset($_SESSION['idEventoHash']) ? decryptHash($_SESSION['idEventoHash']) : null;
            $stats = $modeloEntradas->getEstatisticas($idEvento);
            ?>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-body">
                        <div class="row text-center">
                            <div class="col-3">
                                <h4 class="mb-0"><?= number_format($stats['total']) ?></h4>
                                <small class="text-muted">Total</small>
                            </div>
                            <div class="col-3">
                                <h4 class="mb-0 text-success"><?= number_format($stats['permitidas']) ?></h4>
                                <small class="text-muted">Permitidas</small>
                            </div>
                            <div class="col-3">
                                <h4 class="mb-0 text-danger"><?= number_format($stats['negadas']) ?></h4>
                                <small class="text-muted">Negadas</small>
                            </div>
                            <div class="col-3">
                                <h4 class="mb-0 text-primary"><?= number_format($stats['entradas']) ?></h4>
                                <small class="text-muted">Entradas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtros</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formFiltros" method="GET">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="q" placeholder="Buscar por nome, CPF ou código de credencial..." value="<?= isset($_GET['q']) ? htmlentities($_GET['q']) : '' ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="tipoEntrada">
                                    <option value="">Tipo: Todos</option>
                                    <option value="ENTRADA" <?= (isset($_GET['tipoEntrada']) && $_GET['tipoEntrada'] == 'ENTRADA') ? 'selected' : '' ?>>Entrada</option>
                                    <option value="SAIDA" <?= (isset($_GET['tipoEntrada']) && $_GET['tipoEntrada'] == 'SAIDA') ? 'selected' : '' ?>>Saída</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="permitida">
                                    <option value="">Status: Todos</option>
                                    <option value="T" <?= (isset($_GET['permitida']) && $_GET['permitida'] == 'T') ? 'selected' : '' ?>>Permitida</option>
                                    <option value="F" <?= (isset($_GET['permitida']) && $_GET['permitida'] == 'F') ? 'selected' : '' ?>>Negada</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="<?php echo HOME_URI; ?>/entradas" class="btn btn-secondary btn-block">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <input type="date" class="form-control form-control-sm" name="dataInicio" placeholder="Data Início" value="<?= isset($_GET['dataInicio']) ? $_GET['dataInicio'] : '' ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control form-control-sm" name="dataFim" placeholder="Data Fim" value="<?= isset($_GET['dataFim']) ? $_GET['dataFim'] : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-sm btn-success float-right" onclick="exportarDados()">
                                    <i class="fas fa-file-excel"></i> Exportar Excel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Registros -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listagem de Entradas</h3>
                </div>
                <div class="card-body p-0">
                    <?php
                    $filtros = array(
                        'idEvento' => $idEvento,
                        'tipoEntrada' => isset($_GET['tipoEntrada']) ? $_GET['tipoEntrada'] : null,
                        'permitida' => isset($_GET['permitida']) ? $_GET['permitida'] : null,
                        'dataInicio' => isset($_GET['dataInicio']) ? $_GET['dataInicio'] : null,
                        'dataFim' => isset($_GET['dataFim']) ? $_GET['dataFim'] : null,
                        'q' => isset($_GET['q']) ? $_GET['q'] : null,
                        'limit' => 100
                    );
                    
                    $entradas = $modeloEntradas->getEntradas($filtros);
                    $total = $modeloEntradas->countEntradas($filtros);
                    ?>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40">Foto</th>
                                    <th width="100">Data/Hora</th>
                                    <th>Participante</th>
                                    <th>CPF</th>
                                    <th>Setor</th>
                                    <th>Lote Credencial</th>
                                    <th>Credencial</th>
                                    <th width="80">Tipo</th>
                                    <th width="90">Status</th>
                                    <th>Mensagem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($entradas)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                            <p>Nenhum registro encontrado</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($entradas as $entrada): ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if (!empty($entrada['pathLocalArquivo'])): ?>
                                                    <button type="button" class="btn btn-xs btn-outline-primary" onclick="verFoto('<?= htmlentities($entrada['pathLocalArquivo']) ?>')">
                                                        <i class="fas fa-camera"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <i class="fas fa-camera text-muted"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="d-block"><?= date('d/m/Y', strtotime($entrada['dataTentativa'])) ?></small>
                                                <strong><?= date('H:i:s', strtotime($entrada['dataTentativa'])) ?></strong>
                                            </td>
                                            <td><?= htmlentities($entrada['nomePessoa'] . ' ' . $entrada['sobrenomePessoa']) ?></td>
                                            <td><small><?= htmlentities($entrada['cpf'] ?? '-') ?></small></td>
                                            <td><small><?= htmlentities($entrada['nomeSetor'] ?? '-') ?></small></td>
                                            <td><small><?= htmlentities($entrada['nomeLote'] ?? '-') ?></small></td>
                                            <td><small><?= htmlentities($entrada['credencial'] ?? '-') ?></small></td>
                                            <td>
                                                <?php if ($entrada['tipoEntrada'] == 'ENTRADA'): ?>
                                                    <span class="badge badge-sm badge-info">Entrada</span>
                                                <?php else: ?>
                                                    <span class="badge badge-sm badge-secondary">Saída</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($entrada['permitida'] == 'T'): ?>
                                                    <span class="badge badge-sm badge-success">
                                                        <i class="fas fa-check"></i> OK
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-sm badge-danger">
                                                        <i class="fas fa-times"></i> Negado
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><small class="text-muted"><?= htmlentities($entrada['mensagem'] ?? '') ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        Mostrando <?= count($entradas) ?> de <?= number_format($total) ?> registros
                        <?php if ($total > 100): ?>
                            | <em>Use os filtros para refinar a busca</em>
                        <?php endif; ?>
                    </small>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal para visualizar foto -->
<div class="modal fade" id="modalFoto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Foto de Acesso</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="imagemAcesso" src="" class="img-fluid" alt="Foto de Acesso" style="max-height: 70vh;">
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos adicionais para interface mais profissional */
.table-sm td {
    vertical-align: middle;
    padding: 0.5rem;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.badge-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.btn-xs {
    padding: 0.2rem 0.4rem;
    font-size: 0.75rem;
}

#modalFoto .modal-body {
    background-color: #000;
}

#modalFoto img {
    display: block;
    margin: 0 auto;
}
</style>

<script>
function verFoto(pathLocal) {
    if (!pathLocal) {
        Swal.fire({
            icon: 'warning',
            title: 'Foto não disponível',
            text: 'Esta entrada não possui foto de acesso.',
            timer: 2000
        });
        return;
    }
    
    // Monta o caminho completo da imagem
    const imagemUrl = '<?php echo HOME_URI; ?>/../' + pathLocal;
    
    // Define a imagem no modal
    document.getElementById('imagemAcesso').src = imagemUrl;
    
    // Abre o modal
    $('#modalFoto').modal('show');
}

function exportarDados() {
    const params = new URLSearchParams(window.location.search);
    params.append('exportar', 'excel');
    
    window.open('<?php echo HOME_URI; ?>/entradas?' + params.toString(), '_blank');
    
    Swal.fire({
        icon: 'success',
        title: 'Exportando...',
        text: 'A exportação será aberta em uma nova aba',
        timer: 2000,
        showConfirmButton: false
    });
}

// Auto-atualização a cada 30 segundos (opcional)
// setInterval(function() {
//     location.reload();
// }, 30000);
</script>

