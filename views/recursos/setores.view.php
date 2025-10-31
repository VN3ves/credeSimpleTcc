<?php
if (!defined('ABSPATH')) exit;
require_once ABSPATH . '/views/recursos/common.php';

// A lógica de negócio permanece a mesma
$modeloSetores->validarFormSetor();

$id = "";
$setor = [];
if (chk_array($this->parametros, 0) == 'editar' && chk_array($this->parametros, 1)) {
    $hash = chk_array($this->parametros, 1);
    $id = decryptHash($hash);
    if ($id) {
        $setor = $modeloSetores->getSetor($id);
    }
}

$setores = $modeloSetores->getSetores();

// Define qual aba deve estar ativa. A de listagem é o padrão, a menos que uma edição falhe.
$isEditing = !empty($id);
$isFormActive = !$isEditing && !empty($modeloSetores->form_msg); // Ativa o form se houver erro na criação
$isListActive = $isEditing || !$isFormActive;

?>

<div class="content-wrapper">
    <section class="content-header py-2">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-6">
                    <h4><i class="fas fa-sitemap"></i> Gestão de Setores</h4>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="setores-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?= !$isListActive ? 'active' : '' ?>" id="form-tab" data-toggle="pill" href="#form-pane" role="tab" aria-controls="form-pane" aria-selected="<?= !$isListActive ? 'true' : 'false' ?>">
                                <i class="fas fa-edit"></i> <?= $id ? 'Editar Setor' : 'Novo Setor' ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isListActive ? 'active' : '' ?>" id="lista-tab" data-toggle="pill" href="#lista-pane" role="tab" aria-controls="lista-pane" aria-selected="<?= $isListActive ? 'true' : 'false' ?>">
                                <i class="fas fa-list"></i> Lista de Setores
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content" id="setores-tabContent">

                        <div class="tab-pane fade <?= !$isListActive ? 'show active' : '' ?>" id="form-pane" role="tabpanel" aria-labelledby="form-tab">
                            <form role="form" action="" method="POST">
                                <div class="card card-outline card-primary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0"><?= $id ? 'Editar Setor' : 'Cadastro de Novo Setor' ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($modeloSetores->form_msg)) : ?>
                                            <div class="alert alert-warning"><?= $modeloSetores->form_msg ?></div>
                                        <?php endif; ?>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group mb-3">
                                                    <label for="nomeSetor">Nome do Setor</label>
                                                    <input type="text" class="form-control form-control-sm" id="nomeSetor" name="nomeSetor" placeholder="Ex: Financeiro, TI, Recursos Humanos" value="<?= htmlentities(chk_array($setor, 'nomeSetor')); ?>" required maxlength="100">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="status">Status</label>
                                                    <select class="form-control form-control-sm" id="status" name="status">
                                                        <option value="T" <?= chk_array($setor, 'status') == 'T' ? 'selected' : ''; ?>>Ativo</option>
                                                        <option value="F" <?= chk_array($setor, 'status') == 'F' ? 'selected' : ''; ?>>Inativo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="observacao">Observação</label>
                                            <textarea class="form-control form-control-sm" id="observacao" name="observacao" rows="3" placeholder="Observações sobre o setor (opcional)" maxlength="255"><?= htmlentities(chk_array($setor, 'observacao')); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="card-footer py-2 text-right">
                                        <a href="<?= HOME_URI; ?>/recursos/setores" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i> Salvar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade <?= $isListActive ? 'show active' : '' ?>" id="lista-pane" role="tabpanel" aria-labelledby="lista-tab">
                            <div class="card card-outline card-secondary">
                                <div class="card-header py-2">
                                    <h5 class="card-title mb-0">Setores Cadastrados</h5>
                                    <div class="card-tools">
                                        <button id="btn-novo-setor" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i> Novo Setor
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($setores)) : ?>
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle"></i> Nenhum setor encontrado. Clique em "Novo Setor" para começar.
                                        </div>
                                    <?php else : ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped table-sm" id="tabelaSetores" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th style="width: 100px;" class="text-center">Status</th>
                                                        <th style="width: 100px;" class="text-center">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($setores as $s) : ?>
                                                        <tr>
                                                            <td><?= htmlentities($s['nomeSetor']); ?></td>
                                                            <td class="text-center">
                                                                <?php if ($s['status'] == 'T') : ?>
                                                                    <span class="badge badge-success">Ativo</span>
                                                                <?php else : ?>
                                                                    <span class="badge badge-secondary">Inativo</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="<?= HOME_URI; ?>/recursos/setores/editar/<?= encryptId($s['id']); ?>" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Editar">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                                <a href="<?= HOME_URI; ?>/recursos/setores/excluir/<?= encryptId($s['id']); ?>" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este setor?');">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
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
        // Inicializa o plugin DataTables
        $('#tabelaSetores').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"
            },
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": [2] // Desativa a ordenação na coluna 'Ações'
            }]
        });

        // Inicializa os Tooltips do Bootstrap
        $('[data-toggle="tooltip"]').tooltip();
        
        // Ação para o botão "Novo Setor" na aba de lista
        $('#btn-novo-setor').on('click', function() {
            $('#form-tab').tab('show');
        });

        // Lógica para manter a aba correta ativa no reload da página
        // Se houver um hash na URL que corresponda a uma aba, ativa-a
        var hash = window.location.hash;
        if (hash) {
            $('a[href="' + hash + '"]').tab('show');
        }

        // Muda o hash na URL quando uma aba é clicada para manter o estado
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        });

        <?php if ($id || !$isListActive): ?>
            // Se estiver editando ou se houve um erro no formulário de novo, força a aba do formulário
            $('#form-tab').tab('show');
        <?php else: ?>
            // Caso contrário, mostra a lista
            $('#lista-tab').tab('show');
        <?php endif; ?>
    });
</script>