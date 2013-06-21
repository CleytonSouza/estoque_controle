<?php 
	require_once ('_classes/_HELPER/Helper.class.php');
	require_once ('_classes/_RN/ProdutoRN.class.php');
	
	class ProdutoHelper extends Helper {
		private $produtoRN;
		
		public function __construct() {
			$this->produtoRN = new ProdutoRN();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->produtoRN->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->produtoRN->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->produtoRN->excluir($banco, $filtro);
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->produtoRN->consultar($banco, $campos, $filtro);
		}
		
		public function listarProdutos(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->produtoRN->listarProdutos($banco, $campos, $filtro);
		}
		
		public function habilitarProduto(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->produtoRN->habilitarProduto($banco, $filtro);
		}
		
		public function desabilitarProduto(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->produtoRN->desabilitarProduto($banco, $filtro);
		}
		
		public function getMaxId(DAOBanco $banco, $campos) {
			return $this->produtoRN->getMaxId($banco, $campos);
		}
		
		public function existeId(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->produtoRN->existeId($banco, $filtro);
		}
		
		public function validaDados($vars, $camposValores) {
			throw new Exception("Método validaDados da classe NoticiaHelper não implementado.");
		}
	}
?>