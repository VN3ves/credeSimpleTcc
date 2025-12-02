<?php
class MVC
{
    private $controlador;
    private $acao;
    private $parametros;
    private $errorController = ABSPATH . '/core/controllers/ErrorController.php';

    public function __construct()
    {
        $this->get_url_data();

        if (!$this->controlador) {
            require_once ABSPATH . '/controllers/dashboard.controller.php';
            $this->controlador = new DashBoardController();

            $this->controlador->index();
            return;
        }

        if (
            $this->controlador == 'LoginController' ||
            $this->controlador == 'LogoutController' ||
            $this->controlador == 'PessoasController' ||
            $this->controlador == 'UsuariosController'
        ) {
            require_once ABSPATH . '/core/controllers/' . $this->controlador . '.php';
        } else {

            if (!file_exists(ABSPATH . '/controllers/' . $this->controlador . '.php')) {

                require_once $this->errorController;
                $this->controlador = new ErrorController();

                $this->controlador->index();

                return;
            }

            require_once ABSPATH . '/controllers/' . $this->controlador . '.php';
        }

        $this->controlador = preg_replace('/[^a-zA-Z]/i', '', $this->controlador);

        if (!class_exists($this->controlador)) {

            require_once $this->errorController;
            $this->controlador = new ErrorController();

            $this->controlador->controller_not_found();

            return;
        }

        $this->controlador = new $this->controlador($this->parametros);

        $this->acao = preg_replace('/[^a-zA-Z]/i', '', $this->acao ?? '');

        if (method_exists($this->controlador, $this->acao)) {
            $this->controlador->{$this->acao}($this->parametros);

            return;
        }

        if (!$this->acao && method_exists($this->controlador, 'index')) {
            $this->controlador->index($this->parametros);

            return;
        }


        require_once $this->errorController;
        $this->controlador = new ErrorController();

        $this->controlador->index();
        return;
    }

    public function get_url_data()
    {
        if (isset($_GET['path'])) {
            $path = $_GET['path'];

            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);

            $path = explode('/', $path);

            $this->controlador  = chk_array($path, 0);

            if ($this->controlador == 'login') {
                $this->controlador = 'LoginController';
            } else if ($this->controlador == 'logout') {
                $this->controlador = 'LogoutController';
            } else if ($this->controlador == 'pessoas') {
                $this->controlador = 'PessoasController';
            } else if ($this->controlador == 'usuarios') {
                $this->controlador = 'UsuariosController';
            }else {
                $this->controlador .= '.controller';
            }
            $this->acao = chk_array($path, 1);

            if (chk_array($path, 2)) {
                unset($path[0]);
                unset($path[1]);

                $this->parametros = array_values($path);
            }
        }
    }
}
