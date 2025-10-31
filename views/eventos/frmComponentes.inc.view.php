<?php
if (!defined('ABSPATH')) exit;

$idEvento = decryptHash(chk_array($parametros, 1));
$evento = $modelo->getEvento($idEvento);

$selectedTerminals = [];
if (!empty($componente['marcaTerminal'])) {
    $selectedTerminals = explode(',', trim($componente['marcaTerminal'], '{}'));
}

$selectedLeitors = [];
if (!empty($componente['marcaLeitor'])) {
    $selectedLeitors = explode(',', trim($componente['marcaLeitor'], '{}'));
}

// opções disponíveis
$leitorOptions   = ['controlId' => 'ControlId', /* adicione outras se houver */];

?>

<form role="form" action="" method="POST" enctype="multipart/form-data">
    <div class="card card-secondary shadow-sm">
        <div class="card-header text-white">
            <h3 class="card-title"><i class="fas fa-cogs mr-2"></i> Configurações do Evento</h3>
        </div>

        <div class="card-body">
            <?php echo $modeloComponentes->form_msg; ?>

            <!-- Recursos do Evento -->
            <h5 class="mt-0 mb-3 text-secondary"><i class="fas fa-tools mr-1"></i> Recursos do Evento</h5>
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="qtdSetores">Quantidade de Setores</label>
                        <input type="number" class="form-control" id="qtdSetores" name="qtdSetores"
                            value="<?php echo htmlentities(chk_array($componente, 'qtdSetores')); ?>" min="0">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="qtdLotesCredenciais">Quantidade de Lotes de Credenciais</label>
                        <input type="number" class="form-control" id="qtdLotesCredenciais" name="qtdLotesCredenciais"
                            value="<?php echo htmlentities(chk_array($componente, 'qtdLotesCredenciais')); ?>" min="0">
                    </div>
                </div>
            </div>

            <!-- Terminais -->
            <h5 class="mt-0 mb-3 text-secondary"><i class="fas fa-credit-card mr-1"></i> Terminais</h5>
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="qtdTerminais">Quantidade de Terminais</label>
                        <input type="number" class="form-control" id="qtdTerminais" name="qtdTerminais"
                            value="<?php echo htmlentities(chk_array($componente, 'qtdTerminais')); ?>" min="0">
                    </div>
                </div>

 

            </div>

            <!-- Leitores Faciais -->
            <h5 class="mb-3 text-secondary"><i class="fas fa-camera mr-1"></i> Leitores Faciais</h5>
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label>Usa Leitores Faciais?</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="usaLeitorFacial" name="usaLeitorFacial"
                                <?php echo (chk_array($componente, 'usaLeitorFacial') == 'S') ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="usaLeitorFacial">Sim</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="qtdLeitoresFaciais">Quantidade de Leitores Faciais</label>
                        <input type="number" class="form-control" id="qtdLeitoresFaciais" name="qtdLeitoresFaciais"
                            value="<?php echo htmlentities(chk_array($componente, 'qtdLeitoresFaciais')); ?>" min="0">
                    </div>
                </div>

                <!-- <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="marcaLeitor">Marca do Leitor</label>
                        <select class="form-control" id="marcaLeitor" name="marcaLeitor">
                            <option value="controlId" <?php echo (chk_array($componente, 'marcaLeitor') == 'controlId') ? 'selected' : ''; ?>>ControlId</option>
                        </select>
                    </div>
                </div> -->

                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="marcaLeitor">Marca(s) do Leitor</label>
                        <select multiple class="form-control" id="marcaLeitor_select">
                            <?php foreach ($leitorOptions as $value => $label): ?>
                                <option value="<?= $value ?>"
                                    <?= in_array($value, $selectedLeitors) ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" id="marcaLeitor" name="marcaLeitor"
                            value="<?php echo htmlentities(chk_array($componente, 'marcaLeitor')); ?>">
                    </div>
                </div>
            </div>

            <!-- Credenciais -->
            <h5 class="mb-3 text-secondary"><i class="fas fa-ticket-alt mr-1"></i> Credenciais e Estoque</h5>
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label>Credenciais Impressas?</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="credenciaisImpressas" name="credenciaisImpressas"
                                <?php echo (chk_array($componente, 'credenciaisImpressas') == 'S') ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="credenciaisImpressas">Sim</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light text-right">
                <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Salvar</button>
            </div>
        </div>
</form>

<script>
    $(document).ready(function() {
        // Toggle quantidade de leitores faciais
        $('#usaLeitorFacial').change(function() {
            $('#qtdLeitoresFaciais').prop('disabled', !this.checked);
            $('#marcaLeitor_select').prop('disabled', !this.checked);
            if (!this.checked) {
                $('#qtdLeitoresFaciais').val('0');
                $('#marcaLeitor_select').val('');
            }
        });

        // Inicializar estados dos campos
        $('#qtdLeitoresFaciais').prop('disabled', !$('#usaLeitorFacial').is(':checked'));
        $('#marcaLeitor_select').prop('disabled', !$('#usaLeitorFacial').is(':checked'));
    });

    $(function() {
        function buildBraceString(arr) {
            return '{' + arr.join(',') + '}';
        }

        // a cada submissão, monta as strings e atualiza os inputs ocultos
        $('form').on('submit', function() {
            let leits = $('#marcaLeitor_select').val() || [];
            $('#marcaLeitor').val(buildBraceString(leits));
        });
    });
</script>

<style>
    #marcaLeitor_select {
        max-height: calc(1.5em + .75rem + 2px);
        /* mesma altura de .form-control padrão */
        overflow-y: auto;
    }


    .custom-switch {
        padding-left: 2.5rem;
    }

    .custom-control-label {
        padding-top: 3px;
        cursor: pointer;
    }

    .card {
        border-radius: 0.5rem;
    }

    .card-header {
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-footer {
        border-top: none;
        padding: 1rem 1.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    h5.text-secondary {
        font-weight: 500;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.5rem;
    }

    .btn-primary {
        padding: 0.5rem 1.5rem;
        font-weight: 500;
    }
</style>