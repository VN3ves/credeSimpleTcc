<?php
class ApiController extends MainController
{
    public $login_required = true;

    private $modeloImpressoes;
    private $modeloEmpresas;
    private $modeloParceiros;
    private $modeloTokens;

    public function __construct()
    {
        // Construtor da classe pai
        parent::__construct();
        $this->modeloImpressoes = $this->load_model('impressoes/impressoes');
        $this->modeloEmpresas = $this->loadDefaultModel('EmpresasModel');
        $this->modeloParceiros = $this->load_model('parceiros/parceiros');
        $this->modeloTokens = $this->load_model('empresas/tokens');
    }

  
}
