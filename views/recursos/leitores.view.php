<?php
if (!defined('ABSPATH')) exit;
require_once ABSPATH . '/views/recursos/common.php';

$modeloLeitores->validarFormLeitor();

$id = "";
if (chk_array($this->parametros, 0) == 'editar') {
    if (chk_array($this->parametros, 1)) {
        $hash = chk_array($this->parametros, 1);
        $id = decryptHash($hash);
    }
}

$hash = $_SESSION['idEventoHash'];
$idEvento = decryptHash($hash);

$leitor = "";
if ($id) {
    $leitor = $modeloLeitores->getLeitor($id);
}

$filtros = array('status' => 'T');
$leitores = $modeloLeitores->getLeitores();
$setores = $modeloSetores->getSetores($filtros);

?>

<div class="content-wrapper">
    <section class="content-header py-2">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-6">
                    <h4>Leitores</h4>
                </div>
            </div>
        </div>
    </section>

    <style>
        #tabelaLeitores_wrapper,
        #tabelaLeitores_wrapper .dataTables_scrollBody {
            overflow-x: hidden !important;
        }
    </style>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="leitores-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php echo !$id ? 'active' : ''; ?>" id="lista-tab" data-toggle="pill" href="#lista" role="tab" aria-controls="lista" aria-selected="<?php echo !$id ? 'true' : 'false'; ?>">
                                <i class="fas fa-list"></i> Lista de Leitores
                            </a>
                        </li>
                        <li class="nav-item">
                             <a class="nav-link <?php echo $id ? 'active' : ''; ?>" id="novo-tab" data-toggle="pill" href="#novo" role="tab" aria-controls="novo" aria-selected="<?php echo $id ? 'true' : 'false'; ?>">
                                <i class="fas fa-<?php echo $id ? 'edit' : 'plus'; ?>"></i> <?php echo $id ? 'Editar Leitor' : 'Novo Leitor'; ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content" id="leitores-tabContent">

                        <div class="tab-pane fade <?php echo !$id ? 'show active' : ''; ?>" id="lista" role="tabpanel" aria-labelledby="lista-tab">
                            <div class="card card-outline card-secondary mb-0">
                                <div class="card-header py-2">
                                    <h5 class="card-title mb-0">Leitores Cadastrados</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($leitores)) { ?>
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle"></i> Nenhum leitor encontrado.
                                        </div>
                                    <?php } else { ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped table-sm" id="tabelaLeitores">
                                                <thead>
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>Setor</th>
                                                        <th>IP</th>
                                                        <th width="80">Status</th>
                                                        <th width="80">Condição</th>
                                                        <th width="80" class="text-center">Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($leitores as $l): ?>
                                                        <tr>
                                                            <td><?php echo htmlentities($l['nomeLeitor']); ?></td>
                                                            <td><?php echo htmlentities($l['nomeSetor']); ?></td>
                                                            <td><?php echo htmlentities($l['ip']); ?></td>
                                                            <td><?php echo formatarStatus($l['status']); ?></td>
                                                            <td><?php echo formatarCondicao($l['condicao']); ?></td>
                                                            <td class="text-center">
                                                                <a href="<?php echo HOME_URI; ?>/recursos/leitores/editar/<?php echo encryptId($l['id']); ?>"
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

                         <div class="tab-pane fade <?php echo $id ? 'show active' : ''; ?>" id="novo" role="tabpanel" aria-labelledby="novo-tab">
                            <form role="form" action="" method="POST">
                                <div class="card card-outline card-primary mb-0">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0"><?php echo $id ? 'Editar Leitor' : 'Novo Leitor'; ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <?php echo $modeloLeitores->form_msg; ?>

                                        <input type="hidden" name="idEvento" value="<?php echo $idEvento; ?>">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="nomeLeitor">Nome do Leitor</label>
                                                    <input type="text" class="form-control form-control-sm" id="nomeLeitor" name="nomeLeitor"
                                                        placeholder="Nome do leitor"
                                                        value="<?php echo htmlentities(chk_array($leitor, 'nomeLeitor')); ?>"
                                                        required maxlength="100">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="idSetor">Setor</label>
                                                    <select class="form-control form-control-sm" id="idSetor" name="idSetor" required>
                                                        <option value="">Selecione um setor</option>
                                                        <?php foreach ($setores as $setor): ?>
                                                            <option value="<?php echo $setor['id']; ?>"
                                                                <?php echo chk_array($leitor, 'idSetor') == $setor['id'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlentities($setor['nomeSetor']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="status">Status</label>
                                                    <select class="form-control form-control-sm" id="status" name="status">
                                                        <option value="F" <?php echo chk_array($leitor, 'status') == 'F' ? 'selected' : ''; ?>>Inativo</option>
                                                        <option value="T" <?php echo !$leitor || chk_array($leitor, 'status') == 'T' ? 'selected' : ''; ?>>Ativo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="text-muted font-weight-bold">Configurações de Conexão</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="ip">IP</label>
                                                    <input type="text" class="form-control form-control-sm" id="ip" name="ip"
                                                        placeholder="Endereço IP"
                                                        value="<?php echo htmlentities(chk_array($leitor, 'ip')); ?>"
                                                        required maxlength="39">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="serverUrl">URL do Servidor</label>
                                                    <input type="text" class="form-control form-control-sm" id="serverUrl" name="serverUrl"
                                                        placeholder="URL do servidor"
                                                        value="<?php echo htmlentities(chk_array($leitor, 'serverUrl')); ?>"
                                                        maxlength="255">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="usuario">Usuário</label>
                                                    <input type="text" class="form-control form-control-sm" id="usuario" name="usuario"
                                                        placeholder="Usuário"
                                                        value="<?php echo htmlentities(chk_array($leitor, 'usuario')); ?>"
                                                        maxlength="100"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="senha">Senha</label>
                                                    <input type="password" class="form-control form-control-sm" id="senha" name="senha"
                                                        placeholder="Senha"
                                                        value="<?php echo htmlentities(chk_array($leitor, 'senha')); ?>"
                                                        maxlength="100"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="observacao">Observação</label>
                                            <textarea class="form-control form-control-sm" id="observacao" name="observacao" rows="2"
                                                placeholder="Observações sobre o leitor"
                                                maxlength="255"><?php echo htmlentities(chk_array($leitor, 'observacao')); ?></textarea>
                                        </div>

                                    </div>
                                    <div class="card-footer py-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i> Salvar
                                        </button>
                                        <?php if ($id): ?>
                                            <a href="<?php echo HOME_URI; ?>/recursos/leitores" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-times"></i> Cancelar Edição
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
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
        $('#tabelaLeitores').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "columnDefs": [{
                "orderable": false,
                "targets": [5]
            }]
        });

        // Adiciona um listener para quando o usuário clicar na aba de "Novo/Editar"
        $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            // Se a aba de lista for selecionada
            if ($(e.target).attr('id') == 'lista-tab') {
                // Verificamos se a URL ainda contém '/editar/'
                if (window.location.href.indexOf("/editar/") > -1) {
                    // Redireciona para a URL base para "limpar" o modo de edição
                    window.location.href = '<?php echo HOME_URI; ?>/recursos/leitores';
                }
            }
        });
    });
</script>