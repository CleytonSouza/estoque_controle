<?php 
	require_once ('_classes/_MODEL/Produto.class.php');
	require_once ('_classes/_DAO/DAOPersistivel.class.php');
	
	class ProdutoDAOPersistivel extends DAOPersistivel {
		const NOME_TABELA = "PRODUTO";
		
		public function __construct() {
			parent::__construct(ProdutoDAOPersistivel::NOME_TABELA);
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
			$resultprodutos = array();
			
			foreach ($resultados as $linha)
			{
				$produto = new Produto();
				foreach ($linha as $campo => $valor)
				{
					if (strcasecmp($campo, "codigo") == 0) {
						$produto->setCodigo($valor);
					}
					elseif (strcasecmp($campo, "nome") == 0) {
						$produto->setNome($valor);
					}
					elseif (strcasecmp($campo, "preco") == 0) {
						$produto->setPreco($valor);
					}
					elseif (strcasecmp($campo, "qtde") == 0) {
						$produto->setQtde($valor);
					}
					elseif (strcasecmp($campo, "datacadastro") == 0) {
						$produto->setDataCadastro($valor);
					}
					elseif (strcasecmp($campo, "habil") == 0) {
						$produto->setHabil($valor);
					}
				}
				$resultprodutos[] = $produto;
			}
			return $resultprodutos;
		}
	}
?>