<?php
	require_once('../_classes/_MODEL/Persistivel.class.php');
	
	class Fornecedor extends Persistivel {
		private $codigo;
		private $nome;
		private $habil;
		
		public function setCodigo($codigo) { $this->codigo = $codigo; }
		public function getCodigo() { return $this->codigo;	}
		
		public function setNome($nome) { $this->nome = $nome; }
		public function getNome() {	return $this->nome;	}
		
		public function setHabil($habil){ $this->habil = $habil; }
		public function getHabil(){ return $this->habil; }
		
		public function getChavePrimaria() { return $this->getCodigo(); }
	}
?>