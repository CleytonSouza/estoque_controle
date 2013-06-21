<?php 
	require_once ('_classes/_DAO/DAOPersistivel.class.php');
	
	class EntradaDAOPersistivel extends DAOPersistivel {
		const NOME_TABELA = "ENTRADA";
		
		public function __construct() {
			parent::__construct(EntradaDAOPersistivel::NOME_TABELA);
		}
		
		public function incluir(DAOBanco $banco, $camposValores){
			return parent::incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return parent::alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			return parent::excluir($banco, $filtro);
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			$resultados = array();
			$resultados = parent::consultar($banco, $campos, $filtro);
			return $this->criaObjetos($resultados);
		}
		
		public function getMaxId(DAOBanco $banco, $campo, FiltroSQL $filtro = null) {
			$resultado = parent::getMaxId($banco, $campo, $filtro);
			foreach($resultado as $linha){
				foreach ($linha as $campo => $valor)
				{
					return $valor;
				}
			}
		}
		
		public function existeId(DAOBanco $banco, FiltroSQL $filtro = null) {
			return parent::existeId($banco, $filtro);
		}
		
		public function criaObjetos($resultados)
		{
			$results = array();
			
			foreach ($resultados as $linha)
			{
				$entrada = new Entrada();
				foreach ($linha as $campo => $valor)
				{
					if (strcasecmp($campo, "codigo") == 0) {
						$entrada->setCodigo($valor);
					}
					elseif (strcasecmp($campo, "data") == 0) {
						$entrada->setData($valor);
					}
					elseif (strcasecmp($campo, "fornecedor") == 0) {
						$entrada->setFornecedor($valor);
					}
					elseif (strcasecmp($campo, "produto") == 0) {
						$entrada->setProduto($valor);
					}
					elseif (strcasecmp($campo, "qtde") == 0) {
						$entrada->setQtde($valor);
					}
					elseif (strcasecmp($campo, "cancelado") == 0) {
						$entrada->setCancelado($valor);
					}
				}
				$results[] = $entrada;
			}
			return $results;
		}
	}
?>