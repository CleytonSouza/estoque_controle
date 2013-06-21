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
	require_once ('_classes/_DAO/DAOBanco.class.php');

	class MySQLDAOBanco extends DAOBanco {
		private $servidor;
		private $usuario;
		private $senha;
		private $banco;
		private $conexao;
		private $comandoSQL;
		private static $instancia = null;
		
		private function __construct($servidor, $usuario, $senha, $banco) {
			parent::inicializaConstrutor(false, true);
			$this->setServidor($servidor);
			$this->setUsuario($usuario);
			$this->setSenha($senha);
			$this->setBanco($banco);
		}
		
		public function abreConexao() {
			if (!$this->getConexao()) {
				$this->setConexao(mysql_connect($this->getServidor() , $this->getUsuario() , $this->getSenha()));
				if (!$this->getConexao()) {
					throw new Exception("Nao foi poss�vel conectar no banco de dados. Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
				}
				else {
					if (!mysql_select_db($this->getBanco() , $this->getConexao())) {
						throw new Exception("Nao foi poss�vel selecionar o banco de dados. Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
					}
				}
			}
			else {
				if (!is_resource($this->getConexao())) {
					$this->setConexao(mysql_connect($this->getServidor() , $this->getUsuario() , $this->getSenha()));
				}
				if (!$this->getConexao()) {
					throw new Exception("Nao foi poss�vel conectar no banco de dados. Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
				}
				else {
					if (!mysql_select_db($this->getBanco() , $this->getConexao())) {
						throw new Exception("Nao foi poss�vel selecionar o banco de dados. Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
					}
				}
			}
			return true;
		}
		
		public function fechaConsulta() {
			return mysql_free_result($this->getComandoSql());
		}
		
		public function fechaConexao() {
			mysql_close($this->getConexao());
		}
		
		public function incluir($sql) {
			$this->setComandoSQL(mysql_query($sql));
			if (!$this->getComandoSql()) {
				throw new Exception("N�o foi poss�vel executar a query de inser��o. Query: " . $sql . " Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
			}
			else {
				return mysql_insert_id();
			}
		}
		
		public function alterar($sql) {
			$this->setComandoSQL(mysql_query($sql));
			if (!$this->getComandoSql()) {
				throw new Exception("N�o foi poss�vel executar a query de altera��o. Query: " . $sql . " Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
			}
			else {
				return true;
			}
		}
		
		public function excluir($sql) {
			$this->setComandoSQL(mysql_query($sql));
			if (!$this->getComandoSql()) {
				throw new Exception("N�o foi poss�vel executar a query de exclus�o. Query: " . $sql . " Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
			}
			else {
				return true;
			}
		}
		
		public function consultar($sql) {
			$registros = array();
			$linha = null;
			$conta = 0;
			$this->setComandoSQL(mysql_query($sql));
			if (!$this->getComandoSql()) {
				throw new Exception("N�o foi poss�vel executar a query de consulta. Query: " . $sql . " Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
			}
			else {
				while ($linha = mysql_fetch_array($this->getComandoSql() , MYSQL_ASSOC)) {
					$registros[$conta] = $linha;
					$conta++;
				}
				$this->fechaConsulta();
				return $registros;
			}
		}
		
		public function commit() {
			if ($this->isEmTransacao() && !$this->isAutoCommit()) {
				$this->setComandoSQL(mysql_query("COMMIT"));
				if (!$this->getComandoSql()) {
					throw new Exception("N�o foi poss�vel commitar a transa��o." . " Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
				}
				else {
					$this->setComandoSQL(mysql_query("SET AUTOCOMMIT=1"));
					if (!$this->getComandoSql()) {
						throw new Exception("N�o foi poss�vel configurar a transa��o para autocommit." . " Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
					}
					else {
						$this->setAutoCommit(true);
						$this->setEmTransacao(false);
						return true;
					}
				}
			}
			return false;
		}
		
		public function rollback() {
			if ($this->isEmTransacao() && !$this->isAutoCommit()) {
				$this->setComandoSQL(mysql_query("ROLLBACK"));
				if (!$this->getComandoSql()) {
					throw new Exception("N�o foi poss�vel efetuar o rollback da transa��o." . " Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
				}
				else {
					$this->setComandoSQL(mysql_query("SET AUTOCOMMIT=1"));
					if (!$this->getComandoSql()) {
						throw new Exception("N�o foi poss�vel configurar a transa��o para autocommit." . " Erro: " . mysql_error() . ". C�digo: " . mysql_errno());
					}
					else {
						$this->setAutoCommit(true);
						$this->setEmTransacao(false);
						return true;
					}
				}
			}
			return false;
		}
		
		public function startTransaction() {
			if (!$this->isEmTransacao() && $this->isAutoCommit()) {
				$this->setComandoSQL(mysql_query("SET AUTOCOMMIT=0"));
				if (!$this->getComandoSql()) {
					throw new Exception("Nao foi possivel configurar a transacao para autocommit." . " Erro: " . mysql_error() . ". Codigo: " . mysql_errno());
				}
				else {
					$this->setAutoCommit(false);
					$this->setEmTransacao(true);
					return true;
				}
			}
			return false;
		}
		
		public function setServidor($servidor) {
			$this->servidor = $servidor;
		}
		
		public function getServidor() {
			return $this->servidor;
		}
		
		public function setUsuario($usuario) {
			$this->usuario = $usuario;
		}
		
		public function getUsuario() {
			return $this->usuario;
		}
		
		public function setSenha($senha) {
			$this->senha = $senha;
		}
		
		public function getSenha() {
			return $this->senha;
		}
		
		public function setBanco($banco) {
			$this->banco = $banco;
		}
		
		public function getBanco() {
			return $this->banco;
		}
		
		public function setConexao($conexao) {
			$this->conexao = $conexao;
		}
		public function getConexao() {
			return $this->conexao;
		}
		
		public function setComandoSQL($comandoSQL) {
			$this->comandoSQL = $comandoSQL;
		}
		
		public function getComandoSQL() {
			return $this->comandoSQL;
		}
		
		public static function getInstancia($servidor, $usuario, $senha, $banco) {
			if (is_null(self::$instancia)) {
				self::$instancia = new self($servidor, $usuario, $senha, $banco);
			}
			return self::$instancia;
		}
	}
?>