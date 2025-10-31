<?php
if (!defined('ABSPATH')) exit;

$hash = $_SESSION['idEventoHash'];
$idEvento = decryptHash($hash);

if (chk_array($this->parametros, 0) == 'bloquearLote') {
    $modeloLotes->bloquearLote();
}

if (chk_array($this->parametros, 0) == 'desbloquearLote') {
    $modeloLotes->desbloquearLote();
}

$status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
$q = isset($_REQUEST["q"]) ? $_REQUEST["q"] : null;

$filtros = array('status' => $status, 'q' => $q);

$lotes = $modeloLotes->getLotes($idEvento);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gerenciamento de Lotes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo HOME_URI; ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="<?php echo HOME_URI; ?>/lotes">Lotes de Credenciais</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <style>
        #tabelaLotes_wrapper,
        #tabelaLotes_wrapper .dataTables_scrollBody {
            overflow-x: hidden !important;
        }
    </style>


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
                    <a href="<?php echo HOME_URI; ?>/lotes" class="btn btn-primary">Limpar</a>
                </div>
            </form>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Lotes de Credenciais</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        echo $modeloLotes->form_msg;
                        ?>
                        <div class="table-responsive">
                            <table id="tabelaLotes" class="table table-hover table-bordered table-striped dataTable">
                                <thead>
                                    <tr>
                                        <th>Lote</th>
                                        <th>Credencial</th>
                                        <th>Código</th>
                                        <th>Acesso Facial</th>
                                        <th>Impressão</th>
                                        <th>Autonumeração</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lotes as $lote): ?>
                                        <tr>
                                            <td><?php echo $lote['nomeLote']; ?></td>
                                            <td><?php echo $lote['tipoCredencial'] ?></td>
                                            <td><?php echo $lote['tipoCodigo'] ?></td>
                                            <td><?php echo $lote['permiteAcessoFacial'] == '1' ? 'Sim' : 'Não'; ?></td>
                                            <td><?php echo $lote['permiteImpressao'] == '1' ? 'Sim' : 'Não'; ?></td>
                                            <td><?php echo $lote['temAutonumeracao'] == '1' ? 'Sim' : 'Não'; ?></td>

                                            <td>
                                                <a href="<?php echo HOME_URI; ?>/credenciais/index/lotes/<?php echo encryptId($lote['id']); ?>" class="icon-tab" title="Credenciais"><i class="far fa-credit-card"></i></a>&nbsp;

                                                <a href="<?php echo HOME_URI; ?>/lotes/index/editarLote/<?php echo encryptId($lote['id']); ?>" class="icon-tab" title="Editar"><i class="far fa-edit"></i></a>&nbsp;
                                                <a href="<?php echo HOME_URI; ?>/lotes/index/relacoesLote/<?php echo encryptId($lote['id']); ?>" class="icon-tab" title="Relações"><i class="fas fa-link"></i></a>&nbsp;

                                                <?php if ($lote['status'] == 'T') { ?>
                                                    <a href="<?php echo HOME_URI; ?>/lotes/index/bloquearLote/<?php echo encryptId($lote['id']); ?>" class="icon-tab" title="Bloquear"><i class="fas fa-unlock text-green"></i></a>&nbsp;
                                                <?php } else { ?>
                                                    <a href="<?php echo HOME_URI; ?>/lotes/index/desbloquearLote/<?php echo encryptId($lote['id']); ?>" class="icon-tab" title="Desbloquear"><i class="fas fa-lock text-red"></i></a>
                                                <?php } ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Lote</th>
                                        <th>Credencial</th>
                                        <th>Código</th>
                                        <th>Acesso Facial</th>
                                        <th>Impressão</th>
                                        <th>Autonumeração</th>
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
                <a href="<?php echo HOME_URI; ?>/lotes/index/adicionarLote/<?php echo encryptId($idEvento); ?>"><button type="button" class="btn btn-block btn-danger">Adicionar Lote</button></a>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {

        // Função para traduzir tipos de código
        function traduzirTipoCodigo(codigo) {
            const traducoes = {
                'BARRAS_2D(QR_CODE)': 'QR Code',
                'CODIGO_BARRAS': 'Código de Barras',
                'BARRAS_1D': 'Código de Barras Linear',
                'BARRAS_2D': 'QR Code',
                'RFID': 'RFID',
                'NFC': 'NFC'
            };
            return traducoes[codigo] || codigo;
        }

        function traduzirTipoCredencial(credencial) {
            const traducoes = {
                'CARTAO_PVC': 'Cartão PVC',
                'PAPEL': 'Cartão Papel',
                'PULSEIRA_PVC': 'Pulseira PVC',
                'PULSEIRA_TYVEK': 'Pulseira Tyvek',
            };
            return traducoes[credencial] || credencial;
        }

        $('#tabelaLotes').DataTable({
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
            // Personaliza a exibição da célula do tipo de código
            createdRow: function(row, data, dataIndex) {
                // Traduz o tipo de código na terceira coluna (índice 2)
                var $tipoCodigo = $(row).find('td:eq(2)');
                if ($tipoCodigo.length) {
                    var codigoOriginal = $tipoCodigo.text().trim();
                    var codigoTraduzido = traduzirTipoCodigo(codigoOriginal);
                    $tipoCodigo.html('<span class="badge badge-info">' + codigoTraduzido + '</span>');
                }

                var $tipoCredencial = $(row).find('td:eq(1)');
                if ($tipoCredencial.length) {
                    var credencialOriginal = $tipoCredencial.text().trim();
                    var credencialTraduzido = traduzirTipoCredencial(credencialOriginal);
                    $tipoCredencial.html('<span class="badge badge-warning">' + credencialTraduzido + '</span>');
                }
            },
            // Configurações de paginação melhoradas
            pagingType: "full_numbers",
            info: true,
            searching: true,
            ordering: true
        });
    });
</script>