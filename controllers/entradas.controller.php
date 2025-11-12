<?php
class EntradasController extends MainController
{
    public $login_required = true;

    public function index()
    {
        $this->title = SYS_NAME . ' - Registros de Entrada';

        if (!$this->logged_in) {
            $this->logout();
            $this->goto_login();
            return;
        }

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        // Carrega os modelos necessários
        $modeloEntradas = $this->load_model('entradas/entradas');

        $conteudo = ABSPATH . '/views/entradas/entradas.view.php';

        require ABSPATH . '/views/painel/painel.view.php';
    }
}

