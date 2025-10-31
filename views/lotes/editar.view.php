<?php
if (!defined('ABSPATH')) exit;

// Recupera o hash do lote (edição)
$hash       = chk_array($parametros, 1);
$idLote     = decryptHash($hash);
$hashEvento = $_SESSION['idEventoHash'];
$idEvento   = decryptHash($hashEvento);

// Processa submissão do formulário
if (isset($_POST['editarLote'])) {
    $modeloLotes->validarFormLotes();
}

// Carrega dados para o formulário
$tiposCredenciais = $modeloLotes->getTiposCredencial();
$tiposCodigos      = $modeloLotes->getTiposCodigo();
$lote              = $modeloLotes->getLote($idLote);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Editar Lote de Credenciais</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HOME_URI; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Editar Lote</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <?= $modeloLotes->form_msg; ?>

    <section class="content">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title mb-0">Dados do Lote</h3>
            </div>
            <form action="" method="POST">
                <div class="card-body">

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="nomeLote"><i class="fas fa-tag mr-1"></i>Nome do Lote</label>
                            <input type="text" id="nomeLote" name="nomeLote" class="form-control form-control-lg" value="<?= htmlentities($lote['nomeLote']); ?>" placeholder="Digite o nome do lote" required>
                        </div>

                        <div class="form-group col-md-3 mb-3">
                            <label for="tipoCodigo"><i class="fas fa-barcode mr-1"></i>Tipo de Código</label>
                            <select id="tipoCodigo" name="tipoCodigo" class="custom-select custom-select-lg" required>
                                <option value="" disabled <?= !$lote['tipoCodigo'] ? 'selected' : ''; ?>>Selecione o tipo de código</option>
                                <?php foreach ($tiposCodigos as $cod): ?>
                                    <option value="<?= htmlspecialchars($cod, ENT_QUOTES); ?>" <?= $lote['tipoCodigo'] === $cod ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($cod, ENT_QUOTES); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3 mb-3">
                            <label for="tipoCredencial"><i class="fas fa-id-badge mr-1"></i>Tipo de Credencial</label>
                            <select id="tipoCredencial" name="tipoCredencial" class="custom-select custom-select-lg" required>
                                <option value="" disabled <?= !$lote['tipoCredencial'] ? 'selected' : ''; ?>>Selecione o tipo de credencial</option>
                                <?php foreach ($tiposCredenciais as $cred): ?>
                                    <option value="<?= htmlspecialchars($cred, ENT_QUOTES); ?>" <?= $lote['tipoCredencial'] === $cred ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($cred, ENT_QUOTES); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="idEvento" value="<?= $idEvento; ?>">

                    <div class="form-group mb-4">
                        <div class="custom-control custom-switch d-inline-block mr-4">
                            <input type="checkbox" class="custom-control-input" id="permiteAcessoFacial" name="permiteAcessoFacial" value="1" <?= $lote['permiteAcessoFacial'] ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="permiteAcessoFacial">Permite Acesso Facial</label>
                        </div>
                        <div class="custom-control custom-switch d-inline-block mr-4">
                            <input type="checkbox" class="custom-control-input" id="permiteImpressao" name="permiteImpressao" value="1" <?= $lote['permiteImpressao'] ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="permiteImpressao">Permite Impressão</label>
                        </div>
                        <div class="custom-control custom-switch d-inline-block">
                            <input type="checkbox" class="custom-control-input" id="permiteDuplicidade" name="permiteDuplicidade" value="1" <?= $lote['permiteDuplicidade'] ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="permiteDuplicidade">Permite Duplicidade</label>
                        </div>
                    </div>

                    <!-- Configurações Avançadas -->
                    <div class="row">
                        <!-- Tempo entre Leitura -->
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header py-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="tempoEntreLeitura" name="tempoEntreLeitura" value="1" <?= $lote['tempoEntreLeitura'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label font-weight-bold" for="tempoEntreLeitura">
                                            <i class="fas fa-clock text-info"></i> Tempo entre Leitura
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body collapse <?= $lote['tempoEntreLeitura'] ? 'show' : ''; ?>" id="tempoEntreLeituraCard">
                                    <div class="form-group mb-0">
                                        <label for="qtdTempoEntreLeitura">Segundos entre Leituras</label>
                                        <input type="number" id="qtdTempoEntreLeitura" name="qtdTempoEntreLeitura" 
                                            class="form-control" min="1" max="60" value="<?= $lote['qtdTempoEntreLeitura'] ?: 5; ?>" placeholder="Ex: 5">
                                        <small class="form-text text-muted">Tempo mínimo entre leituras consecutivas</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Autonumeração -->
                        <div class="col-md-6">
                            <div class="card card-outline card-warning">
                                <div class="card-header py-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="autonumeracao" name="autonumeracao" value="1" <?= $lote['autonumeracao'] ? 'checked' : ''; ?> disabled>
                                        <label class="custom-control-label font-weight-bold" for="autonumeracao">
                                            <i class="fas fa-sort-numeric-up text-warning"></i> Autonumeração
                                            <small class="text-muted">(não editável)</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body collapse <?= $lote['autonumeracao'] ? 'show' : ''; ?>" id="autonumeracaoCard">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="numeroLetras">Formato</label>
                                                <select id="numeroLetras" name="numeroLetras" class="custom-select custom-select-sm" disabled>
                                                    <option value="0" <?= !$lote['numeroLetras'] ? 'selected' : ''; ?>>Somente Números</option>
                                                    <option value="1" <?= $lote['numeroLetras'] ? 'selected' : ''; ?>>Números e Letras</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-0">
                                                <label for="qtdDigitos">Quantidade de Dígitos</label>
                                                <input type="number" id="qtdDigitos" name="qtdDigitos" 
                                                    class="form-control form-control-sm" value="<?= htmlentities($lote['qtdDigitos']); ?>" min="1" max="15" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observacao"><i class="fas fa-sticky-note mr-1"></i>Observação</label>
                        <textarea id="observacao" name="observacao" class="form-control" rows="3" placeholder="Digite uma observação..."><?= htmlentities($lote['observacao']); ?></textarea>
                    </div>

                </div>

                <div class="card-footer text-right">
                    <button type="submit" name="editarLote" class="btn btn-success px-4">Salvar</button>
                    <a href="<?= HOME_URI; ?>/lotes" class="btn btn-outline-secondary px-4 ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#tempoEntreLeitura').change(function() {
            if (this.checked) {
                $('#tempoEntreLeituraCard').collapse('show');
            } else {
                $('#tempoEntreLeituraCard').collapse('hide');
            }
        });
        
        // Autonumeração está desabilitada na edição, então não precisa de evento
    });
</script>