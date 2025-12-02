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
                        <div class="form-group col-md-8 mb-3">
                            <label for="nomeLote"><i class="fas fa-tag mr-1"></i>Nome do Lote</label>
                            <input type="text" id="nomeLote" name="nomeLote" class="form-control form-control-lg" value="<?= htmlentities($lote['nomeLote']); ?>" placeholder="Digite o nome do lote" required>
                        </div>

                        <!-- Tempo entre Leitura -->
                        <div class="form-group col-md-4 mb-3">
                            <label for="qtdTempoEntreLeitura">Segundos entre Leituras</label>
                            <input type="number" id="qtdTempoEntreLeitura" name="qtdTempoEntreLeitura"
                                class="form-control" min="1" max="60" value="<?= $lote['qtdTempoEntreLeitura'] ?: 5; ?>" placeholder="Ex: 5">
                            <small class="form-text text-muted">Tempo mínimo entre leituras consecutivas</small>
                        </div>

                    </div>
                    <input type="hidden" name="idEvento" value="<?= $idEvento; ?>">
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