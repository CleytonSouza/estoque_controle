<?php
	require_once ('_classes/_RN/RN.class.php');
	require_once ('_classes/_DAO/persistivel/EntradaDAOPersistivel.class.php');
	
	class EntradaRN extends RN {
		private $entradaDAO;
		
		public function __construct() {
			$this->entradaDAO = new EntradaDAOPersistivel();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->entradaDAO->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->entradaDAO->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				//$banco->startTransaction();
				$this->entradaDAO->excluir($banco, $filtro);
				//$banco->commit();
			}
			catch (Exception $e) {
				/*try {
					$banco->rollback();
				}
				catch (Exception $e) {
					throw new Exception("Erro ao efetuar rollback. Erro: " . $e->getMessage());
				}*/
				throw new Exception("Erro ao excluir usuário. Erro: " . $e->getMessage());
			}
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->entradaDAO->consultar($banco, $campos, $filtro);
		}
		
		public function habilitarEntrada(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				$camposValores["habilita"] = 1;
				$this->entradaDAO->alterar($banco, $camposValores, $filtro);
			}
			catch (Exception $e) {
				throw new Exception("Erro ao habilitar Saida. Erro: " . $e->getMessage());
			}
		}
		
		public function desabilitarEntrada(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				$camposValores["habilita"] = 0;
				$this->entradaDAO->alterar($banco, $camposValores, $filtro);
			}
			catch (Exception $e) {
				throw new Exception("Erro ao desabilitar Saida. Erro: " . $e->getMessage());
			}
		}
		
		public function listarEntradas(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			$resultados = $this->entradaDAO->consultar($banco, $campos, $filtro);
			return $this->montaListaArray($banco, $resultados);
		}
		
		public function getMaxId(DAOBanco $banco, $campo){
			return ($this->entradaDAO->getMaxId($banco, $campo))+1;
		}
		
		public function existeId(DAOBanco $banco, FiltroSQL $filtro = null){				
			return ($this->entradaDAO->existeId($banco, $filtro));
		}
		
		public function montaListaArray(DAOBanco $banco, array $resultados){
			$size = count($resultados);
			$entradas = array();
			
			if(isset($resultados))
			{
				$i = 1;
				foreach($resultados as $u)
				{
					$cod = $u->getCodigo();
					
					$entradas[$i]['cod']        = $cod;
					$entradas[$i]['data']       = $u->getData();
					$entradas[$i]['fornecedor'] = $u->getFornecedor();
					$entradas[$i]['produto']    = $u->getProduto();
					$entradas[$i]['qtde']       = $u->getQtde();					
					$entradas[$i]['cancelado']  = $u->getCancelado();	
					
					$i++;
				}
			}
			return $entradas;
		}
	}
?>