<?php
if (!defined('ABSPATH')) exit;

$hash = $_SESSION['idEventoHash'];
$idEvento = decryptHash($hash);

if (chk_array($this->parametros, 1)) {
    $hashLote = chk_array($this->parametros, 1);
    $idLote = decryptHash($hashLote);
}

// Se for post, adiciona a credencial
if (isset($_POST['adicionarCredencial'])) {
    $modeloCredenciais->validarFormCredenciais();
}

// Carrega o lote
$lote = $modeloLotes->getLote($idLote);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Adicionar Credencial</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HOME_URI; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= HOME_URI; ?>/lotes">Lotes de Credenciais</a></li>
                        <li class="breadcrumb-item"><a href="<?= HOME_URI; ?>/credenciais/index/lotes/<?= $hashLote; ?>">Credenciais do Lote</a></li>
                        <li class="breadcrumb-item active">Adicionar Credencial</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <?= $modeloCredenciais->form_msg; ?>

    <section class="content">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title mb-0">Dados da Credencial</h3>
            </div>
            <form action="" method="POST">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="nomeCredencial"><i class="fas fa-user mr-1"></i>Nome da Credencial</label>
                            <input type="text" id="nomeCredencial" name="nomeCredencial" class="form-control form-control-lg" placeholder="Digite o nome da credencial" required>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="docPessoa"><i class="fas fa-id-card mr-1"></i>Documento</label>
                            <input type="text" id="docPessoa" name="docPessoa" class="form-control form-control-lg" placeholder="Digite o documento" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="codigoCredencial"><i class="fas fa-barcode mr-1"></i>Código da Credencial</label>
                            <input type="text" id="codigoCredencial" name="codigoCredencial" class="form-control form-control-lg" placeholder="Digite o código da credencial" required>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="idPessoa"><i class="fas fa-user-tag mr-1"></i>ID da Pessoa</label>
                            <input type="text" id="idPessoa" name="idPessoa" class="form-control form-control-lg" placeholder="Digite o ID da pessoa">
                        </div>
                    </div>

                    <input type="hidden" name="idEvento" value="<?= $idEvento; ?>">
                    <input type="hidden" name="idLote" value="<?= $idLote; ?>">

                    <div class="form-group mb-4">
                        <label for="detalhes"><i class="fas fa-info-circle mr-1"></i>Detalhes</label>
                        <textarea id="detalhes" name="detalhes" class="form-control" rows="2" placeholder="Digite detalhes adicionais..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="observacao"><i class="fas fa-sticky-note mr-1"></i>Observação</label>
                        <textarea id="observacao" name="observacao" class="form-control" rows="3" placeholder="Digite uma observação..."></textarea>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" name="adicionarCredencial" class="btn btn-success px-4">Salvar</button>
                    <a href="<?= HOME_URI; ?>/credenciais/index/lotes/<?= $hashLote; ?>" class="btn btn-outline-secondary px-4 ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</div>
