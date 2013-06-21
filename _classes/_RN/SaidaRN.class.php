<?php 
	require_once ('_classes/_RN/RN.class.php');
	require_once ('_classes/_DAO/persistivel/SaidaDAOPersistivel.class.php');
	
	class SaidaRN extends RN {
		private $saidaDAO;
		
		public function __construct() {
			$this->saidaDAO = new SaidaDAOPersistivel();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->saidaDAO->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->saidaDAO->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				//$banco->startTransaction();
				$this->saidaDAO->excluir($banco, $filtro);
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
			return $this->saidaDAO->consultar($banco, $campos, $filtro);
		}
		
		public function habilitarSaida(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				$camposValores["habilita"] = 1;
				$this->saidaDAO->alterar($banco, $camposValores, $filtro);
			}
			catch (Exception $e) {
				throw new Exception("Erro ao habilitar Saida. Erro: " . $e->getMessage());
			}
		}
		
		public function desabilitarSaida(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				$camposValores["habilita"] = 0;
				$this->saidaDAO->alterar($banco, $camposValores, $filtro);
			}
			catch (Exception $e) {
				throw new Exception("Erro ao desabilitar Saida. Erro: " . $e->getMessage());
			}
		}
		
		public function listarSaidas(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			$resultados = $this->saidaDAO->consultar($banco, $campos, $filtro);
			return $this->montaListaArray($banco, $resultados);
		}
		
		public function getMaxId(DAOBanco $banco, $campo){
			return ($this->saidaDAO->getMaxId($banco, $campo))+1;
		}
		
		public function existeId(DAOBanco $banco, FiltroSQL $filtro = null){				
			return ($this->saidaDAO->existeId($banco, $filtro));
		}
		
		public function montaListaArray(DAOBanco $banco, array $resultados){
			$size = count($resultados);
			$saidas = array();
			
			if(isset($resultados))
			{
				$i = 1;
				foreach($resultados as $u)
				{
					$cod = $u->getCodigo();
					
					$saidas[$i]['cod']        = $cod;
					$saidas[$i]['data']       = $u->getData();
					$saidas[$i]['cliente']    = $u->getCliente();
					$saidas[$i]['produto']    = $u->getProduto();
					$saidas[$i]['qtde']       = $u->getQtde();					
					$saidas[$i]['cancelado']  = $u->getCancelado();	
					
					$i++;
				}
			}
			return $saidas;
		}
	}
?>