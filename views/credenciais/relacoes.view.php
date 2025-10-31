<?php
if (!defined('ABSPATH')) exit;

// Recupera o hash da credencial
$hash = chk_array($parametros, 1);
$idCredencial = !empty($hash) ? decryptHash($hash) : null;
$hashEvento = $_SESSION['idEventoHash'];
$idEvento = decryptHash($hashEvento);

// Se for post, valida o formulário
if (isset($_POST['salvar_credencial'])) {
    $modeloCredenciais->validarFormRelacoes();
}

// Carrega os dados da credencial e do lote
$credencial = $idCredencial ? $modeloCredenciais->getCredencial($idCredencial) : [];
$lote = !empty($credencial) ? $modeloLotes->getLote($credencial['idLote']) : [];
$setores = $modeloSetores->getSetores($idEvento); // Setores disponíveis no evento
$setoresSelecionados = $idCredencial ? $modeloCredenciais->getSetoresCredencial($idCredencial) : []; // Setores permitidos na credencial
$periodos = $idCredencial ? $modeloCredenciais->getPeriodosCredencial($idCredencial) : [];

?>

<div class="content-wrapper">
    <section class="content-header py-2">
        <div class="container-fluid">
            <div class="row mb-1">
                <div class="col-sm-6">
                    <h4>Relações da Credencial: <?php echo htmlentities(chk_array($credencial, 'nomeCredencial')); ?></h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>/lotes">Lotes de Credenciais</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>/credenciais/index/lotes/<?php echo encryptId($credencial['idLote']); ?>">Credenciais</a></li>
                        <li class="breadcrumb-item active">Relações da Credencial</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <?php echo $modeloCredenciais->form_msg; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="credencial-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="periodos-tab" data-toggle="pill" href="#periodos" role="tab" aria-controls="periodos" aria-selected="true">
                                <i class="fas fa-clock"></i> Períodos de Acesso
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="setores-tab" data-toggle="pill" href="#setores" role="tab" aria-controls="setores" aria-selected="false">
                                <i class="fas fa-map-marker-alt"></i> Setores Permitidos
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content" id="credencial-tabContent">
                        <!-- Tab Períodos de Acesso -->
                        <div class="tab-pane fade show active" id="periodos" role="tabpanel" aria-labelledby="periodos-tab">
                            <div class="card card-outline card-primary">
                                <div class="card-header py-2">
                                    <h5 class="card-title mb-0">Períodos de Acesso Específicos</h5>
                                    <div class="card-tools">
                                        <small class="text-muted">Lote: <?php echo htmlentities($lote['nomeLote']); ?></small>
                                    </div>
                                </div>
                                <form role="form" action="" method="POST">
                                    <input type="hidden" name="idCredencial" value="<?php echo $idCredencial; ?>">
                                    <input type="hidden" name="acao" value="periodos">
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Configure períodos específicos para esta credencial. Se não configurado, usará os períodos do lote.
                                        </div>

                                        <div id="periodos">
                                            <?php if (!empty($periodos)): ?>
                                                <?php foreach ($periodos as $index => $periodo): ?>
                                                    <div class="row periodo-item mb-3 border-bottom pb-3">
                                                        <div class="col-md-3">
                                                            <label>Data de Início</label>
                                                            <input type="date" class="form-control form-control-sm" name="periodos[<?php echo $index; ?>][dataInicio]" value="<?php echo $periodo['dataInicio']; ?>" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Hora de Início</label>
                                                            <input type="time" class="form-control form-control-sm" name="periodos[<?php echo $index; ?>][horaInicio]" value="<?php echo $periodo['horaInicio']; ?>" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Data de Término</label>
                                                            <input type="date" class="form-control form-control-sm" name="periodos[<?php echo $index; ?>][dataTermino]" value="<?php echo $periodo['dataTermino']; ?>" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Hora de Término</label>
                                                            <input type="time" class="form-control form-control-sm" name="periodos[<?php echo $index; ?>][horaTermino]" value="<?php echo $periodo['horaTermino']; ?>" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-sm btn-block" onclick="removerPeriodo(this)">
                                                                <i class="fas fa-trash"></i> Remover
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="row periodo-item mb-3 border-bottom pb-3">
                                                    <div class="col-md-3">
                                                        <label>Data de Início</label>
                                                        <input type="date" class="form-control form-control-sm" name="periodos[0][dataInicio]" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Hora de Início</label>
                                                        <input type="time" class="form-control form-control-sm" name="periodos[0][horaInicio]" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Data de Término</label>
                                                        <input type="date" class="form-control form-control-sm" name="periodos[0][dataTermino]" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Hora de Término</label>
                                                        <input type="time" class="form-control form-control-sm" name="periodos[0][horaTermino]" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>&nbsp;</label>
                                                        <button type="button" class="btn btn-danger btn-sm btn-block" onclick="removerPeriodo(this)">
                                                            <i class="fas fa-trash"></i> Remover
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm mt-2" onclick="adicionarPeriodo()">
                                            <i class="fas fa-plus"></i> Adicionar Período
                                        </button>
                                    </div>
                                    <div class="card-footer py-2">
                                        <button type="submit" name="salvar_credencial" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i> Salvar Períodos
                                        </button>
                                        <a href="<?php echo HOME_URI; ?>/credenciais/index/lotes/<?php echo encryptId($credencial['idLote']); ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-arrow-left"></i> Voltar
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tab Setores Permitidos -->
                        <div class="tab-pane fade" id="setores" role="tabpanel" aria-labelledby="setores-tab">
                            <div class="card card-outline card-success">
                                <div class="card-header py-2">
                                    <h5 class="card-title mb-0">Setores Específicos</h5>
                                    <div class="card-tools">
                                        <small class="text-muted">Sobrescreve configurações do lote</small>
                                    </div>
                                </div>
                                <form role="form" action="" method="POST">
                                    <input type="hidden" name="idCredencial" value="<?php echo $idCredencial; ?>">
                                    <input type="hidden" name="acao" value="setores">
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Configure setores específicos para esta credencial. Se não configurado, usará os setores do lote.
                                        </div>

                                        <div class="row" id="setores">
                                            <?php foreach ($setores as $setor):
                                                // Verifica se o setor está selecionado
                                                $setorSelecionado = null;
                                                foreach ($setoresSelecionados as $sel) {
                                                    if ($sel['idSetor'] == $setor['id']) {
                                                        $setorSelecionado = $sel;
                                                        break;
                                                    }
                                                }
                                            ?>
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card card-outline <?php echo $setorSelecionado && $setorSelecionado['status'] == 'T' ? 'card-success' : 'card-secondary'; ?>">
                                                        <div class="card-header py-2">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="setores[<?php echo $setor['id']; ?>][id]" value="<?php echo $setor['id']; ?>">
                                                                <input type="hidden" name="setores[<?php echo $setor['id']; ?>][status]" value="F">
                                                                <input type="checkbox" class="custom-control-input" id="setor-<?php echo $setor['id']; ?>" 
                                                                    name="setores[<?php echo $setor['id']; ?>][status]" value="T"
                                                                    <?php echo $setorSelecionado && $setorSelecionado['status'] == 'T' ? 'checked' : ''; ?>>
                                                                <label class="custom-control-label font-weight-bold" for="setor-<?php echo $setor['id']; ?>">
                                                                    <?php echo htmlentities($setor['nomeSetor']); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="card-body setor-opcoes" id="opcoes-<?php echo $setor['id']; ?>" 
                                                            style="display: <?php echo $setorSelecionado && $setorSelecionado['status'] == 'T' ? 'block' : 'none'; ?>;">
                                                            
                                                            <div class="form-group mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="permiteSaida-<?php echo $setor['id']; ?>"
                                                                        name="setores[<?php echo $setor['id']; ?>][permiteSaida]" value="T"
                                                                        <?php echo $setorSelecionado && $setorSelecionado['permiteSaida'] == 1 ? 'checked' : ''; ?>>
                                                                    <label class="custom-control-label" for="permiteSaida-<?php echo $setor['id']; ?>">Permite Saída</label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="permiteReentrada-<?php echo $setor['id']; ?>"
                                                                        name="setores[<?php echo $setor['id']; ?>][permiteReentrada]" value="T"
                                                                        <?php echo $setorSelecionado && $setorSelecionado['permiteReentrada'] == 1 ? 'checked' : ''; ?>>
                                                                    <label class="custom-control-label" for="permiteReentrada-<?php echo $setor['id']; ?>">Permite Reentrada</label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group mb-0 tipo-reentrada"
                                                                id="tipo-reentrada-<?= $setor['id'] ?>"
                                                                style="display: <?= ($setorSelecionado && $setorSelecionado['permiteReentrada'] == 1) ? 'block' : 'none' ?>;">
                                                                <label class="text-sm">Tipo de Reentrada</label>
                                                                <?php
                                                                $sel1 = ($setorSelecionado && $setorSelecionado['tipoReentrada'] == 1) ? 'checked' : '';
                                                                $sel2 = ($setorSelecionado && $setorSelecionado['tipoReentrada'] == 2) ? 'checked' : '';
                                                                ?>
                                                                <div class="form-check form-check-sm">
                                                                    <input class="form-check-input" type="radio" 
                                                                        name="setores[<?= $setor['id'] ?>][tipoReentrada]"
                                                                        id="tipoReentrada-<?= $setor['id'] ?>-1" value="1" <?= $sel1 ?>>
                                                                    <label class="form-check-label text-sm" for="tipoReentrada-<?= $setor['id'] ?>-1">
                                                                        Apenas neste setor
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-sm">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="setores[<?= $setor['id'] ?>][tipoReentrada]"
                                                                        id="tipoReentrada-<?= $setor['id'] ?>-2" value="2" <?= $sel2 ?>>
                                                                    <label class="form-check-label text-sm" for="tipoReentrada-<?= $setor['id'] ?>-2">
                                                                        Em todos os setores
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <div class="card-footer py-2">
                                        <button type="submit" name="salvar_credencial" class="btn btn-success btn-sm">
                                            <i class="fas fa-save"></i> Salvar Setores
                                        </button>
                                        <a href="<?php echo HOME_URI; ?>/credenciais/index/lotes/<?php echo encryptId($credencial['idLote']); ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-arrow-left"></i> Voltar
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function adicionarPeriodo() {
        const periodos = document.getElementById('periodos');
        const index = periodos.children.length;
        const row = document.createElement('div');
        row.className = 'row periodo-item mb-3 border-bottom pb-3';
        row.innerHTML = `
            <div class="col-md-3">
                <label>Data de Início</label>
                <input type="date" class="form-control form-control-sm" name="periodos[${index}][dataInicio]" required>
            </div>
            <div class="col-md-2">
                <label>Hora de Início</label>
                <input type="time" class="form-control form-control-sm" name="periodos[${index}][horaInicio]" required>
            </div>
            <div class="col-md-3">
                <label>Data de Término</label>
                <input type="date" class="form-control form-control-sm" name="periodos[${index}][dataTermino]" required>
            </div>
            <div class="col-md-2">
                <label>Hora de Término</label>
                <input type="time" class="form-control form-control-sm" name="periodos[${index}][horaTermino]" required>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm btn-block" onclick="removerPeriodo(this)">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </div>
        `;
        periodos.appendChild(row);
    }

    function removerPeriodo(button) {
        button.closest('.periodo-item').remove();
    }

    $(document).ready(function() {
        // Mostra/esconde opções de setor quando o checkbox é alterado
        $('input[name^="setores"][name$="[status]"]').change(function() {
            const idSetor = $(this).attr('name').match(/\[(\d+)\]/)[1];
            const opcoesDiv = $(`#opcoes-${idSetor}`);
            const card = $(this).closest('.card');

            if ($(this).is(':checked')) {
                opcoesDiv.show();
                card.removeClass('card-secondary').addClass('card-success');
            } else {
                opcoesDiv.hide();
                card.removeClass('card-success').addClass('card-secondary');
            }
        });

        // Mostra/esconde tipo de reentrada quando a opção de reentrada é alterada
        $('input[name^="setores"][name$="[permiteReentrada]"]').change(function() {
            const idSetor = $(this).attr('name').match(/\[(\d+)\]/)[1];
            const tipoReentradaDiv = $(`#tipo-reentrada-${idSetor}`);

            if ($(this).is(':checked')) {
                tipoReentradaDiv.show();
            } else {
                tipoReentradaDiv.hide();
            }
        });
    });
</script>