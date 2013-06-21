<?php 
	require_once ('_classes/_DAO/DAOPersistivel.class.php');
	
	class SaidaDAOPersistivel extends DAOPersistivel {
		const NOME_TABELA = "saida";
		
		public function __construct() {
			parent::__construct(SaidaDAOPersistivel::NOME_TABELA);
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
				$saida = new Saida();
				foreach ($linha as $campo => $valor)
				{
					if (strcasecmp($campo, "codigo") == 0) {
						$saida->setCodigo($valor);
					}
					elseif (strcasecmp($campo, "data") == 0) {
						$saida->setData($valor);
					}
					elseif (strcasecmp($campo, "cliente") == 0) {
						$saida->setCliente($valor);
					}
					elseif (strcasecmp($campo, "produto") == 0) {
						$saida->setProduto($valor);
					}
					elseif (strcasecmp($campo, "qtde") == 0) {
						$saida->setQtde($valor);
					}
					elseif (strcasecmp($campo, "cancelado") == 0) {
						$saida->setCancelado($valor);
					}
				}
				$results[] = $saida;
			}
			return $results;
		}
	}
?>