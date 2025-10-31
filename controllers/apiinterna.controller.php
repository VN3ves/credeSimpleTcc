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
        $this->modeloParceiros = $this->load_model('parceiros/parceiros');
        $this->modeloEmpresas = $this->loadDefaultModel('EmpresasModel');
        $this->modeloImpressoras = $this->load_model('impressoras/impressoras');
        $this->modeloDepartamentos = $this->load_model('empresas/departamentos');
        $this->modeloMarcas = $this->load_model('impressoras/marcas');
        $this->modeloLocais = $this->load_model('eventos/locais');
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
    }

    public function buscarEmpresas()
    {
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
        // Pega o termo da requisição POST
        $termo = $_GET['term'];
        $termo = filter_var($termo, FILTER_DEFAULT);
        $termo = strip_tags(trim($termo));

        // Busca as empresas usando o termo
        $empresas = $this->modeloEmpresas->buscarEmpresas($termo);

        // Retorna o resultado em JSON
        header('Content-Type: application/json');
        echo json_encode($empresas);
    }

    public function getLocais()
    {
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
        $id = $_GET['id'];
        $local = $this->modeloLocais->getLocal($id);
        echo json_encode($local);
    }

}
