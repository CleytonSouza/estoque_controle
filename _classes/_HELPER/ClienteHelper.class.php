<?php 
	require_once ('_classes/_HELPER/Helper.class.php');
	require_once ('_classes/_RN/ClienteRN.class.php');
	
	class ClienteHelper extends Helper {
		private $clienteRN;
		
		public function __construct() {
			$this->clienteRN = new ClienteRN();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->clienteRN->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->clienteRN->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			return $this->clienteRN->excluir($banco, $filtro);
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->clienteRN->consultar($banco, $campos, $filtro);
		}
		
		public function listarClientes(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->clienteRN->listarClientes($banco, $campos, $filtro);
		}
		
		public function validaDados($vars, $camposValores) {
			throw new Exception("Método validaDados da classe UsuarioHelper não implementado.");
		}
		
		public function validarLoginSenha(DAOBanco $banco, $login, $senha) {
			try {
				return $this->clienteRN->validarLoginSenha($banco, $login, $senha);
			}
			catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getMaxId(DAOBanco $banco, $campos) {
			return $this->clienteRN->getMaxId($banco, $campos);
		}
	}
?>