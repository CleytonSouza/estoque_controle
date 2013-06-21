<?php 
	require_once ('_classes/_HELPER/Helper.class.php');
	require_once ('_classes/_RN/SaidaRN.class.php');
	
	class SaidaHelper extends Helper {
		private $saidaRN;
		
		public function __construct() {
			$this->saidaRN = new SaidaRN();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->saidaRN->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->saidaRN->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->saidaRN->excluir($banco, $filtro);
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->saidaRN->consultar($banco, $campos, $filtro);
		}
		
		public function listarSaidas(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->saidaRN->listarSaidas($banco, $campos, $filtro);
		}
		
		public function getMaxId(DAOBanco $banco, $campos) {
			return $this->saidaRN->getMaxId($banco, $campos);
		}
		
		public function existeId(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->saidaRN->existeId($banco, $filtro);
		}
		
		public function validaDados($vars, $camposValores) {
			throw new Exception("Método validaDados da classe EntradaHelper não implementado.");
		}
	}
?>