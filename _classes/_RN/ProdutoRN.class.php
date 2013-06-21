<?php 
	require_once ('_classes/_RN/RN.class.php');
	require_once ('_classes/_DAO/persistivel/ProdutoDAOPersistivel.class.php');
	
	class ProdutoRN extends RN {
		private $produtoDAO;
		
		public function __construct() {
			$this->produtoDAO = new ProdutoDAOPersistivel();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->produtoDAO->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->produtoDAO->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				//$banco->startTransaction();
				$this->produtoDAO->excluir($banco, $filtro);
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
			return $this->produtoDAO->consultar($banco, $campos, $filtro);
		}
		
		public function habilitarProduto(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				$camposValores["habilita"] = 1;
				$this->produtoDAO->alterar($banco, $camposValores, $filtro);
			}
			catch (Exception $e) {
				throw new Exception("Erro ao habilitar produto. Erro: " . $e->getMessage());
			}
		}
		
		public function desabilitarProduto(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				$camposValores["habilita"] = 0;
				$this->produtoDAO->alterar($banco, $camposValores, $filtro);
			}
			catch (Exception $e) {
				throw new Exception("Erro ao desabilitar noticia. Erro: " . $e->getMessage());
			}
		}
		
		public function listarProdutos(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			$resultados = $this->produtoDAO->consultar($banco, $campos, $filtro);
			return $this->montaListaArray($banco, $resultados);
		}
		
		public function getMaxId(DAOBanco $banco, $campo){
			return ($this->produtoDAO->getMaxId($banco, $campo))+1;
		}
		
		public function existeId(DAOBanco $banco, FiltroSQL $filtro = null){				
			return ($this->produtoDAO->existeId($banco, $filtro));
		}
		
		public function montaListaArray(DAOBanco $banco, array $resultados){
			$size = count($resultados);
			$produtos = array();
			
			if(isset($resultados))
			{
				$i = 1;
				foreach($resultados as $u)
				{
					$cod = $u->getCodigo();
					
					$produtos[$i]['cod']          = $cod;
					$produtos[$i]['nome']         = $u->getNome();
					$produtos[$i]['preco']        = number_format($u->getPreco(),2,',','.');
					$produtos[$i]['qtde']         = $u->getqtde();	
					$produtos[$i]['datacadastro'] = $u->getDataCadastro();
					$produtos[$i]['habil']        = $u->getHabil();
					$i++;
				}
			}
			return $produtos;
		}
	}
?>