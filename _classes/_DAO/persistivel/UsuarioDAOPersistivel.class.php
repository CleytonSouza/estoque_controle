<?php
/**
 * Código-fonte do livro "PHP Profissional"
 * Autores: Alexandre Altair de Melo <alexandre@phpsc.com.br>
 *          Mauricio G. F. Nascimento <mauricio@prophp.com.br>
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
	require_once ('_classes/_MODEL/Usuario.class.php');
	require_once ('_classes/_DAO/DAOPersistivel.class.php');
	
	class UsuarioDAOPersistivel extends DAOPersistivel {
		const NOME_TABELA = "USUARIO";
		
		public function __construct() {
			parent::__construct(UsuarioDAOPersistivel::NOME_TABELA);
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
			$resultusuarios = array();
			foreach ($resultados as $linha) {
				$usuario = new Usuario();
				foreach ($linha as $campo => $valor) {
					if (strcasecmp($campo, "codigo") == 0) {
						$usuario->setCodigo($valor);
					}
					elseif (strcasecmp($campo, "nome") == 0) {
						$usuario->setNome($valor);
					}
					elseif (strcasecmp($campo, "email") == 0) {
						$usuario->setEmail($valor);
					}
					elseif (strcasecmp($campo, "login") == 0) {
						$usuario->setLogin($valor);
					}
					elseif (strcasecmp($campo, "senha") == 0) {
						$usuario->setSenha($valor);
					}
					elseif (strcasecmp($campo, "nivel") == 0) {
						$usuario->setNivel($valor);
					}
					elseif (strcasecmp($campo, "habil") == 0) {
						$usuario->setHabil($valor);
					}
				}
				$resultusuarios[] = $usuario;
			}
			return $resultusuarios;
		}
	}
?>