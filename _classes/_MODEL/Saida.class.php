<?php 
	require_once('../_classes/_MODEL/Persistivel.class.php');
	
	class Saida extends Persistivel {

		private $codigo;
		private $data;
		private $cliente;
		private $produto;
		private $qtde;
		private $cancelado;
		
		public function setCodigo($codigo) { $this->codigo = $codigo; }
		public function getCodigo() { return $this->codigo;	}
		
		public function setData($data) { $this->data = $data; }
		public function getData() {	return $this->data;	}
		
		public function setCliente($cliente) { $this->cliente = $cliente; }
		public function getCliente() { return $this->cliente; }
		
		public function setProduto($produto) { $this->produto = $produto; }
		public function getProduto() {	return $this->produto; }
		
		public function setQtde($qtde) { $this->qtde = $qtde; }
		public function getQtde() {	return $this->qtde; }
		
		public function setCancelado($cancelado) { $this->cancelado = $cancelado; }
		public function getCancelado() { return $this->cancelado;	}
		
		public function getChavePrimaria() { return $this->getCodigo(); }
	}
?>