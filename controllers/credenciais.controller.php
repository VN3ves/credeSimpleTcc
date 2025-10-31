<?php
class CredenciaisController extends MainController
{
    public $login_required = true;

    public function index()
    {
        $this->title = SYS_NAME . ' - Credenciais';

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

        if (chk_array($this->parametros, 0) == 'lotes') {
            $this->title = SYS_NAME . ' - Credenciais';

            $conteudo = ABSPATH . '/views/credenciais/credenciais.view.php';
        } elseif (chk_array($this->parametros, 0) == 'editarCredencial') {
            $this->title = SYS_NAME . ' - Editar Credencial';

            $conteudo = ABSPATH . '/views/credenciais/editar.view.php';
        } elseif (chk_array($this->parametros, 0) == 'adicionarCredencial') {
            $this->title = SYS_NAME . ' - Adicionar Credencial';

            $conteudo = ABSPATH . '/views/credenciais/adicionar.view.php';
        } elseif (chk_array($this->parametros, 0) == 'relacoesCredencial') {
            $this->title = SYS_NAME . ' - Relações Credencial';

            $conteudo = ABSPATH . '/views/credenciais/relacoes.view.php';
        } else {
            $this->title = SYS_NAME . ' - Credenciais';

            $conteudo = ABSPATH . '/views/credenciais/credenciais.view.php';
        }

        require ABSPATH . '/views/painel/painel.view.php';
    }


}
