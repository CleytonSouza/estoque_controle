<?php 
	require_once('../_classes/_MODEL/Persistivel.class.php');
	
	class Produto extends Persistivel {		
		private $codigo;
		private $nome;
		private $preco;
		private $qtde;
		private $datacadastro;
		private $habil;
		
		public function setCodigo($codigo) { $this->codigo = $codigo; }
		public function getCodigo() { return $this->codigo;	}
		
		public function setNome($nome) {	$this->nome = $nome; }
		public function getNome() { return $this->nome; }
		
		public function setPreco($preco) { $this->preco = $preco; }
		public function getPreco() {	return $this->preco; }
		
		public function setQtde($qtde) { $this->qtde = $qtde; }
		public function getQtde() {	return $this->qtde; }
		
		public function setDataCadastro($datacadastro) { $this->datacadastro = $datacadastro; }
		public function getDataCadastro() {	return $this->datacadastro;	}
		
		public function setHabil($habil){ $this->habil = $habil; }
		public function getHabil(){ return $this->habil; }
		
		public function getChavePrimaria() { return $this->getCodigo(); }
	}
?>