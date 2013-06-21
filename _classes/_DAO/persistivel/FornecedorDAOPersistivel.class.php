<?php
	require_once ('_classes/_DAO/DAOPersistivel.class.php');
	
	class FornecedorDAOPersistivel extends DAOPersistivel {
		const NOME_TABELA = "fornecedor";
		
		public function __construct() {
			parent::__construct(FornecedorDAOPersistivel::NOME_TABELA);
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
				$fornecedor = new Fornecedor();
				foreach ($linha as $campo => $valor) {
					if (strcasecmp($campo, "codigo") == 0) {
						$fornecedor->setCodigo($valor);
					}
					elseif (strcasecmp($campo, "nome") == 0) {
						$fornecedor->setNome($valor);
					}
					elseif (strcasecmp($campo, "habil") == 0) {
						$fornecedor->setHabil($valor);
					}
				}
				$results[] = $fornecedor;
			}
			return $results;
		}
	}
?>