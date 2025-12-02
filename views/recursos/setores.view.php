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

// LÓGICA DE ABAS SIMPLIFICADA
// A aba do formulário deve estar ativa se:
// 1. Estiver em modo de edição (ex: /setores/editar/HASH)
// 2. OU Se houve um erro ao criar um NOVO setor (ex: campo nome vazio)
$isFormActive = ($id > 0) || (!empty($modeloSetores->form_msg) && !$id);

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
                        <!--
                            CORREÇÃO:
                            - Aba "Lista" vem primeiro e é a padrão (active), a menos que $isFormActive seja true.
                        -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo !$isFormActive ? 'active' : '' ?>" id="lista-tab" data-toggle="pill" href="#lista-pane" role="tab" aria-controls="lista-pane" aria-selected="<?php echo !$isFormActive ? 'true' : 'false' ?>">
                                <i class="fas fa-list"></i> Lista de Setores
                            </a>
                        </li>
                        <!--
                            CORREÇÃO:
                            - Aba "Formulário" vem em segundo e só fica ativa se $isFormActive for true.
                            - O ícone e o texto são dinâmicos (Novo/Editar).
                        -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo $isFormActive ? 'active' : '' ?>" id="form-tab" data-toggle="pill" href="#form-pane" role="tab" aria-controls="form-pane" aria-selected="<?php echo $isFormActive ? 'true' : 'false' ?>">
                                <i class="fas fa-<?php echo $id ? 'edit' : 'plus'; ?>"></i> <?php echo $id ? 'Editar Setor' : 'Novo Setor' ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content" id="setores-tabContent">

                        <!--
                            CORREÇÃO:
                            - Painel "Lista" vem primeiro e é o padrão (show active).
                        -->
                        <div class="tab-pane fade <?php echo !$isFormActive ? 'show active' : '' ?>" id="lista-pane" role="tabpanel" aria-labelledby="lista-tab">
                            <div class="card card-outline card-secondary mb-0">
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
                                                            <td><?php echo htmlentities($s['nomeSetor']); ?></td>
                                                            <td class="text-center">
                                                                <?php echo formatarStatus($s['status']); // Usando a função common ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="<?php echo HOME_URI; ?>/recursos/setores/editar/<?php echo encryptId($s['id']); ?>" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Editar">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                                <!--
                                                                    MELHORIA PROFISSIONAL:
                                                                    - Removido o 'onclick="return confirm(...)"'.
                                                                    - Adicionado 'data-toggle="modal"', 'data-target' e 'data-hash'
                                                                      para acionar o modal de confirmação.
                                                                -->
                                                                <a href="#"
                                                                   class="btn btn-sm btn-outline-danger"
                                                                   data-toggle="modal"
                                                                   data-target="#modalExcluir"
                                                                   data-hash="<?php echo encryptId($s['id']); ?>"
                                                                   data-tooltip="tooltip"
                                                                   title="Excluir">
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

                        <!--
                            CORREÇÃO:
                            - Painel "Formulário" vem em segundo.
                        -->
                        <div class="tab-pane fade <?php echo $isFormActive ? 'show active' : '' ?>" id="form-pane" role="tabpanel" aria-labelledby="form-tab">
                            <form role="form" action="" method="POST">
                                <div class="card card-outline card-primary mb-0">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0"><?php echo $id ? 'Editar Setor' : 'Cadastro de Novo Setor' ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($modeloSetores->form_msg)) : ?>
                                            <!--
                                                MELHORIA:
                                                - A mensagem de erro agora usa a classe correta de 'alert'
                                                  para mensagens de formulário (ex: 'alert-danger').
                                            -->
                                            <?php echo $modeloSetores->form_msg; ?>
                                        <?php endif; ?>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group mb-3">
                                                    <label for="nomeSetor">Nome do Setor</label>
                                                    <input type="text" class="form-control form-control-sm" id="nomeSetor" name="nomeSetor" placeholder="Ex: Financeiro, TI, Recursos Humanos" value="<?php echo htmlentities(chk_array($setor, 'nomeSetor')); ?>" required maxlength="100">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="status">Status</label>
                                                    <select class="form-control form-control-sm" id="status" name="status">
                                                        <!--
                                                            CORREÇÃO:
                                                            - Adicionada lógica para 'Ativo' ser o padrão ao criar.
                                                        -->
                                                        <option value="F" <?php echo chk_array($setor, 'status') == 'F' ? 'selected' : ''; ?>>Inativo</option>
                                                        <option value="T" <?php echo !$setor || chk_array($setor, 'status') == 'T' ? 'selected' : ''; ?>>Ativo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="observacao">Observação</label>
                                            <textarea class="form-control form-control-sm" id="observacao" name="observacao" rows="3" placeholder="Observações sobre o setor (opcional)" maxlength="255"><?php echo htmlentities(chk_array($setor, 'observacao')); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="card-footer py-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i> Salvar
                                        </button>
                                        <a href="<?php echo HOME_URI; ?>/recursos/setores" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
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

<!--
    MELHORIA PROFISSIONAL:
    - HTML do Modal de Confirmação para Excluir.
    - Este modal será acionado por qualquer botão com 'data-target="#modalExcluir"'.
-->
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="modalExcluirLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExcluirLabel">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este setor?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <!-- O 'href' deste botão será preenchido via JavaScript -->
                <a id="btnConfirmarExclusao" class="btn btn-danger" href="#">Excluir</a>
            </div>
        </div>
    </div>
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

        // Lógica para "limpar" a URL ao sair do modo de edição
        $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            // Se a aba de lista for selecionada
            if ($(e.target).attr('id') == 'lista-tab') {
                // Verificamos se a URL ainda contém '/editar/'
                if (window.location.href.indexOf("/editar/") > -1) {
                    // Redireciona para a URL base para "limpar" o modo de edição
                    window.location.href = '<?php echo HOME_URI; ?>/recursos/setores';
                }
            }
        });

        // MELHORIA PROFISSIONAL:
        // JavaScript para o modal de exclusão
        $('#modalExcluir').on('show.bs.modal', function(event) {
            // Botão que acionou o modal
            var button = $(event.relatedTarget);
            // Extrai o hash do 'data-hash' do botão
            var hash = button.data('hash');
            var modal = $(this);
            // Monta a URL de exclusão
            var deleteUrl = '<?php echo HOME_URI; ?>/recursos/setores/excluir/' + hash;
            // Define o 'href' do botão de confirmação do modal
            modal.find('#btnConfirmarExclusao').attr('href', deleteUrl);
        });

        // Remove o script que forçava a aba, pois o PHP no HTML já faz isso.
    });
</script>