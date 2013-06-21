<?php 
	require_once ('_classes/_RN/RN.class.php');
	require_once ('_classes/_DAO/persistivel/FornecedorDAOPersistivel.class.php');
	
	class FornecedorRN extends RN {
		private $fornecedorDAO;
		
		public function __construct() {
			$this->fornecedorDAO = new FornecedorDAOPersistivel();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->fornecedorDAO->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->fornecedorDAO->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				//$banco->startTransaction();
				$this->fornecedorDAO->excluir($banco, $filtro);
				//$banco->commit();
			}
			catch (Exception $e) {
				/*try {
					$banco->rollback();
				}
				catch (Exception $e) {
					throw new Exception("Erro ao efetuar rollback. Erro: " . $e->getMessage());
				}*/
				throw new Exception("Erro ao excluir. Erro: " . $e->getMessage());
			}
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->fornecedorDAO->consultar($banco, $campos, $filtro);
		}
		
		public function listarFornecedores(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			$resultados = $this->fornecedorDAO->consultar($banco, $campos, $filtro);
			return $this->montaListaArray($banco, $resultados);
		}
		
		public function montaListaArray(DAOBanco $banco, array $resultados){
			$size = count($resultados);
			
			if(isset($resultados))
			{
				$i = 1;
				foreach($resultados as $u)
				{					
					$fornecedores[$i]['cod']   = $u->getCodigo();
					$fornecedores[$i]['nome']  = $u->getNome();
					$fornecedores[$i]['habil'] = $u->getHabil();
					$i++;
				}
			}
			return $fornecedores;
		}
		
		public function getMaxId(DAOBanco $banco, $campo){
			return ($this->fornecedorDAO->getMaxId($banco, $campo))+1;
		}
	}
?>