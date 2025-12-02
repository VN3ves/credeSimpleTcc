<?php
class EventosController extends MainController
{
    public $login_required = true;

    public function index()
    {
        $this->title = SYS_NAME . ' - Eventos';

        if (!$this->logged_in) {
            $this->logout();

            $this->goto_login();

            return;
        }

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        $modelo = $this->load_model('eventos/eventos');
        $modeloUsuarios = $this->load_model('usuarios/usuarios');
        $modeloCategorias = $this->load_model('eventos/categorias');
        $modeloLocais = $this->load_model('eventos/locais');
        $modeloComponentes = $this->load_model('eventos/componentes');
        $quantidadeSetores = $this->load_model('recursos/setores');
        $quantidadeLeitores = $this->load_model('recursos/leitores');

        //$modeloContratacoes = $this->load_model('eventos/contratacoes');


        if (chk_array($this->parametros, 0) == 'adicionar') {
            $this->title = SYS_NAME . ' - Adicionar Evento';

            $conteudo = ABSPATH . '/views/eventos/form.view.php';
        } elseif (chk_array($this->parametros, 0) == 'editar') {
            $this->title = SYS_NAME . ' - Editar Evento';

            $conteudo = ABSPATH . '/views/eventos/form.view.php';
        } elseif (chk_array($this->parametros, 0) == 'perfil') {
            $this->title = SYS_NAME . ' - Perfil do Evento';

            $conteudo = ABSPATH . '/views/eventos/perfil.view.php';
        } elseif (chk_array($this->parametros, 0) == 'setEvento') {
            $idPost = isset($_POST['idEvento']) ? $_POST['idEvento'] : 0;
            $nomeDB = isset($_POST['nomeDB']) ? $_POST['nomeDB'] : '';
            Log::info('Evento controller: setEvento -> ' . $idPost . ' ' . $nomeDB);
            $modelo->setEvento($idPost, $nomeDB);
            $this->goto_page('/');
            return;
        } elseif (chk_array($this->parametros, 0) == 'integracoes') {
            $this->title = SYS_NAME . ' - Integrações do Evento';

            $conteudo = ABSPATH . '/views/eventos/integracoes.view.php';
        } else {
            $conteudo = ABSPATH . '/views/eventos/eventos.view.php';
        }

        require ABSPATH . '/views/painel/painel.view.php';
    }

    public function json()
    {
        if (!$this->logged_in) {
            $this->logout();

            $this->goto_login();

            return;
        }

        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        if (chk_array($this->parametros, 0) == 'terceirizados') {
            $modelo = $this->load_model('eventos/contratacoes');

            require ABSPATH . '/views/eventos/terceirizados.json.view.php';
        }
    }

    public function upload()
    {
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();

        $modelo = $this->load_model('eventos/upload');

        require ABSPATH . '/views/eventos/upload.view.php';
    }
}
