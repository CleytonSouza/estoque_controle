<?php 
	require_once ('_classes/_HELPER/Helper.class.php');
	require_once ('_classes/_RN/FornecedorRN.class.php');
	
	class FornecedorHelper extends Helper {
		private $fornecedorRN;
		
		public function __construct() {
			$this->fornecedorRN = new FornecedorRN();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->fornecedorRN->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->fornecedorRN->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->fornecedorRN->excluir($banco, $filtro);
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->fornecedorRN->consultar($banco, $campos, $filtro);
		}
		
		public function listarFornecedores(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->fornecedorRN->listarFornecedores($banco, $campos, $filtro);
		}
		
		public function validaDados($vars, $camposValores) {
			throw new Exception("Método validaDados da classe FornecedorHelper não implementado.");
		}
		
		public function getMaxId(DAOBanco $banco, $campos) {
			return $this->fornecedorRN->getMaxId($banco, $campos);
		}
	}
?>