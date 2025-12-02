<?php
if (!defined('ABSPATH')) exit;

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-id-card mr-2"></i>Sistema de Credenciamento</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active"><i class="fas fa-id-badge"></i> Credenciamento</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            <!-- Estágio 1: Busca -->
            <div class="card card-primary card-outline shadow-sm" id="searchCard">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-search mr-2"></i>Buscar Participante
                        <span class="badge badge-light ml-2">Estágio 1/3</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="documento_busca">
                            <i class="fas fa-id-card text-primary mr-1"></i>
                            CPF ou Número do Passaporte
                        </label>
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-document text-muted"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="documento_busca" 
                                   placeholder="Digite o documento para iniciar...">
                            <div class="input-group-append">
                                <button class="btn btn-success btn-flat" type="button" id="searchButton">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Digite o CPF ou passaporte do participante para verificar seu cadastro
                        </small>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="loading" style="display: none;" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Carregando...</span>
                        </div>
                        <h5 class="mt-3 text-muted">Buscando dados...</h5>
                        <p class="text-muted">Aguarde enquanto processamos sua consulta.</p>
                    </div>
                </div>
            </div>

            <!-- Estágio 2: Cadastro da Pessoa (se não existe) -->
            <div class="card card-warning card-outline shadow-sm" id="personCard" style="display: none;">
                <div class="card-header bg-gradient-warning">
                    <h3 class="card-title text-white">
                        <i class="fas fa-user-plus mr-2"></i>Cadastrar Participante
                        <span class="badge badge-light ml-2">Estágio 2/3</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Participante não encontrado. É necessário cadastrar os dados básicos antes de prosseguir.
                    </div>
                    
                    <form id="personForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pessoa_nome">
                                        <i class="fas fa-user text-primary mr-1"></i>
                                        Nome <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="pessoa_nome" 
                                           name="nome" required placeholder="Nome do participante">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pessoa_sobrenome">
                                        <i class="fas fa-user text-primary mr-1"></i>
                                        Sobrenome <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="pessoa_sobrenome" 
                                           name="sobrenome" required placeholder="Sobrenome do participante">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pessoa_cpf">
                                        <i class="fas fa-id-card text-info mr-1"></i>
                                        CPF <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="pessoa_cpf" 
                                           name="cpf" required placeholder="000.000.000-00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pessoa_telefone">
                                        <i class="fas fa-phone text-success mr-1"></i>
                                        Telefone
                                    </label>
                                    <input type="tel" class="form-control" id="pessoa_telefone" 
                                           name="telefone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="pessoa_email">
                                <i class="fas fa-envelope text-warning mr-1"></i>
                                E-mail
                            </label>
                            <input type="email" class="form-control" id="pessoa_email" 
                                   name="email" placeholder="email@exemplo.com">
                        </div>
                        
                        <div class="card-footer bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Campos marcados com * são obrigatórios
                                    </small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-default mr-2" id="cancelPersonButton">
                                        <i class="fas fa-times mr-1"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-user-plus mr-1"></i> Cadastrar e Continuar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Estágio 3: Credenciamento -->
            <form id="credenciamentoForm" style="display: none;">
                <input type="hidden" id="participante_id" name="participante_id">
                <input type="hidden" id="foto_base64" name="foto">
                <input type="hidden" id="action_type" name="action_type" value="new">

                <div class="card card-success card-outline shadow-sm" id="resultsCard">
                    <div class="card-header bg-gradient-success">
                        <h3 class="card-title">
                            <i class="fas fa-id-badge mr-2"></i><span id="credencial_title">Atribuir Credencial</span>
                            <span class="badge badge-light ml-2">Estágio 3/3</span>
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-light" id="status_badge">
                                <i class="fas fa-check-circle"></i> Participante Encontrado
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- Seção da Foto -->
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="profile-photo-container mb-3">
                                        <img id="profilePhoto" src="<?php echo HOME_URI; ?>/midia/noPictureProfile.png" 
                                             class="profile-photo img-fluid rounded-circle shadow-sm" 
                                             style="width: 200px; height: 200px; object-fit: cover; border: 4px solid #fff; cursor: pointer;" 
                                             alt="Foto do Participante"
                                             data-toggle="modal" data-target="#capturePhotoModal">
                                        <div class="photo-overlay">
                                            <i class="fas fa-camera fa-2x text-white"></i>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="btn btn-info btn-sm" 
                                            data-toggle="modal" data-target="#capturePhotoModal">
                                        <i class="fas fa-camera mr-1"></i> Capturar Foto
                                    </button>
                                    
                                    <div class="mt-3">
                                        <div class="callout callout-info">
                                            <h6><i class="fas fa-lightbulb"></i> Dica</h6>
                                            <p class="mb-0">Centralize o rosto do participante na câmera antes de capturar.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Seção do Formulário -->
                            <div class="col-md-8">
                                <!-- Nome do Participante -->
                                <div class="form-group">
                                    <label for="nome">
                                        <i class="fas fa-user text-info mr-1"></i>
                                        Nome do Participante
                                    </label>
                                    <input type="text" class="form-control form-control-lg bg-light" 
                                           id="nome" name="nome" required readonly>
                                </div>

                                <!-- Credencial Atual (se existir) -->
                                <div id="credencial_atual_section" style="display: none;">
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-id-card mr-2"></i>Credencial Atual</h6>
                                        <p class="mb-2"><strong>Tipo:</strong> <span id="credencial_atual_nome"></span></p>
                                        <p class="mb-0"><strong>Lote:</strong> <span id="credencial_atual_lote"></span></p>
                                    </div>
                                </div>

                                <hr class="border-primary">

                                <!-- Seleção de Lote -->
                                <h5 class="text-dark mb-3">
                                    <i class="fas fa-tags text-warning mr-2"></i>
                                    Selecionar Lote de Credencial
                                    <span class="badge badge-danger ml-2">Obrigatório</span>
                                </h5>

                                <div class="form-group">
                                    <label for="lote_id">
                                        <i class="fas fa-layer-group text-primary mr-1"></i>
                                        Lote Disponível
                                    </label>
                                    <select class="form-control select2" id="lote_id" name="lote_id" required>
                                        <option value="">Carregando lotes disponíveis...</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Selecione o tipo de credencial para o participante
                                    </small>
                                </div>

                                <!-- Status Alert -->
                                <div class="alert alert-success alert-dismissible" id="status_alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h6><i class="icon fas fa-check"></i> Status: Pronto para Credenciamento</h6>
                                    <span id="status_message">Participante localizado com sucesso. Selecione um lote para finalizar.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Todos os campos marcados com * são obrigatórios
                                </small>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-default mr-2" id="cancelButton">
                                    <i class="fas fa-times mr-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-success" id="submitButton">
                                    <i class="fas fa-save mr-1"></i> <span id="submit_text">Salvar Credenciamento</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Modal de Captura de Foto com Detecção Facial -->
<div class="modal fade" id="capturePhotoModal" tabindex="-1" role="dialog" aria-labelledby="capturePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="capturePhotoModalLabel">
                    <i class="fas fa-camera mr-2"></i>Capturar Foto do Participante
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="video-container mb-3" style="border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <video id="video" autoplay style="width: 100%; max-width: 480px; border-radius: 10px;"></video>
                    </div>
                    <canvas id="canvas" style="display: none;"></canvas>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Posicione o participante no centro da câmera e clique em capturar.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Fechar
                </button>
                <button type="button" id="snap" class="btn btn-primary">
                    <i class="fas fa-camera mr-1"></i> Capturar Foto
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos customizados mantendo o padrão AdminLTE */
.profile-photo-container {
    position: relative;
    display: inline-block;
}

.profile-photo {
    transition: all 0.3s ease;
}

.profile-photo:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    cursor: pointer;
}

.profile-photo-container:hover .photo-overlay {
    opacity: 1;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.input-group-lg .form-control {
    font-size: 1.1rem;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Animações suaves */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

.slide-down {
    animation: slideDown 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from { 
        opacity: 0; 
        transform: translateY(-20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

/* Melhorias nos botões */
.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Select2 customizado para AdminLTE */
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

/* Opções desabilitadas no select */
.select2-results__option[aria-disabled="true"] {
    opacity: 0.6;
    background-color: #f8f9fa;
    color: #6c757d;
}

/* Efeitos de hover nos cards */
.card-outline:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

/* Loading states */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s ease infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsividade melhorada */
@media (max-width: 768px) {
    .profile-photo {
        width: 150px !important;
        height: 150px !important;
    }
    
    .card-footer .row {
        text-align: center;
    }
    
    .card-footer .btn {
        margin-bottom: 10px;
    }
}
</style>

<script>
$(document).ready(function() {
    
    // Inicializar o plugin Select2 no campo de lote
    $('#lote_id').select2({
        placeholder: 'Selecione um lote',
        allowClear: true,
        width: '100%'
    });

    // Função para resetar a tela para o estado inicial
    var fotoPlaceholder = '<?php echo HOME_URI; ?>/midia/noPictureProfile.png';
    function resetView() {
        $('#credenciamentoForm, #personCard').fadeOut(400, function() {
            $('#credenciamentoForm')[0].reset();
            $('#personForm')[0].reset();
            $('#lote_id').empty().trigger('change');
            $('#profilePhoto').attr('src', fotoPlaceholder);
            $('#participante_id').val('');
            $('#foto_base64').val('');
            $('#credencial_atual_section').hide();
        });
        
        setTimeout(() => {
            $('#searchCard').fadeIn(400).addClass('slide-down');
            $('#documento_busca').val('').focus();
        }, 450);
    }

    // Ações dos botões Cancelar
    $('#cancelButton, #cancelPersonButton').on('click', function() {
        Swal.fire({
            title: 'Cancelar Processo?',
            text: "Todos os dados preenchidos serão perdidos!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, cancelar',
            cancelButtonText: 'Não, continuar'
        }).then((result) => {
            if (result.isConfirmed) {
                resetView();
                Swal.fire('Cancelado!', 'O processo foi cancelado.', 'success');
            }
        });
    });

    // Carregar lotes disponíveis
    function loadAvailableLots() {
        $.ajax({
            url: '<?php echo HOME_URI; ?>/credenciamento/lotesDisponiveis',
            method: 'GET',
            dataType: 'json',
            success: function(lotes) {
                const loteSelect = $('#lote_id');
                loteSelect.empty();
                loteSelect.append(new Option('Selecione um lote', '', true, true));

                if (lotes && lotes.length > 0) {
                    lotes.forEach(function(lote) {
                        // Sem limite - sistema simplificado sem controle de empresa
                        const qtdEmitidas = lote.usada;
                        const optionText = `${lote.nome} (${qtdEmitidas} credenciais emitidas)`;
                        
                        const option = new Option(optionText, lote.id, false, false);
                        loteSelect.append(option);
                    });
                } else {
                    loteSelect.append(new Option('Nenhum lote disponível', '', false, true));
                }
                
                loteSelect.trigger('change');
            },
            error: function() {
                $('#lote_id').empty().append(new Option('Erro ao carregar lotes', '', false, true));
            }
        });
    }

    // Função de busca principal usando a rota fornecida
    function performSearch() {
        const documento = $('#documento_busca').val().trim();
        if (documento === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Por favor, digite um documento para a busca.',
                timer: 3000
            });
            $('#documento_busca').focus();
            return;
        }

        $('#loading').fadeIn(300);
        $('#searchButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Buscando...');

        $.ajax({
            url: '<?php echo HOME_URI; ?>/credenciamento/buscaDoc/' + encodeURIComponent(documento) + '/' + '<?php echo isset($_SESSION['idEventoHash']) ? $_SESSION['idEventoHash'] : ''; ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Resposta da API:', response);
                
                if (response && response.pessoa) {
                    // Pessoa existe
                    const pessoa = response.pessoa;
                    const credencial = response.credencial;
                    
                    // Preenche dados básicos
                    $('#participante_id').val(pessoa.id);
                    $('#nome').val(`${pessoa.nome} ${pessoa.sobrenome}`);
                    
                    // Define a foto
                    var fotoPlaceholder = pessoa.localFoto ? pessoa.localFoto : '<?php echo HOME_URI; ?>/midia/noPictureProfile.png';
                    const iniciais = `${pessoa.nome.charAt(0)}${pessoa.sobrenome.charAt(0)}`;
                    $('#profilePhoto').attr('src', fotoPlaceholder);
                    
                    // Verifica se já tem credencial
                    if (credencial && credencial.nomeCredencial) {
                        // Já tem credencial - modo troca
                        $('#action_type').val('change');
                        $('#credencial_title').text('Trocar Credencial');
                        $('#submit_text').text('Confirmar Troca');
                        $('#status_badge').html('<i class="fas fa-exchange-alt"></i> Troca de Credencial');
                        $('#status_message').text('Participante já possui credencial. Selecione um novo lote para substituir.');
                        $('#status_alert').removeClass('alert-success').addClass('alert-warning');
                        
                        $('#credencial_atual_section').show();
                        $('#credencial_atual_nome').text(credencial.nomeCredencial);
                        $('#credencial_atual_lote').text(credencial.nomeLote || 'N/A');
                    } else {
                        // Não tem credencial - modo novo
                        $('#action_type').val('new');
                        $('#credencial_title').text('Atribuir Credencial');
                        $('#submit_text').text('Salvar Credenciamento');
                        $('#status_badge').html('<i class="fas fa-check-circle"></i> Pronto para Credenciamento');
                        $('#status_message').text('Participante encontrado. Selecione um lote para criar a credencial.');
                        $('#status_alert').removeClass('alert-warning').addClass('alert-success');
                        $('#credencial_atual_section').hide();
                    }
                    
                    // Carrega lotes e mostra formulário
                    loadAvailableLots();
                    
                    $('#searchCard').fadeOut(400, function() {
                        $('#credenciamentoForm').fadeIn(400).addClass('fade-in');
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Participante Encontrado!',
                        text: `${pessoa.nome} ${pessoa.sobrenome} carregado com sucesso.`,
                        timer: 3000
                    });

                } else {
                    // Pessoa não existe - vai para cadastro
                    $('#pessoa_cpf').val(documento);
                    
                    $('#searchCard').fadeOut(400, function() {
                        $('#personCard').fadeIn(400).addClass('fade-in');
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Conexão',
                    text: 'Não foi possível se comunicar com o servidor. Tente novamente.',
                    timer: 5000
                });
            },
            complete: function() {
                $('#loading').fadeOut(300);
                $('#searchButton').prop('disabled', false).html('<i class="fas fa-search"></i> Buscar');
            }
        });
    }

    // Cadastro de pessoa
    $('#personForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Cadastrando...');

        $.ajax({
            url: '<?php echo HOME_URI; ?>/credenciamento/cadastrarPessoa',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response && response.success && response.pessoa) {
                    const pessoa = response.pessoa;
                    
                    // Preenche dados para o próximo estágio
                    $('#participante_id').val(pessoa.id);
                    $('#nome').val(`${pessoa.nome} ${pessoa.sobrenome}`);
                    
                    const iniciais = `${pessoa.nome.charAt(0)}${pessoa.sobrenome.charAt(0)}`;
                    var fotoPlaceholder = '<?php echo HOME_URI; ?>/midia/noPictureProfile.png';
                    $('#profilePhoto').attr('src', fotoPlaceholder);
                    
                    // Configura para novo credenciamento
                    $('#action_type').val('new');
                    $('#credencial_title').text('Atribuir Credencial');
                    $('#submit_text').text('Salvar Credenciamento');
                    $('#status_badge').html('<i class="fas fa-user-plus"></i> Participante Cadastrado');
                    $('#status_message').text('Participante cadastrado com sucesso. Selecione um lote para criar a credencial.');
                    $('#status_alert').removeClass('alert-warning').addClass('alert-success');
                    $('#credencial_atual_section').hide();
                    
                    // Carrega lotes
                    loadAvailableLots();
                    
                    // Transição para o próximo estágio
                    $('#personCard').fadeOut(400, function() {
                        $('#credenciamentoForm').fadeIn(400).addClass('fade-in');
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Participante Cadastrado!',
                        text: `${pessoa.nome} ${pessoa.sobrenome} foi cadastrado e está pronto para credenciamento.`,
                        timer: 3000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao Cadastrar',
                        text: response.message || 'Não foi possível cadastrar o participante.',
                        timer: 4000
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Conexão',
                    text: 'Não foi possível cadastrar o participante. Tente novamente.',
                    timer: 5000
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-user-plus mr-1"></i> Cadastrar e Continuar');
            }
        });
    });

    // Gatilhos da busca
    $('#searchButton').on('click', performSearch);
    $('#documento_busca').on('keypress', function(e) {
        if (e.which === 13) { // Tecla Enter
            performSearch();
        }
    });

    // Submissão do formulário de credenciamento
    $('#credenciamentoForm').on('submit', function(e) {
        e.preventDefault();

        if (!$('#lote_id').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Lote Obrigatório',
                text: 'Por favor, selecione um lote para prosseguir.',
                timer: 3000
            });
            $('#lote_id').focus();
            return;
        }

        const formData = $(this).serialize();
        const submitBtn = $('#submitButton');

        submitBtn.prop('disabled', true).addClass('btn-loading').html('<i class="fas fa-spinner fa-spin mr-1"></i> Salvando...');

        $.ajax({
            url: '<?php echo HOME_URI; ?>/credenciamento/salvar',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response && response.success) {
                    const actionType = $('#action_type').val();
                    const actionText = actionType === 'change' ? 'trocado' : 'criado';
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Credenciamento Salvo!',
                        text: response.message || `Credenciamento ${actionText} com sucesso.`,
                        timer: 3000
                    }).then(() => {
                        resetView();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao Salvar',
                        text: response.message || 'Não foi possível salvar os dados.',
                        timer: 4000
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Conexão',
                    text: 'Não foi possível salvar os dados. Tente novamente.',
                    timer: 5000
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).removeClass('btn-loading').html('<i class="fas fa-save mr-1"></i> <span id="submit_text">Salvar Credenciamento</span>');
            }
        });
    });

    // Lógica da Câmera
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const snap = document.getElementById('snap');
    let stream = null;

    // Inicia a câmera quando o modal é aberto
    $('#capturePhotoModal').on('shown.bs.modal', async function() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: 640, 
                    height: 480,
                    facingMode: 'user'
                } 
            });
            video.srcObject = stream;
        } catch (err) {
            console.error("Erro ao acessar a câmera: ", err);
            Swal.fire({
                icon: 'error',
                title: 'Erro na Câmera',
                text: 'Não foi possível acessar a câmera. Verifique as permissões.',
            });
            $('#capturePhotoModal').modal('hide');
        }
    });

    // Para a câmera quando o modal é fechado
    $('#capturePhotoModal').on('hidden.bs.modal', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.srcObject = null;
    });

    // Captura a foto
    snap.addEventListener('click', function() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataURL = canvas.toDataURL('image/jpeg', 0.8);
        $('#profilePhoto').attr('src', dataURL);
        $('#foto_base64').val(dataURL);

        $('#capturePhotoModal').modal('hide');
        
        Swal.fire({
            icon: 'success',
            title: 'Foto Capturada!',
            text: 'A foto foi capturada com sucesso.',
            timer: 2000
        });
    });

    // Auto-focus no campo de busca ao carregar a página
    $('#documento_busca').focus();

    // Formatação dos campos
    $('#documento_busca, #pessoa_cpf').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }
        $(this).val(value);
    });

    // Formatação do telefone
    $('#pessoa_telefone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
        $(this).val(value);
    });

    // Validação de email em tempo real
    $('#pessoa_email').on('blur', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            Swal.fire({
                icon: 'warning',
                title: 'Email Inválido',
                text: 'Por favor, digite um email válido.',
                timer: 3000
            });
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Validação de CPF básica
    $('#pessoa_cpf').on('blur', function() {
        const cpf = $(this).val().replace(/\D/g, '');
        
        if (cpf && cpf.length !== 11) {
            $(this).addClass('is-invalid');
            Swal.fire({
                icon: 'warning',
                title: 'CPF Inválido',
                text: 'O CPF deve ter 11 dígitos.',
                timer: 3000
            });
        } else {
            $(this).removeClass('is-invalid');
        }
    });

});
</script>