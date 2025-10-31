<?php
if (!defined('ABSPATH')) exit;
require_once ABSPATH . '/views/recursos/common.php';

$modeloTerminais->validarFormTerminal();

$id = "";
if (chk_array($this->parametros, 0) == 'editar') {
    if (chk_array($this->parametros, 1)) {
        $hash = chk_array($this->parametros, 1);
        $id = decryptHash($hash);
    }
}

$hash = $_SESSION['idEventoHash'];
$idEvento = decryptHash($hash);

$terminal = "";
if ($id) {
    $terminal = $modeloTerminais->getTerminal($id);
}

$terminais = $modeloTerminais->getTerminais();
$filtros = array('status' => 'T');
$setores = $modeloSetores->getSetores($filtros);

$marcasDisponiveis = $modeloTerminais->getMarcasDisponiveis($idEvento);
?>

<div class="content-wrapper">
    <section class="content-header py-2">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-6">
                    <h4>Terminais</h4>
                </div>
            </div>
        </div>
    </section>

    <style>
        #tabelaTerminais_wrapper,
        #tabelaTerminais_wrapper .dataTables_scrollBody {
            overflow-x: hidden !important;
        }
    </style>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="terminais-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php echo !$id ? 'active' : ''; ?>" id="novo-tab" data-toggle="pill" href="#novo" role="tab" aria-controls="novo" aria-selected="true">
                                <i class="fas fa-plus"></i> Novo Terminal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $id ? 'active' : ''; ?>" id="lista-tab" data-toggle="pill" href="#lista" role="tab" aria-controls="lista" aria-selected="false">
                                <i class="fas fa-list"></i> Lista de Terminais
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content" id="terminais-tabContent">
                        <!-- Tab Novo/Editar Terminal -->
                        <div class="tab-pane fade <?php echo !$id ? 'show active' : ''; ?>" id="novo" role="tabpanel" aria-labelledby="novo-tab">
                            <form role="form" action="" method="POST">
                                <div class="card card-outline card-primary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0"><?php echo $id ? 'Editar Terminal' : 'Novo Terminal'; ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <?php echo $modeloTerminais->form_msg; ?>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="idSetor">Setor</label>
                                                    <select class="form-control form-control-sm" id="idSetor" name="idSetor" required>
                                                        <option value="">Selecione um setor</option>
                                                        <?php foreach ($setores as $setor): ?>
                                                            <option value="<?php echo $setor['id']; ?>"
                                                                <?php echo chk_array($terminal, 'idSetor') == $setor['id'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlentities($setor['nomeSetor']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="marca">Marca</label>
                                                    <select class="form-control form-control-sm" id="marca" name="marca" required>
                                                        <option value="">Selecione uma marca</option>
                                                        <?php foreach ($marcasDisponiveis as $marcaOp): ?>
                                                            <option value="<?= htmlentities($marcaOp) ?>"
                                                                <?= ($marcaOp === chk_array($terminal, 'marca')) ? 'selected' : '' ?>>
                                                                <?= htmlentities(strtoupper($marcaOp)) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="tipo">Tipo</label>
                                                    <select class="form-control form-control-sm" id="tipo" name="tipo" required>
                                                        <option value="ENTRADA" <?php echo chk_array($terminal, 'tipo') == 'ENTRADA' ? 'selected' : ''; ?>>Entrada</option>
                                                        <option value="SAIDA" <?php echo chk_array($terminal, 'tipo') == 'SAIDA' ? 'selected' : ''; ?>>Saída</option>
                                                        <option value="AMBOS" <?php echo chk_array($terminal, 'tipo') == 'AMBOS' ? 'selected' : ''; ?>>Ambos</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="nomeTerminal">Nome do Terminal</label>
                                                    <input type="text" class="form-control form-control-sm" id="nomeTerminal" name="nomeTerminal"
                                                        placeholder="Nome do terminal"
                                                        value="<?php echo htmlentities(chk_array($terminal, 'nomeTerminal')); ?>"
                                                        required maxlength="100">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="numero">Número</label>
                                                    <input type="number" class="form-control form-control-sm" id="numero" name="numero"
                                                        placeholder="Número do terminal"
                                                        value="<?php echo htmlentities(chk_array($terminal, 'numero')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="status">Status</label>
                                                    <select class="form-control form-control-sm" id="status" name="status">
                                                        <option value="F" <?php echo chk_array($terminal, 'status') == 'F' ? 'selected' : ''; ?>>Inativo</option>
                                                        <option value="T" <?php echo chk_array($terminal, 'status') == 'T' ? 'selected' : ''; ?>>Ativo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Accordion para configurações avançadas -->
                                        <div id="accordion">
                                            <div class="card">
                                                <div class="card-header py-1" id="headingOne">
                                                    <h6 class="mb-0">
                                                        <button class="btn btn-link btn-sm" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                            <i class="fas fa-cog"></i> Configurações de Conexão
                                                        </button>
                                                    </h6>
                                                </div>
                                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                                    <div class="card-body py-2">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="ip">IP</label>
                                                                    <input type="text" class="form-control form-control-sm" id="ip" name="ip"
                                                                        placeholder="Endereço IP"
                                                                        value="<?php echo htmlentities(chk_array($terminal, 'ip')); ?>"
                                                                        maxlength="39">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="serverUrl">URL do Servidor</label>
                                                                    <input type="text" class="form-control form-control-sm" id="serverUrl" name="serverUrl"
                                                                        placeholder="URL do servidor"
                                                                        value="<?php echo htmlentities(chk_array($terminal, 'serverUrl')); ?>"
                                                                        maxlength="100">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6" id="grupoUsuario" hidden>
                                                                <div class="form-group mb-3">
                                                                    <label for="usuario">Usuário</label>
                                                                    <input type="text" class="form-control form-control-sm" id="usuario" name="usuario"
                                                                        placeholder="Usuário"
                                                                        value="<?php echo htmlentities(chk_array($terminal, 'usuario')); ?>"
                                                                        maxlength="100">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6" id="grupoSenha" hidden>
                                                                <div class="form-group mb-3">
                                                                    <label for="senha">Senha</label>
                                                                    <input type="password" class="form-control form-control-sm" id="senha" name="senha"
                                                                        placeholder="Senha"
                                                                        value="<?php echo htmlentities(chk_array($terminal, 'senha')); ?>"
                                                                        maxlength="100">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="observacao">Observação</label>
                                                            <textarea class="form-control form-control-sm" id="observacao" name="observacao" rows="2"
                                                                placeholder="Observações sobre o terminal"
                                                                maxlength="255"><?php echo htmlentities(chk_array($terminal, 'observacao')); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer py-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i> Salvar
                                        </button>
                                        <?php if ($id): ?>
                                            <a href="<?php echo HOME_URI; ?>/recursos/terminais" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-times"></i> Cancelar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Tab Lista de Terminais -->
                        <div class="tab-pane fade <?php echo $id ? 'show active' : ''; ?>" id="lista" role="tabpanel" aria-labelledby="lista-tab">
                            <div class="card card-outline card-secondary">
                                <div class="card-header py-2">
                                    <h5 class="card-title mb-0">Lista de Terminais</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($terminais)) { ?>
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle"></i> Nenhum terminal encontrado.
                                        </div>
                                    <?php } else { ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped table-sm" id="tabelaTerminais">
                                                <thead>
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>Setor</th>
                                                        <th>IP</th>
                                                        <th width="80">Tipo</th>
                                                        <th width="80">Status</th>
                                                        <th width="80">Condição</th>
                                                        <th width="80" class="text-center">Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($terminais as $t): ?>
                                                        <tr>
                                                            <td><?php echo htmlentities($t['nomeTerminal']); ?></td>
                                                            <td><?php echo htmlentities($t['nomeSetor']); ?></td>
                                                            <td><?php echo htmlentities($t['ip']); ?></td>
                                                            <td><?php echo $t['tipo']; ?></td>
                                                            <td><?php echo formatarStatus($t['status']); ?></td>
                                                            <td><?php echo formatarCondicao($t['condicao']); ?></td>
                                                            <td class="text-center">
                                                                <a href="<?php echo HOME_URI; ?>/recursos/terminais/editar/<?php echo encryptId($t['id']); ?>"
                                                                    class="btn btn-sm btn-outline-primary" title="Editar">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Inicializa DataTable para paginação automática
    $('#tabelaTerminais').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "columnDefs": [
            { "orderable": false, "targets": [6] }
        ]
    });

    // Função para mostrar/ocultar campos de credenciais baseado na marca
    function toggleCredenciais(){
        const marca = $('#marca').val();
        const mostrar = (marca && marca !== 'padrao');
        
        $('#grupoUsuario, #grupoSenha')
            .toggle(mostrar)
            .attr('hidden', !mostrar);
    }

    // Evento de mudança na marca
    $('#marca').on('change', toggleCredenciais);
    
    // Estado inicial
    toggleCredenciais();

    // Se está editando, ativa a tab lista
    <?php if ($id): ?>
        $('#lista-tab').tab('show');
    <?php endif; ?>
});
</script>
