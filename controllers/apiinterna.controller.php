<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


class ApiinternaController extends MainController
{
    public $login_required = true;
    private $modeloEmpresas;
    private $modeloParceiros;
    private $modeloImpressoras;
    private $modeloDepartamentos;
    private $modeloMarcas;
    private $modeloLocais;

    public function __construct()
    {
        parent::__construct();
        $this->modeloLocais = $this->load_model('eventos/locais');
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
    }

    public function getLocais()
    {
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
        $id = $_GET['id'];
        $local = $this->modeloLocais->getLocal($id);
        echo json_encode($local);
    }

}
