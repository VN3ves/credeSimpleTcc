<?php
class ErrorController extends MainController {
    public $errorCode = 'ERRO 404';
    public $errorMessage = 'Página não encontrada!';
    
    public function index() {
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
        $this->errorCode = "ERRO 404";
		$this->errorMessage = "Ooops, página não encontrada!";
		
		require ABSPATH . '/core/views/404.view.php';
    }

    public function controller_not_found() {
        $this->errorCode = "ERRO 404";
		$this->errorMessage = "Ooops, controller não encontrado!";
		
		require ABSPATH . '/core/views/404.view.php';
    }
	
}