<?php
if (!defined('ABSPATH')) exit;

if (
	chk_array($this->parametros, 0) == 'adicionar' ||
	chk_array($this->parametros, 0) == 'editar'
) {
	if (chk_array($this->parametros, 2) == 'componentes') {
		$modeloComponentes->validarFormComponentes();
	}else{
		$modelo->validarFormEventos();
	}

	$categorias = $modeloCategorias->getCategorias();
	$locais = $modeloLocais->getLocais();
}

if (chk_array($this->parametros, 0) == 'editar') {
	$idEvento = decryptHash(chk_array($parametros, 1));
	$evento = $modelo->getEvento($idEvento);
	$local = $modeloLocais->getLocal($evento['idLocal']);
	$componente = $modeloComponentes->getComponente($idEvento);
} else {
	$local = [];
	$componente = [];
}

$dataHoraCriacao = explode(" ", chk_array($modelo->form_data, 'dataCriacao'));
$dataCriacao = explode("-", $dataHoraCriacao[0]);

?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1><?php echo (chk_array($this->parametros, 0) == 'editar') ? 'Editar' : 'Adicionar'; ?> Evento</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>/eventos">Eventos</a></li>
						<?php if (chk_array($this->parametros, 0) == 'editar') { ?>
							<li class="breadcrumb-item active"><a href="<?php echo HOME_URI; ?>/eventos/index/editar/<?php echo chk_array($parametros, 1); ?>">Editar</a></li>
						<?php } else if (chk_array($this->parametros, 0) == 'adicionar') { ?>
							<li class="breadcrumb-item active"><a href="<?php echo HOME_URI; ?>/eventos/index/adicionar">Adicionar</a></li>
						<?php } ?>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-lg-4 col-xs-4">
				<div class="small-box <?php if (chk_array($this->parametros, 0) == 'adicionar') {
											echo "bg-red";
										} elseif (chk_array($this->parametros, 0) == 'editar' && (chk_array($this->parametros, 2) != 'componentes')) {
											echo "bg-red";
										} else {
											echo "bg-gray";
										} ?>">
					<div class="inner">
						<h3>01</h3>

						<p>Evento</p>
					</div>
					<div class="icon">
						<i class="fas fa-calendar-plus"></i>
					</div>
					<?php if (chk_array($this->parametros, 0) == 'editar') { ?>
						<a href="<?php echo HOME_URI; ?>/eventos/index/editar/<?php echo chk_array($parametros, 1); ?>" class="small-box-footer">Mostrar <i class="fas fa-arrow-circle-right"></i></a>
					<?php } ?>
				</div>
			</div>

			<div class="col-lg-4 col-xs-4">
				<div class="small-box <?php if (chk_array($this->parametros, 0) == 'adicionar') {
											echo "bg-gray";
										} elseif (chk_array($this->parametros, 0) == 'editar' && chk_array($this->parametros, 2) == 'componentes') {
											echo "bg-red";
										} else {
											echo "bg-gray";
										} ?>">
					<div class="inner">
						<h3>02</h3>

						<p>Componentes</p>
					</div>
					<div class="icon">
						<i class="fas fa-cogs"></i>
					</div>
					<?php if (chk_array($this->parametros, 0) == 'editar') { ?>
						<a href="<?php echo HOME_URI; ?>/eventos/index/editar/<?php echo chk_array($parametros, 1); ?>/componentes" class="small-box-footer">Mostrar <i class="fas fa-arrow-circle-right"></i></a>
					<?php } ?>
				</div>
			</div>


			<!-- <div class="col-lg-4 col-xs-4">
				<div class="small-box <?php if (chk_array($this->parametros, 0) == 'adicionar') {
											echo "bg-gray";
										} elseif (chk_array($this->parametros, 0) == 'editar' && chk_array($this->parametros, 2) == 'lideres') {
											echo "bg-red";
										} else {
											echo "bg-gray";
										} ?>">
					<div class="inner">
						<h3>02</h3>

						<p>Líderes</p>
					</div>
					<div class="icon">
						<i class="fas fa-user-circle"></i>
					</div>
					<?php if (chk_array($this->parametros, 0) == 'editar') { ?>
						<a href="<?php echo HOME_URI; ?>/eventos/index/editar/<?php echo chk_array($parametros, 1); ?>/lideres" class="small-box-footer">Mostrar <i class="fas fa-arrow-circle-right"></i></a>
					<?php } ?>
				</div>
			</div>

			<div class="col-lg-4 col-xs-4">
				<div class="small-box <?php if (chk_array($this->parametros, 0) == 'adicionar') {
											echo "bg-gray";
										} elseif (chk_array($this->parametros, 0) == 'editar' && chk_array($this->parametros, 2) == 'fases') {
											echo "bg-red";
										} else {
											echo "bg-gray";
										} ?>">
					<div class="inner">
						<h3>03</h3>

						<p>Fases e Mão de Obra</p>
					</div>
					<div class="icon">
						<i class="fas fa-users"></i>
					</div>
					<?php if (chk_array($this->parametros, 0) == 'editar') { ?>
						<a href="<?php echo HOME_URI; ?>/eventos/index/editar/<?php echo chk_array($parametros, 1); ?>/fases" class="small-box-footer">Mostrar <i class="fas fa-arrow-circle-right"></i></a>
					<?php } ?>
				</div>
			</div> -->

		</div>

		<?php
		if (chk_array($this->parametros, 0) == 'adicionar') {
			require ABSPATH . '/views/eventos/frmEvento.inc.view.php';
		} elseif (chk_array($this->parametros, 0) == 'editar') {
			if (chk_array($this->parametros, 2) == 'lideres') {
				require ABSPATH . '/views/eventos/frmLideres.inc.view.php';
			} elseif (chk_array($this->parametros, 2) == 'fases') {
				require ABSPATH . '/views/eventos/frmFases.inc.view.php';
			} elseif (chk_array($this->parametros, 2) == 'componentes') {
				require ABSPATH . '/views/eventos/frmComponentes.inc.view.php';
			} else {
				require ABSPATH . '/views/eventos/frmEvento.inc.view.php';
			}
		}
		?>
	</section>
</div>