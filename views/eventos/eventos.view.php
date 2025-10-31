<?php
if (!defined('ABSPATH')) exit;

if (chk_array($this->parametros, 0) == 'bloquear') {
	$modelo->bloquearEvento();
}

if (chk_array($this->parametros, 0) == 'desbloquear') {
	$modelo->desbloquearEvento();
}


$categorias = $modeloCategorias->getCategorias();

$dataInicioFim = isset($_GET["dataInicioFim"]) ? $_GET["dataInicioFim"] : null;
$categoria = isset($_GET["categoria"]) ? $_GET["categoria"] : null;
$q = isset($_GET["q"]) ? $_GET["q"] : null;
$status = isset($_GET["status"]) ? $_GET["status"] : null;

$filtros = array('dataInicioFim' => $dataInicioFim, 'categoria' => $categoria, 'q' => $q, 'status' => $status);

$resultado = $modelo->getEventos($filtros);
$eventos = $resultado['eventos'];
$total_records = $resultado['total'];
$total_pages = $resultado['pages'];
$page = $resultado['current_page'];
$start = ($page - 1) * 20;

?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Eventos</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Dashboard</a></li>
						<li class="breadcrumb-item active"><a href="<?php echo HOME_URI; ?>/eventos">Eventos</a></li>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="card card-secondary">
			<div class="card-header">
				<h3 class="card-title">Pesquisa</h3>
			</div>
			<form role="form" action="" method="GET" enctype="multipart/form-data">
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicioFim">Período</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-calendar"></i></span>
									</div>
									<input type="text" class="form-control" id="dataInicioFim" name="dataInicioFim" value="<?php echo $dataInicioFim; ?>">
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="categoria">Categoria</label>
								<select class="form-control select2" name="categoria" id="categoria">
									<option value="">Todas</option>
									<?php foreach ($categorias as $cat): ?>
										<option value="<?php echo $cat['id']; ?>" <?php echo ($categoria == $cat['id']) ? 'selected' : ''; ?>>
											<?php echo $cat['nomeCategoria']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>


						<div class="col-md-4">
							<div class="form-group">
								<label for="q">Busca por texto</label>
								<input type="text" class="form-control" placeholder="Digite aqui..." name="q" id="q" value="<?php echo $q; ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<button type="submit" class="btn btn-success">Buscar</button>
					<a href="<?php echo HOME_URI; ?>/eventos" class="btn btn-primary">Limpar</a>
				</div>
			</form>
		</div>

		<div class="card card-secondary">
			<div class="card-header">
				<h3 class="card-title">Eventos Cadastrados</h3>
			</div>
			<div class="card-body">
				<?php echo $modelo->form_msg; ?>

				<div class="table-responsive">
					<table id="table" class="table table-hover table-bordered table-striped">
						<thead>
							<tr>
								<th>Evento</th>
								<th>Local</th>
								<th>Período</th>
								<th>Categoria</th>
								<th>Status</th>
								<th>Opções</th>
							</tr>
						</thead>

						<tbody>
							<?php 
							if (!empty($eventos)):
								foreach ($eventos as $evento):
									$permiteEditar = $evento['aprovado'] == 'T' ? true : false;
							?>
								<tr>
									<td>
										<a href="<?php echo HOME_URI; ?>/eventos/index/perfil/<?php echo encryptId($evento['idEvento']); ?>">
											<?php echo $evento['nomeEvento']; ?>
										</a>
									</td>

									<td><?php echo $evento['nomeLocal']; ?></td>

									<td>
										<?php
										if (!empty($evento['dataInicio'])) {
											$dataInicial = explode("-", $evento['dataInicio']);
											$dataFinal = explode("-", $evento['dataFim']);

											if ($evento['dataInicio'] == $evento['dataFim']) {
												echo $dataInicial[2] . " " . mesAbreviado($dataInicial[1]) . "/" . $dataInicial[0];
											} else {
												echo $dataInicial[2] . " " . mesAbreviado($dataInicial[1]) . "/" . $dataInicial[0] . " - " .
													$dataFinal[2] . " " . mesAbreviado($dataFinal[1]) . "/" . $dataFinal[0];
											}
										}
										?>
									</td>

									<td><?php echo $evento['nomeCategoria']; ?></td>

									<td>
										<?php
										if ($evento['aprovado'] == 'F') {
											echo '<span class="badge badge-danger">Não Aprovado</span>';
										} elseif ($evento['statusEvento'] == 'T') {
											echo '<span class="badge badge-success">Ativo</span>';
										} else {
											echo '<span class="badge badge-danger">Inativo</span>';
										}
										?>
									</td>

									<td>
										<?php if ($permiteEditar) { ?>
											<a href="<?php echo HOME_URI; ?>/eventos/index/editar/<?php echo encryptId($evento['idEvento']); ?>"
												class="btn btn-sm btn-info" title="Editar">
												<i class="fas fa-edit"></i>
											</a>

											<?php if ($evento['statusEvento'] == 'T') { ?>
												<a href="<?php echo HOME_URI; ?>/eventos/index/bloquear/<?php echo encryptId($evento['idEvento']); ?>"
													class="btn btn-sm btn-success" title="Bloquear">
													<i class="fas fa-unlock"></i>
												</a>
											<?php } else { ?>
												<a href="<?php echo HOME_URI; ?>/eventos/index/desbloquear/<?php echo encryptId($evento['idEvento']); ?>"
													class="btn btn-sm btn-danger" title="Desbloquear">
													<i class="fas fa-lock"></i>
												</a>
											<?php } ?>
										<?php } ?>
									</td>
								</tr>
							<?php 
								endforeach;
							else:
							?>
								<tr>
									<td colspan="7" class="text-center">Nenhum evento encontrado</td>
								</tr>
							<?php endif; ?>
						</tbody>
						<tfoot>
							<tr>
								<th>Evento</th>
								<th>Local</th>
								<th>Período</th>
								<th>Categoria</th>
								<th>Status</th>
								<th>Opções</th>
							</tr>
						</tfoot>
					</table>
				</div>

				<?php if ($total_pages > 1): ?>
					<div class="row">
						<div class="col-sm-12 col-md-5">
							<div class="dataTables_info">
								Mostrando <?php echo $start + 1; ?> até <?php echo min($start + 20, $total_records); ?> de <?php echo $total_records; ?> registros
							</div>
						</div>
						<div class="col-sm-12 col-md-7">
							<div class="dataTables_paginate paging_simple_numbers">
								<ul class="pagination">
									<?php for ($i = 1; $i <= $total_pages; $i++): ?>
										<li class="paginate_button page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
											<a href="?page=<?php echo $i; ?><?php echo $queryString; ?>" class="page-link"><?php echo $i; ?></a>
										</li>
									<?php endfor; ?>
								</ul>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="card-footer">
				<a href="<?php echo HOME_URI; ?>/eventos/index/adicionar" class="btn btn-danger btn-lg">Adicionar Evento</a>
			</div>
		</div>
	</section>
</div>

<script>
	$(document).ready(function() {
		$('#dataInicio').datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			templates: {
				leftArrow: '<i class="fa fa-chevron-left"></i>',
				rightArrow: '<i class="fa fa-chevron-right"></i>'
			}
		});

		$('#categoria').select2({
			theme: "classic"
		});
	});;
</script>

<style>
	/* Aplicando estilos comuns para os select2 containers */
	#categoria+.select2-container {
		width: 100% !important;
	}

	#categoria+.select2-container .select2-selection--single {
		height: calc(2.25rem + 2px);
		/* Ajuste para altura do input */
	}

	#categoria+.select2-container .select2-selection__rendered {
		line-height: calc(2.25rem);
		/* Centralizar texto */
	}

	#categoria+.select2-container .select2-selection__arrow {
		height: calc(2.25rem);
		/* Ajuste para ícone da seta */
	}
</style>