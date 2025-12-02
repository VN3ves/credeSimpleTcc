<?php
if (!defined('ABSPATH')) exit;

$activePage = isset($_GET['path']) ? explode('/', $_GET['path']) : null;

$painelUsuarios = $this->load_model('usuarios/usuarios');

$usuario = $painelUsuarios->getUsuario($_SESSION['userdata']['id']);
$permissao = $usuario['permissao'];

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $this->title; ?></title>

	<base href="<?php echo HOME_URI; ?>/">

	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/plugins/sweetalert/css/sweetalert2.min.css">
	<script src="<?php echo HOME_URI; ?>/views/standards/plugins/sweetalert/js/sweetalert2.all.min.js"></script>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte//plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte/css/adminlte.min.css">

	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/select2/css/select2.min.css">
	<!-- CSS do jQuery File Upload -->
	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/css/jquery.fileupload.css">
	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/css/jquery.fileupload-ui.css">
	<!-- Datepicker CSS -->
	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">

	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/jquery/jquery.min.js"></script>

	<!-- Toastr -->
	<link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/toastr/toastr.min.css">
</head>

<style>
	.dataTables_wrapper .dataTables_filter {
		float: right;
		text-align: right;
		padding: 10px 0;
	}

	.dataTables_wrapper .dataTables_filter input {
		margin-left: 0.5em;
		display: inline-block;
		width: auto;
	}

	.card-body {
		padding: 1.25rem;
	}

	.dataTable {
		width: 100% !important;
	}
</style>

<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>

			<!-- SELECT PARA SELECIONAR UM EVENTO -->
			<?php
			$idEventoHash = isset($_SESSION['idEventoHash']) ? $_SESSION['idEventoHash'] : null;
			$idEvento = 0;
			$modeloEvento = $this->load_model('eventos/eventos');
			$filtros = array('status' => 'T');
			$eventos = $modeloEvento->getEventos($filtros);
			$eventos = $eventos['eventos'];

			if ($idEventoHash !== null) {
				$idEvento = decryptHash($idEventoHash);
			?>

				<div class="form-inline mx-2">

					<label class="mr-2" for="selectEvento">Evento Atual:</label>
					<select class="form-control select2-search" id="selectEvento" name="selectEvento" style="width: 250px;">
						<option value="0">Nenhum evento selecionado</option>
						<?php foreach ($eventos as $evento) :
						?>

							<option value="<?= $evento['idEvento'] ?>" <?= $evento['idEvento'] == $idEvento ? 'selected' : '' ?>>
								<?= $evento['nomeEvento'] ?>
							</option>

						<?php endforeach; ?>
					</select>

				</div>
			<?php } else { ?>
				<div class="form-inline mx-2">
					<label class="mr-2" for="selectEvento">Selecione um Evento:</label>
					<select class="form-control select2-search" id="selectEvento" name="selectEvento" style="width: 250px;">
						<option value="0" selected>Nenhum evento selecionado</option>
						<?php
						foreach ($eventos as $evento) : ?>
							<option value="<?= $evento['idEvento'] ?>">

								<?= $evento['nomeEvento'] ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php } ?>


			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" data-widget="fullscreen" href="#" role="button">
						<i class="fas fa-expand-arrows-alt"></i>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo HOME_URI; ?>/logout" role="button" alt="Sair">
						<i class="fas fa-sign-out-alt"></i>
					</a>
				</li>
			</ul>
		</nav>

		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<a href="./" class="brand-link text-center">
				<span class="brand-text font-weight-light"><?php echo SYS_NAME; ?></span>
			</a>

			<?php
			$modeloPessoas = $this->loadDefaultModel('PessoasModel');
			?>

			<div class="sidebar">
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img

							src="<?php echo HOME_URI . '/' . $modeloPessoas->getAvatar($_SESSION['userdata']['id'], false); ?>"
							class="img-circle elevation-2"
							alt="User Image"
							style="width: 35px; height: 35px; object-fit: cover;">
					</div>
					<div class="info">
						<a href="<?php echo HOME_URI; ?>/pessoas/index/perfil" class="d-block text-center"><?php echo chk_array($this->userdata, 'nome'); ?> <?php echo chk_array($this->userdata, 'sobrenome'); ?></a>
					</div>
				</div>

				<div class="form-inline">
					<div class="input-group" data-widget="sidebar-search">
						<input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Buscar">
						<div class="input-group-append">
							<button class="btn btn-sidebar">
								<i class="fas fa-search fa-fw"></i>
							</button>
						</div>
					</div>
				</div>

				<?php require_once ABSPATH . '/views/painel/sidebar.view.php'; ?>

			</div>
		</aside>

		<?php
		require $conteudo;
		?>

		<aside class="control-sidebar control-sidebar-dark"></aside>

		<footer class="main-footer">
			<strong><?php echo SYS_NAME; ?> &copy; <?php echo SYS_YEAR; ?> - <a href="<?php echo SYS_COPYRIGHT_URL; ?>"><?php echo SYS_COPYRIGHT; ?></a></strong> - Todos direitos reservados.
			<div class="float-right d-none d-sm-inline-block">
				<b>Versão</b> <?php echo SYS_VERSION; ?>
			</div>
		</footer>
	</div>

	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/jszip/jszip.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

	<!-- jQuery UI depois -->
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/jquery-ui/jquery-ui.min.js"></script>

	<!-- Widget factory do jQuery UI (CRUCIAL) -->
	<script src="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/js/vendor/jquery.ui.widget.js"></script>

	<!-- Depois os scripts do File Upload na ordem correta -->
	<script src="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/js/jquery.iframe-transport.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/js/jquery.fileupload.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/js/jquery.fileupload-process.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/js/jquery.fileupload-image.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/js/jquery.fileupload-validate.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/plugins/fileupload/js/jquery.fileupload-ui.js"></script>
	<!-- Datepicker JS -->
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/moment/moment-with-locales.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/select2/js/select2.full.min.js"></script>

	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/inputmask/jquery.inputmask.min.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/javascripts/jquery.priceformat.min.js"></script>

	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/js/adminlte.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/javascripts/cep.js"></script>
	<script src="<?php echo HOME_URI; ?>/views/standards/javascripts/app.js"></script>

	<!-- Toastr -->
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/toastr/toastr.min.js"></script>

	<!-- ChartJS -->
	<script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/chart.js/Chart.min.js"></script>

	<script>
		$(document).ready(function() {

			// Inicializa o Select2 no campo de evento
			$('#selectEvento').select2({
				theme: "classic"

			});

			$('#selectEvento').change(function() {
				var idEvento = $(this).val();
				var nomeDB = $('#selectEvento option:selected').data('nome-db');
				console.log(nomeDB);
				$.ajax({
					url: '<?php echo HOME_URI; ?>/eventos/index/setEvento',
					type: 'POST',
					data: {

						idEvento: idEvento,
						nomeDB: nomeDB
					},
					success: function(data) {
						location.reload();
					}

				});
			});
		});
	</script>

</body>

</html>