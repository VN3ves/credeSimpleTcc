<?php
class LoginController extends MainController
{
    public function index()
    {
        if ($this->logged_in) {
            $this->goto_page('/');
        }

        if (chk_array($this->parametros, 0) == 'esqueci-minha-senha') {
            $modeloUsuarios = $this->load_model('usuarios/usuarios');

            $this->title = SYS_NAME . ' - Esqueci Minha Senha';

            require ABSPATH . '/core/views/login/esqueci-minha-senha.view.php';
        } else {
            $this->title = SYS_NAME . ' - Login';

            require ABSPATH . '/core/views/login/login.view.php';
        }
    }
}
