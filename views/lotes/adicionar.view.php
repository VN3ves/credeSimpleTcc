<?php
if (!defined('ABSPATH')) exit;

$hash = $_SESSION['idEventoHash'];
$idEvento = decryptHash($hash);

// Se for post, adiciona o lote
if (isset($_POST['adicionarLote'])) {
    $modeloLotes->validarFormLotes();
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Adicionar Lote de Credenciais</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HOME_URI; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Adicionar Lote</li>
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
                            <input type="text" id="nomeLote" name="nomeLote" class="form-control form-control-lg" placeholder="Digite o nome do lote" required>
                        </div>



                        <input type="hidden" name="idEvento" value="<?= $idEvento; ?>">


                        <!-- Tempo entre Leitura -->
                        <div class="form-group col-md-4 mb-3">

                            <label for="qtdTempoEntreLeitura">Segundos entre Leituras</label>
                            <input type="number" id="qtdTempoEntreLeitura" name="qtdTempoEntreLeitura"
                                class="form-control" min="1" max="60" value="5" placeholder="Ex: 5">
                            <small class="form-text text-muted">Tempo mínimo entre leituras consecutivas</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observacao"><i class="fas fa-sticky-note mr-1"></i>Observação</label>
                        <textarea id="observacao" name="observacao" class="form-control" rows="3" placeholder="Digite uma observação..."></textarea>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" name="adicionarLote" class="btn btn-success px-4">Salvar</button>
                    <a href="<?= HOME_URI; ?>/lotes" class="btn btn-outline-secondary px-4 ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</div>