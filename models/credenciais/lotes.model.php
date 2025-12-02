<?php
class LotesModel extends MainModel
{
    public $form_data;
    public $form_msg;
    public $toast_message;
    public $db;

    private $id;
    private $idEvento;
    private $nomeLote;
    private $observacao;
    private $qtdTempoEntreLeitura;
    private $status;

    private $idLote;
    private $periodos;
    private $setores;
    private $erro;

    public function __construct($db = null, $controller = null)
    {
        $this->db = $db;
        $this->controller = $controller;
        $this->parametros = $this->controller->parametros;
        $this->userdata = $this->controller->userdata;
    }

    /**
     * Valida o formulário de lotes
     */
    public function validarFormLotes()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        // Captura os dados do formulário
        $this->nomeLote = isset($_POST["nomeLote"]) ? $_POST["nomeLote"] : null;
        $this->observacao = isset($_POST["observacao"]) ? $_POST["observacao"] : null;
        $this->qtdTempoEntreLeitura = isset($_POST["qtdTempoEntreLeitura"]) ? $_POST["qtdTempoEntreLeitura"] : null;
        $this->idEvento = isset($_POST["idEvento"]) ? $_POST["idEvento"] : null;

        // Validações
        if (empty($this->nomeLote)) {
            $this->erro .= "<br>Preencha o nome do lote.";
        }
        if (empty($this->idEvento)) {
            $this->erro .= "<br>ID do evento não encontrado.";
        }

        // Prepara os dados para o formulário
        $this->form_data['nomeLote'] = $this->nomeLote;
        $this->form_data['observacao'] = $this->observacao;
        $this->form_data['qtdTempoEntreLeitura'] = $this->qtdTempoEntreLeitura;
        $this->form_data['idEvento'] = $this->idEvento;

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Os seguintes erros foram encontrados:</strong>' . $this->erro);
            return;
        }

        if (empty($this->form_data)) {
            return;
        }

        if (chk_array($this->parametros, 0) == 'editarLote') {
            $this->editarLote();
            return;
        } else {
            $this->adicionarLote();
            return;
        }
    }

    /**
     * Valida o formulário de relações de lotes
     */
    public function validarFormRelacoes()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->form_data = array();

        // Captura os dados do formulário
        $this->idLote = isset($_POST["idLote"]) ? $_POST["idLote"] : null;
        $this->periodos = isset($_POST["periodos"]) ? $_POST["periodos"] : array();
        $this->setores = isset($_POST["setores"]) ? $_POST["setores"] : array();
        $this->idEvento = isset($_POST["idEvento"]) ? $_POST["idEvento"] : null;

        // Validações
        if (empty($this->idLote)) {
            $this->erro .= "<br>ID do lote não encontrado.";
        }

        // Prepara os dados para o formulário
        $this->form_data['idLote'] = $this->idLote;
        $this->form_data['periodos'] = $this->periodos;
        $this->form_data['setores'] = $this->setores;
        $this->form_data['idEvento'] = $this->idEvento;

        if (!empty($this->erro)) {
            $this->form_msg = $this->controller->Messages->error('<strong>Os seguintes erros foram encontrados:</strong>' . $this->erro);
            return;
        }

        if (empty($this->form_data)) {
            return;
        }

        $this->salvarRelacoesLote();
    }

    /**
     * Adiciona um novo lote
     */
    private function adicionarLote()
    {
        $createLoteService = $this->getService('LotesServices', 'CreateLote');
        $query = $createLoteService->createLote(
            $this->form_data['idEvento'],
            $this->form_data
        );

        if (!$query) {
            $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
            return;
        } else {
            $this->form_msg = $this->controller->Messages->success('Lote cadastrado com sucesso.');
            $hash = encryptId($this->db->lastInsertId());
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/lotes' . '">';
            $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/lotes' . '";</script>';

            $this->form_data = null;
            return;
        }
    }

    /**
     * Edita um lote existente
     */
    private function editarLote()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $updateLoteService = $this->getService('LotesServices', 'UpdateLote');
            $query = $updateLoteService->updateLote(
                $id,
                $this->form_data
            );

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro interno: Os dados não foram enviados.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Lote editado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/lotes' . '">';
                $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/lotes' . '";</script>';

                return;
            }
        }
    }

    /**
     * Salva as relações de um lote (períodos e setores)
     */
    private function salvarRelacoesLote()
    {
        $id = $this->form_data['idLote'];

        if (!empty($id)) {
            $id = (int) $id;

            // Salva os períodos
            if (!empty($this->form_data['periodos'])) {
                $updatePeriodosLoteService = $this->getService('LotesServices', 'UpdatePeriodosLote');
                $queryPeriodos = $updatePeriodosLoteService->updatePeriodosLote($id, $this->form_data['periodos'], $this->form_data['idEvento']);

                if (!$queryPeriodos) {
                    $this->form_msg = $this->controller->Messages->error('Erro ao salvar períodos do lote.');
                    return;
                }
            }

            // Salva os setores
            if (!empty($this->form_data['setores'])) {
                $updateSetoresLoteService = $this->getService('LotesServices', 'UpdateSetoresLote');
                $querySetores = $updateSetoresLoteService->updateSetoresLote($id, $this->form_data['setores'], $this->form_data['idEvento']);

                if (!$querySetores) {
                    $this->form_msg = $this->controller->Messages->error('Erro ao salvar setores do lote.');
                    return;
                }
            }

            $this->form_msg = $this->controller->Messages->success('Relações do lote salvas com sucesso.');
            $hash = encryptId($id);
            $this->form_msg .= '<meta http-equiv="refresh" content="0; url=' . HOME_URI . '/lotes' . '">';
            $this->form_msg .= '<script type="text/javascript">window.location.href = "' . HOME_URI . '/lotes' . '";</script>';

            return;
        }
    }

    /**
     * Bloqueia um lote
     */
    public function bloquearLote()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $bloquearLoteService = $this->getService('LotesServices', 'BloquearLote');
            $query = $bloquearLoteService->bloquearLote($id);

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro ao bloquear o lote.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Lote bloqueado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/lotes">';
                return;
            }
        }
    }

    /**
     * Desbloqueia um lote
     */
    public function desbloquearLote()
    {
        $id = null;

        if (chk_array($this->parametros, 1)) {
            $hash = chk_array($this->parametros, 1);
            $id = decryptHash($hash);
        }

        if (!empty($id)) {
            $id = (int) $id;

            $desbloquearLoteService = $this->getService('LotesServices', 'DesbloquearLote');
            $query = $desbloquearLoteService->desbloquearLote($id);

            if (!$query) {
                $this->form_msg = $this->controller->Messages->error('Erro ao desbloquear o lote.');
                return;
            } else {
                $this->form_msg = $this->controller->Messages->success('Lote desbloqueado com sucesso.');
                $this->form_msg .= '<meta http-equiv="refresh" content="2; url=' . HOME_URI . '/lotes">';
                return;
            }
        }
    }

    /**
     * Obtém um lote específico
     */
    public function getLote($id = false)
    {
        if (empty($id)) {
            return;
        }

        $getLoteService = $this->getService('LotesServices', 'GetLote');
        $registro = $getLoteService->getLote($id);

        if (empty($registro)) {
            $this->form_msg = $this->controller->Messages->error('Registro inexistente.');
            return;
        }

        foreach ($registro as $key => $value) {
            $this->form_data[$key] = $value;
        }

        return $registro;
    }

    /**
     * Obtém todos os lotes de um evento
     */
    public function getLotes($idEvento = false)
    {
        if (empty($idEvento)) {
            return array();
        }

        $getLotesService = $this->getService('LotesServices', 'GetLotes');
        return $getLotesService->getLotes($idEvento);
    }

    /**
     * Obtém os períodos de um lote
     */
    public function getPeriodosLote($idLote = false)
    {
        if (empty($idLote)) {
            return array();
        }

        $getPeriodosLoteService = $this->getService('LotesServices', 'GetPeriodosLote');
        return $getPeriodosLoteService->getPeriodosLote($idLote);
    }

    /**
     * Obtém os setores de um lote
     */
    public function getSetoresLote($idLote = false)
    {
        if (empty($idLote)) {
            return array();
        }

        $getSetoresLoteService = $this->getService('LotesServices', 'GetSetoresLote');
        return $getSetoresLoteService->getSetoresLote($idLote);
    }


    /**
     * Obtém lotes disponíveis para um evento
     */
    public function getLotesDisponiveis($idEvento)
    {
        try {
            // Sem limite de lotes - removido conceito de empresa que controlava isso
            $sql = "SELECT 
                        l.id,
                        l.nomeLote as nome,
                        COUNT(c.id) as usada,
                        999999 as disponivel
                    FROM tblLote l
                    LEFT JOIN tblCredencial c ON l.id = c.idLote AND c.status = 'T'
                    WHERE l.idEvento = ? AND l.status = 'T'
                    GROUP BY l.id
                    ORDER BY l.nomeLote";

            $query = $this->db->query($sql, array($idEvento));

            if (!$query) {
                return array();
            }

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            Log::error('Erro ao buscar lotes disponíveis: ' . $e->getMessage());
            return array();
        }
    }
}
