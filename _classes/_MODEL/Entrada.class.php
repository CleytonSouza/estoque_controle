<?php 
	require_once('../_classes/_MODEL/Persistivel.class.php');
	
	class Entrada extends Persistivel {

		private $codigo;
		private $data;
		private $fornecedor;
		private $produto;
		private $qtde;
		private $cancelado;
		
		public function setCodigo($codigo) { $this->codigo = $codigo; }
		public function getCodigo() { return $this->codigo;	}
		
		public function setData($data) { $this->data = $data; }
		public function getData() {	return $this->data;	}
		
		public function setFornecedor($fornecedor) { $this->fornecedor = $fornecedor; }
		public function getFornecedor() { return $this->fornecedor; }
		
		public function setProduto($produto) { $this->produto = $produto; }
		public function getProduto() {	return $this->produto; }
		
		public function setQtde($qtde) { $this->qtde = $qtde; }
		public function getQtde() {	return $this->qtde; }
		
		public function setCancelado($cancelado) { $this->cancelado = $cancelado; }
		public function getCancelado() { return $this->cancelado;	}
		
		public function getChavePrimaria() { return $this->getCodigo(); }
	}
?>