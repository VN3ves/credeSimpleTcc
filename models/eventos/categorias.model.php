<?php 
class CategoriasModel extends MainModel {
    public $form_data;
	public $form_msg;
	public $db;

    private $categoria;
	
    private $erro;

    public function __construct($db = false, $controller = null){
		$this->db = $db;
		
		$this->controller = $controller;
		
		$this->parametros = $this->controller->parametros;

		$this->userdata = $this->controller->userdata;
	}

    public function getCategorias($filtros = null){
        $getCategoriasService = $this->getService('CategoriasServices', 'GetCategorias');
        return $getCategoriasService->getCategorias($filtros);
    }

}