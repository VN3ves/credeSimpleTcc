<?php
if (!defined('ABSPATH')) exit;

// Recupera o hash do lote, se disponível (edição)
$hash = chk_array($parametros, 1);
$idLote = !empty($hash) ? decryptHash($hash) : null;
$hashEvento = $_SESSION['idEventoHash'];
$idEvento = decryptHash($hashEvento);

// Se for post, valida o formulário
if (isset($_POST['salvar_lote'])) {
    $modeloLotes->validarFormRelacoes();
}

// Carrega os dados do lote, setores e períodos, se houver (edição)
$lote = $idLote ? $modeloLotes->getLote($idLote) : [];
$setores = $modeloSetores->getSetores($idEvento); // Setores disponíveis no evento
$setoresSelecionados = $idLote ? $modeloLotes->getSetoresLote($idLote) : []; // Setores permitidos no lote
$periodos = $idLote ? $modeloLotes->getPeriodosLote($idLote) : [];

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Relações do Lote de Credenciais: <?php echo htmlentities(chk_array($lote, 'nomeLote')); ?> </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>/lotes">Lotes de Credenciais</a></li>
                        <li class="breadcrumb-item active"> Relações do Lote de Credenciais</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <?php echo $modeloLotes->form_msg; ?>

    <section class="content">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Períodos de Acesso</h3>
            </div>
            <form role="form" action="" method="POST">
                <input type="hidden" name="idLote" value="<?php echo $idLote; ?>">
                <input type="hidden" name="idEvento" value="<?php echo $idEvento; ?>">
                <div class="card-body">

                    <!-- Períodos de Acesso -->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="periodos">
                                <?php if (!empty($periodos)): ?>
                                    <?php foreach ($periodos as $index => $periodo): ?>
                                        <div class="row periodo-item mb-2">
                                            <div class="col-md-3">
                                                <label>Data de Início</label>
                                                <input type="date" class="form-control" name="periodos[<?php echo $index; ?>][dataInicio]" value="<?php echo $periodo['dataInicio']; ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Hora de Início</label>
                                                <input type="time" class="form-control" name="periodos[<?php echo $index; ?>][horaInicio]" value="<?php echo $periodo['horaInicio']; ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Data de Término</label>
                                                <input type="date" class="form-control" name="periodos[<?php echo $index; ?>][dataTermino]" value="<?php echo $periodo['dataTermino']; ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Hora de Término</label>
                                                <input type="time" class="form-control" name="periodos[<?php echo $index; ?>][horaTermino]" value="<?php echo $periodo['horaTermino']; ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block" onclick="removerPeriodo(this)">Remover</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Caso não tenha períodos, inicia com um campo em branco -->
                                    <div class="row periodo-item mb-2">
                                        <div class="col-md-3">
                                            <label>Data de Início</label>
                                            <input type="date" class="form-control" name="periodos[0][dataInicio]" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Hora de Início</label>
                                            <input type="time" class="form-control" name="periodos[0][horaInicio]" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Data de Término</label>
                                            <input type="date" class="form-control" name="periodos[0][dataTermino]" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Hora de Término</label>
                                            <input type="time" class="form-control" name="periodos[0][horaTermino]" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-block" onclick="removerPeriodo(this)">Remover</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-success mt-2" onclick="adicionarPeriodo()">Adicionar Período</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="salvar_lote" class="btn btn-primary">Salvar</button>
                    <a href="<?php echo HOME_URI; ?>/lotes" class="btn btn-default">Cancelar</a>
                </div>
            </form>
        </div>

        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Setores Permitidos</h3>
            </div>
            <form role="form" action="" method="POST">
                <input type="hidden" name="idLote" value="<?php echo $idLote; ?>">
                <input type="hidden" name="idEvento" value="<?php echo $idEvento; ?>">
                <div class="card-body">
                    <!-- Setores Permitidos com Ativar/Desativar -->
                    <div class="row mt-4">
                        <div class="col-md-12">
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
                                    <div class="col-md-4">
                                        <div class="form-group row align-items-center border p-2 ml-1 mr-1" style="border-radius: 5px; background-color: #f8f9fa; margin-bottom: 10px;">
                                            <label class="col-md-7" style="font-size: 14px; font-weight: bold;"><?php echo $setor['nomeSetor']; ?></label>
                                            <div class="col-md-5 text-right">
                                                <input type="hidden" name="setores[<?php echo $setor['id']; ?>][id]" value="<?php echo $setor['id']; ?>">
                                                <input type="hidden" name="setores[<?php echo $setor['id']; ?>][status]" value="F">
                                                <input type="checkbox" name="setores[<?php echo $setor['id']; ?>][status]" value="T"
                                                    <?php echo $setorSelecionado && $setorSelecionado['status'] == 'T' ? 'checked' : ''; ?>>
                                                <span style="font-size: 12px;">Ativo</span>
                                            </div>
                                        </div>

                                        <!-- Opções adicionais para setores ativos -->
                                        <div class="setor-opcoes" id="opcoes-<?php echo $setor['id']; ?>" style="display: <?php echo $setorSelecionado && $setorSelecionado['status'] == 'T' ? 'block' : 'none'; ?>;">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="permiteSaida-<?php echo $setor['id']; ?>"
                                                        name="setores[<?php echo $setor['id']; ?>][permiteSaida]" value="T"
                                                        <?php echo $setorSelecionado && $setorSelecionado['permiteSaida'] == 1 ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="permiteSaida-<?php echo $setor['id']; ?>">Permite Saída</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="permiteReentrada-<?php echo $setor['id']; ?>"
                                                        name="setores[<?php echo $setor['id']; ?>][permiteReentrada]" value="T"
                                                        <?php echo $setorSelecionado && $setorSelecionado['permiteReentrada'] == 1 ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="permiteReentrada-<?php echo $setor['id']; ?>">Permite Reentrada</label>
                                                </div>
                                            </div>
                                            <div class="form-group tipo-reentrada"
                                                id="tipo-reentrada-<?= $setor['id'] ?>"
                                                style="display: <?= ($setorSelecionado && $setorSelecionado['permiteReentrada'] == 1) ? 'block' : 'none' ?>;">
                                                <label>Tipo de Reentrada</label>
                                                <?php
                                                $sel1 = ($setorSelecionado && $setorSelecionado['tipoReentrada'] == 1) ? 'checked' : '';
                                                $sel2 = ($setorSelecionado && $setorSelecionado['tipoReentrada'] == 2) ? 'checked' : '';
                                                ?>
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        type="radio"
                                                        name="setores[<?= $setor['id'] ?>][tipoReentrada]"
                                                        id="tipoReentrada-<?= $setor['id'] ?>-1"
                                                        value="1"
                                                        <?= $sel1 ?>>
                                                    <label class="form-check-label" for="tipoReentrada-<?= $setor['id'] ?>-1">
                                                        Apenas nesse setor
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        type="radio"
                                                        name="setores[<?= $setor['id'] ?>][tipoReentrada]"
                                                        id="tipoReentrada-<?= $setor['id'] ?>-2"
                                                        value="2"
                                                        <?= $sel2 ?>>
                                                    <label class="form-check-label" for="tipoReentrada-<?= $setor['id'] ?>-2">
                                                        Em todos os setores
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" name="salvar_lote" class="btn btn-primary">Salvar</button>
                    <a href="<?php echo HOME_URI; ?>/lotes/index/lotes" class="btn btn-default">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Scripts para adicionar/remover períodos -->
<script>
    function adicionarPeriodo() {
        const periodos = document.getElementById('periodos');
        const index = periodos.children.length;
        const row = document.createElement('div');
        row.className = 'row periodo-item mb-2';
        row.innerHTML = `
            <div class="col-md-3">
                <label>Data de Início</label>
                <input type="date" class="form-control" name="periodos[${index}][dataInicio]" required>
            </div>
            <div class="col-md-2">
                <label>Hora de Início</label>
                <input type="time" class="form-control" name="periodos[${index}][horaInicio]" required>
            </div>
            <div class="col-md-3">
                <label>Data de Término</label>
                <input type="date" class="form-control" name="periodos[${index}][dataTermino]" required>
            </div>
            <div class="col-md-2">
                <label>Hora de Término</label>
                <input type="time" class="form-control" name="periodos[${index}][horaTermino]" required>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-danger btn-block" onclick="removerPeriodo(this)">Remover</button>
            </div>
        `;
        periodos.appendChild(row);
    }

    function removerPeriodo(button) {
        button.closest('.periodo-item').remove();
    }

    $(document).ready(function() {
        // Inicializa Select2
        $('.select2').select2({
            placeholder: 'Selecione os setores permitidos',
            allowClear: true
        });

        // Mostra/esconde opções de setor quando o checkbox é alterado
        $('input[name^="setores"][name$="[status]"]').change(function() {
            const idSetor = $(this).attr('name').match(/\[(\d+)\]/)[1];
            const opcoesDiv = $(`#opcoes-${idSetor}`);

            if ($(this).is(':checked')) {
                opcoesDiv.show();
            } else {
                opcoesDiv.hide();
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