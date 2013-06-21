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
	require_once('../_classes/_MODEL/Persistivel.class.php');
	
	class Usuario extends Persistivel {
		const HABILITA_SIM = "S";
		const HABILITA_NAO = "N";
		private $codigo;
		private $nome;
		private $email;
		private $login;
		private $senha;
		private $nivel;
		private $habil;
		
		public function setCodigo($codigo) { $this->codigo = $codigo; }
		public function getCodigo() { return $this->codigo;	}
		
		public function setNome($nome) { $this->nome = $nome; }
		public function getNome() {	return $this->nome;	}
		
		public function setEmail($email) { $this->email = $email; }
		public function getEmail() { return $this->email; }
		
		public function setLogin($login) { $this->login = $login; }
		public function getLogin() { return $this->login; }
		
		public function setSenha($senha) { $this->senha = $senha; }
		public function getSenha() { return $this->senha; }
		
		public function setNivel($nivel) { $this->nivel = $nivel; }
		public function getNivel() { return $this->nivel; }
		
		public function setHabil($habil){ $this->habil = $habil; }
		public function getHabil(){ return $this->habil; }
		
		public function getChavePrimaria() { return $this->getCodigo(); }
	}
?>