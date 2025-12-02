<?php
require_once ABSPATH . '/views/eventos/mini-perfil.inc.view.php';
?>


<form role="form" action="" method="POST" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-12">
			<div class="card card-secondary">
				<div class="card-header">
					<i class="fas fa-id-card ml-3"></i>
					<h3 class="card-title">Identificação</h3>
				</div>

				<div class="card-body">
					<?php
					echo $modelo->form_msg;
					?>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="nomeEvento">Nome do Evento</label>
								<input type="text" class="form-control" id="nomeEvento" name="nomeEvento" value="<?php echo htmlentities(chk_array($modelo->form_data, 'nomeEvento')); ?>">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="idCategoria">Categoria do Evento</label>
								<select class="form-control select2" name="idCategoria" id="idCategoria">
									<option value="">Escolha uma opção</option>
									<?php
									foreach ($categorias as $categoria):
										echo "<option value='" . $categoria['id'] . "' ";
										if (htmlentities(chk_array($modelo->form_data, 'idCategoria')) == $categoria['id']) {
											echo 'selected';
										}
										echo ">" . $categoria['nomeCategoria'] . "</option>";
									endforeach;
									?>
								</select>
							</div>
						</div>


					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Início Credenciamento:</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-calendar"></i></span>
									</div>
									<input type="date" class="form-control" name="dataInicioCredenciamento" value="<?php echo chk_array($modelo->form_data, 'dataInicioCredenciamento'); ?>">
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Data Fim Credenciamento:</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-calendar"></i></span>
									</div>
									<input type="date" class="form-control" name="dataFimCredenciamento" value="<?php echo chk_array($modelo->form_data, 'dataFimCredenciamento'); ?>">
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Data Início Evento:</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-calendar"></i></span>
									</div>
									<input type="date" class="form-control" name="dataInicio" value="<?php echo chk_array($modelo->form_data, 'dataInicio'); ?>">
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Data Fim Evento:</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-calendar"></i></span>
									</div>
									<input type="date" class="form-control" name="dataFim" value="<?php echo chk_array($modelo->form_data, 'dataFim'); ?>">
								</div>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="observacao">Observações</label>
								<textarea class="form-control" id="observacao" name="observacao" rows="3"><?php echo htmlentities(chk_array($modelo->form_data, 'observacaoEvento')); ?></textarea>
							</div>
						</div>
					</div>

					<div class="row" id="licencaDiv">
						<div class="col-md-12">
							<div class="form-group">
								<label for="licenca">Licença</label>
								<textarea class="form-control" id="licenca" name="licenca" rows="3" readonly><?php echo htmlentities(chk_array($modelo->form_data, 'licenca')); ?></textarea>
							</div>
						</div>
					</div>

					<!-- Campos hidden para dados do local -->
					<input type="hidden" name="local[nomeOficial]" id="hidden_nomeOficial" value="<?php echo htmlentities(chk_array($local, 'nomeOficial')); ?>">
					<input type="hidden" name="local[cep]" id="hidden_cep" value="<?php echo htmlentities(chk_array($local, 'cep')); ?>">
					<input type="hidden" name="local[logradouro]" id="hidden_logradouro" value="<?php echo htmlentities(chk_array($local, 'logradouro')); ?>">
					<input type="hidden" name="local[numero]" id="hidden_numero" value="<?php echo htmlentities(chk_array($local, 'numero')); ?>">
					<input type="hidden" name="local[complemento]" id="hidden_complemento" value="<?php echo htmlentities(chk_array($local, 'complemento')); ?>">
					<input type="hidden" name="local[bairro]" id="hidden_bairro" value="<?php echo htmlentities(chk_array($local, 'bairro')); ?>">
					<input type="hidden" name="local[cidade]" id="hidden_cidade" value="<?php echo htmlentities(chk_array($local, 'cidade')); ?>">
					<input type="hidden" name="local[estado]" id="hidden_estado" value="<?php echo htmlentities(chk_array($local, 'estado')); ?>">
					<input type="hidden" name="local[latitude]" id="hidden_latitude" value="<?php echo htmlentities(chk_array($local, 'latitude')); ?>">
					<input type="hidden" name="local[longitude]" id="hidden_longitude" value="<?php echo htmlentities(chk_array($local, 'longitude')); ?>">

				</div>
			</div>
		</div>
	</div>

	<?php if (chk_array($this->parametros, 0) == 'editar') { ?>
		<div class="row">
			<div class="col-md-12">
				<div class="card card-secondary">
					<div class="card-header">
						<i class="fas fa-images ml-3"></i>
						<h3 class="card-title">Imagens</h3>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="logo">Logo do Evento</label>
									<div id="upload-container-logo">
										<span class="btn btn-success fileinput-button">
											<i class="fas fa-plus"></i>
											<span>Selecionar logo...</span>
											<input id="fileupload-logo" type="file" name="midia" accept="image/*">
										</span>
									</div>
									<div id="preview-logo" class="mt-3"></div>
									<p class="help-block">Formatos aceitos: JPG, PNG e GIF</p>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="banner">Banner do Evento</label>
									<div id="upload-container-banner">
										<span class="btn btn-success fileinput-button">
											<i class="fas fa-plus"></i>
											<span>Selecionar banner...</span>
											<input id="fileupload-banner" type="file" name="midia" accept="image/*">
										</span>
									</div>
									<div id="preview-banner" class="mt-3"></div>
									<p class="help-block">Formatos aceitos: JPG, PNG e GIF</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<div class="row">
		<div class="col-md-6">
			<div class="card card-secondary">
				<div class="card-header">
					<i class="fas fa-map-marker-alt ml-3"></i>
					<h3 class="card-title">Localização</h3>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" id="btnNovoLocal">
							<i class="fas fa-plus"></i> Novo Local
						</button>
					</div>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="idLocal">Local</label>
								<select class="form-control select2" name="idLocal" id="idLocal">
									<option value="">Escolha</option>
									<?php
									foreach ($locais as $local1):
										echo "<option value='" . $local1['id'] . "' ";
										if (htmlentities(chk_array($modelo->form_data, 'idLocal')) == $local1['id']) {
											echo 'selected';
										}
										echo ">" . $local1['nomeOficial'] . "</option>";
									endforeach;
									?>
								</select>
							</div>
						</div>

						<div class="col-md-8">
							<div class="form-group">
								<label>Nome Oficial</label>
								<input type="text" class="form-control" id="nomeOficial" readonly value="<?php echo chk_array($local, 'nomeOficial'); ?>">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<label>CEP</label>
							<div class="input-group">
								<input type="text" class="form-control" id="cep" readonly="readonly" value="<?php echo chk_array($local, 'cep'); ?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Logradouro</label>
								<input type="text" class="form-control" id="logradouro" readonly="readonly" value="<?php echo chk_array($local, 'logradouro'); ?>">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Número</label>
								<input type="text" class="form-control" id="numero" readonly="readonly" value="<?php echo chk_array($local, 'numero'); ?>">
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-4">
							<label>Bairro</label>
							<div class="input-group">
								<input type="text" class="form-control" id="bairro" readonly="readonly" value="<?php echo chk_array($local, 'bairro'); ?>">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Cidade</label>
								<input type="text" class="form-control" id="cidade" readonly="readonly" value="<?php echo chk_array($local, 'cidade'); ?>">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Estado</label>
								<input type="text" class="form-control" id="estado" readonly="readonly" value="<?php echo chk_array($local, 'estado'); ?>">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Complemento</label>
								<input type="text" class="form-control" id="complemento" readonly="readonly" value="<?php echo chk_array($local, 'complemento'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>

			</div>
		</div>

		<div class="col-md-6">
			<div class="card card-secondary">
				<div class="card-header">
					<i class="fas fa-map ml-3"></i>
					<h3 class="card-title">Google Map</h3>
				</div>

				<div class="card-body">
					<?php
					$latitude = !empty(chk_array($local, 'latitude')) ? chk_array($local, 'latitude') : SYS_LAT;
					$longitude = !empty(chk_array($local, 'longitude')) ? chk_array($local, 'longitude') : SYS_LNG;
					?>
					<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyDqITRk0Rt9D7RsFR3spL9r_HiEupKEcY4&amp;"></script>
					<script type="text/javascript">
						var map;
						var infoWindow;
						var marker;
						var markersData = new Array();

						markersData = [
							<?php
							echo "
											{
												lat: " . $latitude . ",
												lng: " . $longitude . ",
												cep: '" . chk_array($local, 'cep') . "',
												endereco: '" . chk_array($local, 'logradouro') . ", " . chk_array($local, 'numero') . "',
												bairro: '" . chk_array($local, 'bairro') . "',
												cidade: '" . chk_array($local, 'cidade') . "',
												estado: '" . chk_array($local, 'estado') . "'
											}";
							?>
						];

						function createMarker(latlng, cep, endereco, bairro, cidade, estado) {
							marker = new google.maps.Marker({
								map: map,
								position: latlng
							});

							var iwContent = '<div>' + '<div>' + endereco + '<br />' + bairro + '<br />' + cidade + '/' + estado + '<br />' + cep + '</div></div>';

							infoWindow.setContent(iwContent);

							google.maps.event.addListener(marker, 'click', function() {
								infoWindow.open(map, marker);
							});

							infoWindow.open(map, marker);

							map.setZoom(18);
						}

						function displayMarkers() {
							var bounds = new google.maps.LatLngBounds();
							for (var i = 0; i < markersData.length; i++) {
								var latlng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
								var cep = markersData[i].cep;
								var endereco = markersData[i].endereco;
								var bairro = markersData[i].bairro;
								var cidade = markersData[i].cidade;
								var estado = markersData[i].estado;

								createMarker(latlng, cep, endereco, bairro, cidade, estado);
								map.setCenter(latlng);
								bounds.extend(latlng);
							}
						}

						function removeMarkers() {
							marker.setMap(null);
						}

						function initialize() {
							var mapOptions = {
								zoom: 18,
								center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
								mapTypeId: 'roadmap',
							};

							map = new google.maps.Map(document.getElementById('mapa'), mapOptions);

							infoWindow = new google.maps.InfoWindow();

							google.maps.event.addListener(map, 'click', function() {
								infoWindow.close();
							});

							<?php if (!empty(chk_array($local, 'logradouro'))) { ?>
								displayMarkers();
							<?php } ?>
						}

						google.maps.event.addDomListener(window, 'load', initialize);
					</script>

					<div id="mapa" style="width: 100%; height: 300px;"></div>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	function getLocal() {
		$("#idLocal").change(function() {
			var id = $('option:selected', this).attr("value");
			var isEdicao = <?php echo (chk_array($this->parametros, 0) == 'editar') ? 'true' : 'false'; ?>;

			if (id != null && id != '') {
				$.ajax({
					type: "GET",
					url: "<?php echo HOME_URI; ?>/apiinterna/getLocais",
					data: {
						id: id
					},
					dataType: "json",
					success: function(dados) {
						// Preenche os campos visíveis
						$("#nomeOficial").val(dados.nomeOficial);
						$("#cep").val(dados.cep);
						$("#logradouro").val(dados.logradouro);
						$("#numero").val(dados.numero);
						$("#complemento").val(dados.complemento);
						$("#bairro").val(dados.bairro);
						$("#cidade").val(dados.cidade);
						$("#estado").val(dados.estado);

						// Atualiza os campos hidden para o POST
						$("#hidden_nomeOficial").val(dados.nomeOficial);
						$("#hidden_cep").val(dados.cep);
						$("#hidden_logradouro").val(dados.logradouro);
						$("#hidden_numero").val(dados.numero);
						$("#hidden_complemento").val(dados.complemento);
						$("#hidden_bairro").val(dados.bairro);
						$("#hidden_cidade").val(dados.cidade);
						$("#hidden_estado").val(dados.estado);
						$("#hidden_latitude").val(dados.latitude);
						$("#hidden_longitude").val(dados.longitude);

						// Se for edição, mantém os campos readonly
						if (isEdicao) {
							$('#nomeOficial, #cep, #logradouro, #numero, #complemento, #bairro, #cidade, #estado')
								.prop('readonly', true);
						}

						// Atualiza o mapa
						markersData = [{
							lat: dados.latitude,
							lng: dados.longitude,
							cep: dados.cep,
							endereco: dados.logradouro + ", " + dados.numero,
							bairro: dados.bairro,
							cidade: dados.cidade,
							estado: dados.estado
						}];

						if (marker) {
							removeMarkers();
						}
						displayMarkers();
					}
				});
			} else {
				// Limpa os campos visíveis
				$("#nomeOficial, #cep, #logradouro, #numero, #complemento, #bairro, #cidade, #estado").val("");

				// Limpa os campos hidden
				$("#hidden_nomeOficial, #hidden_cep, #hidden_logradouro, #hidden_numero, #hidden_complemento, #hidden_bairro, #hidden_cidade, #hidden_estado, #hidden_latitude, #hidden_longitude").val("");

				if (marker) {
					removeMarkers();
				}
			}
		});
	}

	$(document).ready(function() {
		var isEdicao = <?php echo (chk_array($this->parametros, 0) == 'editar') ? 'true' : 'false'; ?>;

		if (!isEdicao) {
			$('#licencaDiv').hide();
		}

		// Inicializa o getLocal imediatamente
		getLocal();

		// Inicializa Select2 com validação
		$('.select2').select2({
			theme: 'bootstrap4',
			width: '100%'
		}).on('select2:select', function(e) {
			$(this).trigger('change');
		});

		// Botão Novo Local
		$('#btnNovoLocal').click(function() {
			if (!isEdicao) {
				$('#idLocal').val('').trigger('change').prop('disabled', true);

				// Limpa e habilita os campos visíveis
				$('#nomeOficial, #cep, #logradouro, #numero, #complemento, #bairro, #cidade, #estado')
					.prop('readonly', false)
					.val('');

				// Limpa os campos hidden
				$("#hidden_nomeOficial, #hidden_cep, #hidden_logradouro, #hidden_numero, #hidden_complemento, #hidden_bairro, #hidden_cidade, #hidden_estado, #hidden_latitude, #hidden_longitude").val("");

				if (marker) {
					removeMarkers();
				}
			}
		});

		// Se for edição, desabilita o botão Novo Local
		if (isEdicao) {
			$('#btnNovoLocal').prop('disabled', true).hide();
		}

		// Adiciona listeners para atualizar campos hidden quando os campos visíveis são alterados
		$('#nomeOficial, #cep, #logradouro, #numero, #complemento, #bairro, #cidade, #estado').on('input', function() {
			var fieldId = $(this).attr('id');
			$('#hidden_' + fieldId).val($(this).val());
		});
	});
</script>

<style>
	/* Aplicando estilos comuns para os select2 containers */
	#idCategoria+.select2-container,
	#idLocal+.select2-container {
		width: 100% !important;
	}

	#idCategoria+.select2-container .select2-selection--single,
	#idLocal+.select2-container .select2-selection--single {
		height: calc(2.25rem + 2px);
		/* Ajuste para altura do input */
	}

	#idCategoria+.select2-container .select2-selection__rendered,
	#idLocal+.select2-container .select2-selection__rendered {
		line-height: calc(2.25rem);
		/* Centralizar texto */
	}

	#idCategoria+.select2-container .select2-selection__arrow,
	#idLocal+.select2-container .select2-selection__arrow {
		height: calc(2.25rem);
		/* Ajuste para ícone da seta */
	}
</style>

<script>
	$(function() {
		'use strict';

		// Configuração para upload da logo
		$('#fileupload-logo').fileupload({
			url: '<?php echo HOME_URI; ?>/upload/eventos/logo/<?php echo chk_array($parametros, 1); ?>',
			dataType: 'json',
			autoUpload: true,
			paramName: 'midia',
			maxFileSize: 5000000,
			disableImageLoad: true,
			disableImagePreview: false,
			disableImageMetadata: true,
			disableImageValidation: false,
			processQueue: [{
				action: 'validate',
				acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
				maxFileSize: 5000000
			}],
			send: function(e, data) {
				$('#preview-logo').html(`
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated" 
							role="progressbar" style="width: 0%">0%</div>
					</div>
				`);
			},
			progress: function(e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#preview-logo .progress-bar').css('width', progress + '%').text(progress + '%');
			},
			done: function(e, data) {
				if (data.result && data.result.midia && data.result.midia[0]) {
					var file = data.result.midia[0];
					if (file.url) {
						$('#preview-logo').html(`
							<div class="alert alert-success">
								<i class="fas fa-check-circle"></i> Upload realizado com sucesso!
							</div>
							<img src="${file.url}" class="img-thumbnail" style="max-width: 200px">
						`);
						setTimeout(function() { window.location.reload(); }, 1500);
					}
				}
			},
			fail: function(e, data) {
				$('#preview-logo').html(`
					<div class="alert alert-danger">
						<i class="fas fa-times-circle"></i> Erro no upload. Por favor, tente novamente.
					</div>
				`);
			}
		});

		// Configuração para upload do banner
		$('#fileupload-banner').fileupload({
			url: '<?php echo HOME_URI; ?>/upload/eventos/banner/<?php echo chk_array($parametros, 1); ?>',
			dataType: 'json',
			autoUpload: true,
			paramName: 'midia',
			maxFileSize: 5000000,
			disableImageLoad: true,
			disableImagePreview: false,
			disableImageMetadata: true,
			disableImageValidation: false,
			processQueue: [{
				action: 'validate',
				acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
				maxFileSize: 5000000
			}],
			send: function(e, data) {
				$('#preview-banner').html(`
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated" 
							role="progressbar" style="width: 0%">0%</div>
					</div>
				`);
			},
			progress: function(e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#preview-banner .progress-bar').css('width', progress + '%').text(progress + '%');
			},
			done: function(e, data) {
				if (data.result && data.result.midia && data.result.midia[0]) {
					var file = data.result.midia[0];
					if (file.url) {
						$('#preview-banner').html(`
							<div class="alert alert-success">
								<i class="fas fa-check-circle"></i> Upload realizado com sucesso!
							</div>
							<img src="${file.url}" class="img-thumbnail" style="max-width: 200px">
						`);
						setTimeout(function() { window.location.reload(); }, 1500);
					}
				}
			},
			fail: function(e, data) {
				$('#preview-banner').html(`
					<div class="alert alert-danger">
						<i class="fas fa-times-circle"></i> Erro no upload. Por favor, tente novamente.
					</div>
				`);
			}
		});
	});
</script>