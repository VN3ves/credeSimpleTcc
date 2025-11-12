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
    }
}
