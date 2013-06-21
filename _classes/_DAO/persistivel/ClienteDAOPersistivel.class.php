<?php
	require_once ('_classes/_DAO/DAOPersistivel.class.php');
	
	class ClienteDAOPersistivel extends DAOPersistivel {
		const NOME_TABELA = "CLIENTE";
		
		public function __construct() {
			parent::__construct(ClienteDAOPersistivel::NOME_TABELA);
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
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
		
		public function criaObjetos($resultados) {
			$results = array();
			foreach ($resultados as $linha) {
				$cliente = new Cliente();
				foreach ($linha as $campo => $valor) {
					if (strcasecmp($campo, "codigo") == 0) {
						$cliente->setCodigo($valor);
					}
					elseif (strcasecmp($campo, "nome") == 0) {
						$cliente->setNome($valor);
					}
					elseif (strcasecmp($campo, "habil") == 0) {
						$cliente->setHabil($valor);
					}
				}
				$results[] = $cliente;
			}
			return $results;
		}
	}
?>