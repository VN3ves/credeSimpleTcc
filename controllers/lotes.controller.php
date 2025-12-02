<?php
class LotesController extends MainController
{
    public $login_required = true;

    public function index()
    {
        $this->title = SYS_NAME . ' - Lotes de Credenciais';

        if (!$this->logged_in) {
            $this->logout();

            $this->goto_login();

            return;
        }

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        $modeloEvento = $this->load_model('eventos/eventos');
        $modeloCredenciais = $this->load_model('credenciais/credenciais');
        $modeloLotes = $this->load_model('credenciais/lotes');
        $modeloSetores = $this->load_model('recursos/setores');

        if (chk_array($this->parametros, 0) == 'adicionarLote') {
            $this->title = SYS_NAME . ' - Adicionar Lote de Credenciais';

            $conteudo = ABSPATH . '/views/lotes/adicionar.view.php';
        } elseif (chk_array($this->parametros, 0) == 'editarLote') {
            $this->title = SYS_NAME . ' - Editar Lote de Credenciais';

            $conteudo = ABSPATH . '/views/lotes/editar.view.php';
        } elseif (chk_array($this->parametros, 0) == 'relacoesLote') {
            $this->title = SYS_NAME . ' - Relações do Lote de Credenciais';

            $conteudo = ABSPATH . '/views/lotes/relacoes.view.php';
        } else {
            $this->title = SYS_NAME . ' - Lotes de Credenciais';
            $conteudo = ABSPATH . '/views/lotes/lotes.view.php';
        }

        require ABSPATH . '/views/painel/painel.view.php';
    }
}
