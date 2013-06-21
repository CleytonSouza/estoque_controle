<?php 
/**
 * Código-fonte do livro "PHP Profissional"
 * Autores: Alexandre Altair de Melo <alexandre@phpsc.com.br>
 *          Mauricio G. F. Nascimento <mauricio@prophp.com.br>
 * 
 * 
 *
 * http://www.novatec.com.br/livros/phppro
 * ISBN: 978-85-7522-141-9
 * Editora Novatec, 2008 - todos os direitos reservados
 *
 * LICENÇA: Este arquivo-fonte está sujeito a Atribuição 2.5 Brasil, da licença Creative Commons,
 * que encontra-se disponível no seguinte endereço URI: http://creativecommons.org/licenses/by/2.5/br/
 * Se você não recebeu uma cópia desta licença, e não conseguiu obtê-la pela internet, por favor, 
 * envie uma notificação aos seus autores para que eles possam enviá-la para você imediatamente.
 *
 *
 * Source-code of "PHP Profissional" book
 * Authors: Alexandre Altair de Melo <alexandre@phpsc.com.br>
 *          Mauricio G. F. Nascimento <mauricio@prophp.com.br>
 *
 * http://www.novatec.com.br/livros/phppro
 * ISBN: 978-85-7522-141-9
 * Editora Novatec, 2008 - all rights reserved
 *
 * LICENSE: This source file is subject to Attibution version 2.5 Brazil of the Creative Commons 
 * license that is available through the following URI:  http://creativecommons.org/licenses/by/2.5/br/
 * If you did not receive a copy of this license and are unable to obtain it through the web, please 
 * send a note to the authors so they can mail you a copy immediately.
 *
 */
	require_once ('_classes/_DAO/persistivel/UsuarioDAOPersistivel.class.php');
	require_once ('_classes/_RN/RN.class.php');
	
	class UsuarioRN extends RN {
		private $usuarioDAO;
		
		public function __construct() {
			$this->usuarioDAO = new UsuarioDAOPersistivel();
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			return $this->usuarioDAO->incluir($banco, $camposValores);
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			return $this->usuarioDAO->alterar($banco, $camposValores, $filtro);
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			try {
				$banco->startTransaction();
				$this->usuarioDAO->excluir($banco, $filtro);
				$banco->commit();
			}
			catch (Exception $e) {
				try {
					$banco->rollback();
				}
				catch (Exception $e) {
					throw new Exception("Erro ao efetuar rollback. Erro: " . $e->getMessage());
				}
				throw new Exception("Erro ao excluir usuário. Erro: " . $e->getMessage());
			}
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			return $this->usuarioDAO->consultar($banco, $campos, $filtro);
		}
		
		public function listarUsuarios(DAOBanco $banco, $campos, FiltroSQL $filtro = null) {
			$resultados = $this->usuarioDAO->consultar($banco, $campos, $filtro);
			return $this->montaListaArray($banco, $resultados);
		}
		
		public function montaListaArray(DAOBanco $banco, array $resultados){
			$size = count($resultados);
			
			if(isset($resultados))
			{
				$usuarios = array();
				$i = 1;
				foreach($resultados as $u)
				{
					$usuarios[$i]['cod']   = $u->getCodigo();
					$usuarios[$i]['nome']  = $u->getNome();
					$usuarios[$i]['email'] = $u->getEmail();
					$usuarios[$i]['login'] = $u->getLogin();
					$usuarios[$i]['senha'] = $u->getSenha();
					$usuarios[$i]['nivel'] = $u->getNivel();
					$usuarios[$i]['habil'] = $u->getHabil();
					$i++;
				}
			}
			return $usuarios;
		}
		
		public function validarLoginSenha(DAOBanco $banco, $login, $senha) {
			try {
				$filtro = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_IGUAL, array("login" => $login, "senha" => $senha));
				$resultados = $this->consultar($banco, null, $filtro);
				if (count($resultados) > 0) {
					return true;
				}
			}
			catch (Exception $e) {
				throw new Exception("Não foi possível validar login. Erro: " . $e->getMessage());
			}
			return false;
		}
		
		public function getMaxId(DAOBanco $banco, $campo){
			return ($this->usuarioDAO->getMaxId($banco, $campo))+1;
		}
	}
?>