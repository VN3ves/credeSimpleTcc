<?php
if (!defined('ABSPATH')) exit;

$hash = $_SESSION['idEventoHash'];
$idEvento = decryptHash($hash);

if (chk_array($this->parametros, 1)) {
    $hashLote = chk_array($this->parametros, 1);
    $idLote = decryptHash($hashLote);
}

if (chk_array($this->parametros, 2) == 'bloquearCredencial') {
    $modeloCredenciais->bloquearCredencial();
}

if (chk_array($this->parametros, 2) == 'desbloquearCredencial') {
    $modeloCredenciais->desbloquearCredencial();
}

$status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
$q = isset($_REQUEST["q"]) ? $_REQUEST["q"] : null;

$filtros = array('status' => $status, 'q' => $q);

$credenciais = $modeloCredenciais->getCredenciaisLote($idLote);
$lote = $modeloLotes->getLote($idLote);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gerenciamento de Credenciais</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>/lotes">Lotes de Credenciais</a></li>
                        <li class="breadcrumb-item active">Credenciais do Lote: <?php echo $lote['nomeLote']; ?></li>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="q">Busca por texto</label> *Desenvolvimento*
                                <input type="text" class="form-control" placeholder="Digite aqui..." name="q" id="q" value="<?php echo $q; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Buscar</button>
                    <a href="<?php echo HOME_URI; ?>/credenciais/index/lotes/<?php echo $hashLote; ?>" class="btn btn-primary">Limpar</a>
                </div>
            </form>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Credenciais do Lote: <?php echo $lote['nomeLote']; ?></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalGerarSequencial">
                                <i class="fas fa-sort-numeric-up"></i> Gerar Sequencial
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo $modeloCredenciais->form_msg;
                        ?>
                        <div class="table-responsive">
                            <table id="table" class="table table-hover table-bordered table-striped dataTable">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Documento</th>
                                        <th>Código</th>
                                        <th>Observação</th>
                                        <th>Impresso</th>
                                        <th>Status</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($credenciais as $credencial): ?>
                                        <tr>
                                            <td><?php echo $credencial['nomeCredencial']; ?></td>
                                            <td><?php echo $credencial['docPessoa']; ?></td>
                                            <td><?php echo $credencial['codigoCredencial']; ?></td>
                                            <td><?php echo $credencial['observacao']; ?></td>
                                            <td><?php echo $credencial['impresso'] == 'T' ? 'Sim' : 'Não'; ?></td>
                                            <td><?php echo $credencial['status'] == 'T' ? 'Ativo' : 'Inativo'; ?></td>
                                            <td>
                                                <a href="<?php echo HOME_URI; ?>/credenciais/index/editarCredencial/<?php echo encryptId($credencial['id']); ?>" class="icon-tab" title="Editar"><i class="far fa-edit"></i></a>&nbsp;
                                                <a href="<?php echo HOME_URI; ?>/credenciais/index/relacoesCredencial/<?php echo encryptId($credencial['id']); ?>" class="icon-tab" title="Relações"><i class="fas fa-link"></i></a>&nbsp;
                                                <a href="<?php echo HOME_URI; ?>/credenciais/index/imprimirCredencial/<?php echo encryptId($credencial['id']); ?>" class="icon-tab" title="Imprimir"><i class="fas fa-print"></i></a>&nbsp;

                                                <?php if ($credencial['status'] == 'T') { ?>
                                                    <a href="<?php echo HOME_URI; ?>/credenciais/index/lotes/<?php echo $hashLote; ?>/bloquearCredencial/<?php echo encryptId($credencial['id']); ?>" class="icon-tab" title="Bloquear"><i class="fas fa-unlock text-green"></i></a>&nbsp;
                                                <?php } else { ?>
                                                    <a href="<?php echo HOME_URI; ?>/credenciais/index/lotes/<?php echo $hashLote; ?>/desbloquearCredencial/<?php echo encryptId($credencial['id']); ?>" class="icon-tab" title="Desbloquear"><i class="fas fa-lock text-red"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Documento</th>
                                        <th>Código</th>
                                        <th>Observação</th>
                                        <th>Impresso</th>
                                        <th>Status</th>
                                        <th>Opções</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <a href="<?php echo HOME_URI; ?>/credenciais/index/adicionarCredencial/<?php echo $hashLote; ?>"><button type="button" class="btn btn-block btn-danger">Adicionar Credencial</button></a>
            </div>
        </div>
    </section>

    <!-- Modal Gerar Sequencial -->
    <div class="modal fade" id="modalGerarSequencial" tabindex="-1" role="dialog" aria-labelledby="modalGerarSequencialLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form role="form" action="" method="POST">
                    <input type="hidden" name="idLote" value="<?php echo $idLote; ?>">
                    <input type="hidden" name="acao" value="gerarSequencial">
                    
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalGerarSequencialLabel">
                            <i class="fas fa-sort-numeric-up me-2"></i> Gerar Sequencial de Códigos
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body p-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Configure os parâmetros para gerar códigos sequenciais automaticamente
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="qtdDigitos" class="font-weight-bold">
                                        <i class="fas fa-hashtag text-success"></i> Quantidade de Dígitos *
                                    </label>
                                    <input type="number" class="form-control" id="qtdDigitos" name="qtdDigitos"
                                        placeholder="Ex: 6" min="1" max="15" value="6" required>
                                    <small class="form-text text-muted">Número total de dígitos do código</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-align-left text-success"></i> Zeros à Esquerda
                                    </label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="zerosEsquerda_sim" name="zerosEsquerda" value="1" class="custom-control-input" checked>
                                        <label class="custom-control-label" for="zerosEsquerda_sim">Sim</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="zerosEsquerda_nao" name="zerosEsquerda" value="0" class="custom-control-input">
                                        <label class="custom-control-label" for="zerosEsquerda_nao">Não</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="prefixo" class="font-weight-bold">
                                        <i class="fas fa-arrow-right text-success"></i> Prefixo
                                    </label>
                                    <input type="text" class="form-control" id="prefixo" name="prefixo"
                                        placeholder="Ex: CRED" maxlength="10">
                                    <small class="form-text text-muted">Texto que aparecerá antes do número</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="sufixo" class="font-weight-bold">
                                        <i class="fas fa-arrow-left text-success"></i> Sufixo
                                    </label>
                                    <input type="text" class="form-control" id="sufixo" name="sufixo"
                                        placeholder="Ex: 2024" maxlength="10">
                                    <small class="form-text text-muted">Texto que aparecerá depois do número</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="numeroInicial" class="font-weight-bold">
                                        <i class="fas fa-play text-success"></i> Número Inicial *
                                    </label>
                                    <input type="number" class="form-control" id="numeroInicial" name="numeroInicial"
                                        placeholder="Ex: 1" min="0" value="1" required>
                                    <small class="form-text text-muted">Número de onde começar a sequência</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="numeroFinal" class="font-weight-bold">
                                        <i class="fas fa-stop text-success"></i> Número Final *
                                    </label>
                                    <input type="number" class="form-control" id="numeroFinal" name="numeroFinal"
                                        placeholder="Ex: 100" min="1" required>
                                    <small class="form-text text-muted">Número onde terminar a sequência</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-eye text-success"></i> Exemplo do Código Gerado:
                                    </label>
                                    <div class="alert alert-secondary" id="exemploGerado">
                                        <code id="codigoExemplo">CRED000001</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-magic"></i> Gerar Códigos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: false,
            scrollCollapse: true,
            processing: true,
            serverSide: false, // Como não temos endpoint configurado, mantemos false por enquanto
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            pageLength: 25, // Aumentando para 25 itens por página
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            columnDefs: [{
                orderable: false,
                targets: [6] // Coluna de opções não ordenável
            }],
            // Configurações de paginação melhoradas
            pagingType: "full_numbers",
            info: true,
            searching: true,
            ordering: true
        });

        // Função para atualizar o exemplo do código gerado
        function atualizarExemplo() {
            var prefixo = $('#prefixo').val() || '';
            var sufixo = $('#sufixo').val() || '';
            var qtdDigitos = parseInt($('#qtdDigitos').val()) || 6;
            var numeroInicial = parseInt($('#numeroInicial').val()) || 1;
            var zerosEsquerda = $('input[name="zerosEsquerda"]:checked').val() == '1';
            
            var numeroFormatado = zerosEsquerda ? 
                numeroInicial.toString().padStart(qtdDigitos, '0') : 
                numeroInicial.toString();
            
            var exemplo = prefixo + numeroFormatado + sufixo;
            $('#codigoExemplo').text(exemplo);
        }

        // Eventos para atualizar o exemplo
        $('#prefixo, #sufixo, #qtdDigitos, #numeroInicial').on('input', atualizarExemplo);
        $('input[name="zerosEsquerda"]').on('change', atualizarExemplo);

        // Validação do número final
        $('#numeroFinal').on('input', function() {
            var inicial = parseInt($('#numeroInicial').val()) || 1;
            var final = parseInt($(this).val()) || 1;
            
            if (final <= inicial) {
                $(this).addClass('is-invalid');
                $('#modalGerarSequencial').find('button[type="submit"]').prop('disabled', true);
            } else {
                $(this).removeClass('is-invalid');
                $('#modalGerarSequencial').find('button[type="submit"]').prop('disabled', false);
            }
        });

        // Inicializa o exemplo
        atualizarExemplo();
    });
</script>