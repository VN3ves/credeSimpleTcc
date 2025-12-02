				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="card card-widget widget-user">
								<?php
								if (chk_array($this->parametros, 1)) {
									$hash = chk_array($this->parametros, 1);
									$id = decryptHash($hash);
								}
								$dirAvatar = $modelo->getAvatar($id, false);
								$dirBanner = $modelo->getBanner($id);
								?>
								<div class="widget-user-header bg-secondary" 
									 style="background: url('<?php echo HOME_URI . '/' . $dirBanner ?>') center center; 
											background-size: cover;
											position: relative;">
									<!-- Overlay escuro para melhorar legibilidade -->
									<div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
												background: rgba(0,0,0,0.4);"></div>
									
									<!-- Conteúdo -->
									<div style="position: relative; z-index: 1;">
										<!-- Imagem -->
										<h3 class="widget-user-username">
											<img
												src="<?php echo HOME_URI . '/' . $dirAvatar ?>"
												class="img-circle elevation-2"
												alt="User Image"
												style="width: 90px; height: 90px; object-fit: cover;">
										</h3>
										<!-- Nome -->
										<h3 class="widget-user-username text-white">
											<?php echo htmlentities(chk_array($modelo->form_data, 'nomeEvento')); ?>
										</h3>
										<h5 class="widget-user-desc text-white">
											<?php echo htmlentities(chk_array($modelo->form_data, 'nomeCategoria')); ?>
										</h5>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<style>
					.widget-user-header {
						min-height: 180px;
						padding: 40px 20px;
						text-align: center;
					}
					
					/* Ajuste para melhor visualização do texto sobre o banner */
					.widget-user-username,
					.widget-user-desc {
						text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
					}
					
					/* Ajuste para a imagem do logo */
					.widget-user-username img {
						border: 3px solid #fff;
						box-shadow: 0 3px 6px rgba(0,0,0,0.16);
					}
				</style>