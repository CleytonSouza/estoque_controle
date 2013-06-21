<?php 
	require_once ('_classes/_HELPER/Helper.class.php');
	require_once ('_classes/_RN/EntradaRN.class.php');
	
	class EntradaHelper extends Helper {
		private $entradaRN;
		
		public function __construct() {
			$this->entradaRN = new EntradaRN();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->entradaRN->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->entradaRN->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->entradaRN->excluir($banco, $filtro);
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->entradaRN->consultar($banco, $campos, $filtro);
		}
		
		public function listarEntradas(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->entradaRN->listarEntradas($banco, $campos, $filtro);
		}
		
		public function getMaxId(DAOBanco $banco, $campos) {
			return $this->entradaRN->getMaxId($banco, $campos);
		}
		
		public function existeId(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->entradaRN->existeId($banco, $filtro);
		}
		
		public function validaDados($vars, $camposValores) {
			throw new Exception("Método validaDados da classe EntradaHelper não implementado.");
		}
	}
?>