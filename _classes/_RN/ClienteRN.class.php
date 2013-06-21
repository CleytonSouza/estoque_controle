<?php
	require_once ('_classes/_RN/RN.class.php');
	require_once ('_classes/_DAO/persistivel/ClienteDAOPersistivel.class.php');
	
	class ClienteRN extends RN {
		private $clienteDAO;
		
		public function __construct() {
			$this->clienteDAO = new ClienteDAOPersistivel();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->clienteDAO->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->clienteDAO->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				//$banco->startTransaction();
				$this->clienteDAO->excluir($banco, $filtro);
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
			return $this->clienteDAO->consultar($banco, $campos, $filtro);
		}
		
		public function listarClientes(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			$resultados = $this->clienteDAO->consultar($banco, $campos, $filtro);
			return $this->montaListaArray($banco, $resultados);
		}
		
		public function montaListaArray(DAOBanco $banco, array $resultados){
			$size = count($resultados);
			
			if(isset($resultados))
			{
				$i = 1;
				foreach($resultados as $u)
				{					
					$clientes[$i]['cod']   = $u->getCodigo();
					$clientes[$i]['nome']  = $u->getNome();
					$clientes[$i]['habil'] = $u->getHabil();
					$i++;
				}
			}
			return $clientes;
		}
		
		public function getMaxId(DAOBanco $banco, $campo){
			return ($this->clienteDAO->getMaxId($banco, $campo))+1;
		}
	}
?>