<?php
class RecursosController extends MainController
{
    public $login_required = true;

    public function leitores()

    {
        if (!$this->logged_in) {
            $this->logout();

            $this->goto_login();

            return;
        }

        $this->title = SYS_NAME . ' - Leitores';

        $modeloLeitores = $this->load_model('recursos/leitores');
        $modeloSetores = $this->load_model('recursos/setores');

        $conteudo = ABSPATH . '/views/recursos/leitores.view.php';
        require ABSPATH . '/views/painel/painel.view.php';
    }

    public function setores()
    {
        if (!$this->logged_in) {
            $this->logout();

            $this->goto_login();

            return;
        }

        $this->title = SYS_NAME . ' - Setores';

        $modeloSetores = $this->load_model('recursos/setores');

        $conteudo = ABSPATH . '/views/recursos/setores.view.php';
        require ABSPATH . '/views/painel/painel.view.php';
    }

    public function integracoes()
    {
        $this->title = SYS_NAME . ' - Integrações';

        if (!$this->logged_in) {
            $this->logout();

            $this->goto_login();

            return;
        }

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        $modeloEvento = $this->load_model('eventos/eventos');
        $modeloComponentes = $this->load_model('eventos/componentes');
        $modeloIntegracoes = $this->load_model('recursos/integracoes');

        if (chk_array($this->parametros, 0) == 'adicionar') {
            $this->title = SYS_NAME . ' - Adicionar Evento';

            $conteudo = ABSPATH . '/views/recursos/form.view.php';
        } else {
            $this->title = SYS_NAME . ' - Integrações do Evento';

            $conteudo = ABSPATH . '/views/recursos/integracoes.view.php';
        }

        require ABSPATH . '/views/painel/painel.view.php';
    }
}
