<?php
if (!defined('ABSPATH')) exit;

// --- Lógica PHP Mantida ---
$hash = chk_array($parametros, 1);

if (!empty($hash)) {
    if ($_SESSION['idEventoHash'] != $hash) {
        $_SESSION['idEventoHash'] = $hash;
        $urlSemHash = HOME_URI . '/eventos/index/' . implode('/', array_slice($parametros, 0, 1));
        header("Location: $urlSemHash");
        exit;
    }
} else {
    $hash = $_SESSION['idEventoHash'];
}

$idEvento = decryptHash($_SESSION['idEventoHash']);
$evento = $modelo->getEvento($idEvento);

$logoEvento = $modelo->getAvatar($idEvento);
$bannerEvento = $modelo->getBanner($idEvento);

// Datas e Cálculos
$dataInicio = strtotime(chk_array($evento, 'dataInicio'));
$dataFim = strtotime(chk_array($evento, 'dataFim'));
$diasDuracao = ceil(($dataFim - $dataInicio) / (60 * 60 * 24)) + 1;

// Status e Dados
$status = chk_array($evento, 'statusEvento');
$nomeEvento = chk_array($evento, 'nomeEvento');
$categoria = chk_array($evento, 'nomeCategoria');
$local = chk_array($evento, 'nomeLocal');
$obs = chk_array($evento, 'observacaoEvento');

// Define imagem de fundo (Banner ou cor padrão)
$bgStyle = "";
$txtClass = "text-white";
if (!empty($bannerEvento) && $bannerEvento != 'midia/noBanner.png') {
    $urlBanner = HOME_URI . '/' . $bannerEvento;
    $bgStyle = "background: url('$urlBanner') center center; background-size: cover; height: 300px;";
} else {
    $bgStyle = "background-color: #343a40; height: 150px;"; // Fallback elegante
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Visão Geral do Evento</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>/eventos">Eventos</a></li>
                        <li class="breadcrumb-item active">Perfil</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-widget widget-user">
                        <div class="widget-user-header <?php echo $txtClass; ?>" style="<?php echo $bgStyle; ?>">
                            <h3 class="widget-user-username" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8); font-weight: bold;">
                                <?php echo htmlentities($nomeEvento); ?>
                            </h3>
                            <h5 class="widget-user-desc" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                                <?php echo htmlentities($categoria); ?>
                            </h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="<?php echo HOME_URI . '/' . $logoEvento; ?>" alt="Logo Evento" style="background: #fff; width: 90px; height: 90px; object-fit: contain;">
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><?php echo date('d/m/Y', $dataInicio); ?></h5>
                                        <span class="description-text">INÍCIO</span>
                                    </div>
                                </div>
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><?php echo $diasDuracao; ?> Dias</h5>
                                        <span class="description-text">DURAÇÃO</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header"><?php echo date('d/m/Y', $dataFim); ?></h5>
                                        <span class="description-text">TÉRMINO</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box <?php echo $status == 'T' ? 'bg-success' : 'bg-danger'; ?>">
                        <div class="inner">
                            <h3><?php echo $status == 'T' ? 'Ativo' : 'Inativo'; ?></h3>
                            <p>Status do Evento</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-<?php echo $status == 'T' ? 'check-circle' : 'times-circle'; ?>"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Local</h3>
                            <p><?php echo htmlentities($local); ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Editar</h3>
                            <p>Gerenciar Evento</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <a href="<?php echo HOME_URI; ?>/eventos/index/editar/<?php echo encryptId($idEvento); ?>" class="small-box-footer">Ir para edição <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Sobre o Evento</h3>
                        </div>
                        <div class="card-body">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> Cronograma</strong>
                            <p class="text-muted">
                                O evento ocorrerá entre <b><?php echo date('d/m/Y', $dataInicio); ?></b> e <b><?php echo date('d/m/Y', $dataFim); ?></b>.
                                <?php if(isset($dataInicioCred) && !empty($dataInicioCred)): ?>
                                    <br>
                                    <small>Credenciamento: <?php echo date('d/m/Y', strtotime($dataInicioCred)); ?> até <?php echo date('d/m/Y', strtotime($dataFimCred)); ?></small>
                                <?php endif; ?>
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Localização</strong>
                            <p class="text-muted"><?php echo htmlentities($local); ?></p>

                            <hr>

                            <strong><i class="far fa-file-alt mr-1"></i> Observações</strong>
                            <p class="text-muted">
                                <?php echo !empty($obs) ? nl2br(htmlentities($obs)) : 'Nenhuma observação cadastrada.'; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Dados do Sistema</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td><b>Cadastrado em:</b></td>
                                        <td class="text-right"><span class="badge bg-secondary"><?php echo date('d/m/Y H:i', strtotime(chk_array($evento, 'dataCadastroEvento'))); ?></span></td>
                                    </tr>
                                    <?php if (!empty(chk_array($evento, 'dataEdicaoEvento'))): ?>
                                    <tr>
                                        <td><b>Última edição:</b></td>
                                        <td class="text-right"><span class="badge bg-warning"><?php echo date('d/m/Y H:i', strtotime(chk_array($evento, 'dataEdicaoEvento'))); ?></span></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td><b>ID Sistema:</b></td>
                                        <td class="text-right">#<?php echo $idEvento; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="card-footer">
                           <button class="btn btn-outline-secondary btn-block btn-sm">Ver Log de Alterações</button>
                        </div> -->
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>