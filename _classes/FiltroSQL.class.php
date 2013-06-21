<?php 
/**
 * C�digo-fonte do livro "PHP Profissional"
 * Autores: Alexandre Altair de Melo <alexandre@phpsc.com.br>
 *          Mauricio G. F. Nascimento <mauricio@prophp.com.br>
 *
 * http://www.novatec.com.br/livros/phppro
 * ISBN: 978-85-7522-141-9
 * Editora Novatec, 2008 - todos os direitos reservados
 *
 * LICEN�A: Este arquivo-fonte est� sujeito a Atribui��o 2.5 Brasil, da licen�a Creative Commons, 
 * que encontra-se dispon�vel no seguinte endere�o URI: http://creativecommons.org/licenses/by/2.5/br/
 * Se voc� n�o recebeu uma c�pia desta licen�a, e n�o conseguiu obt�-la pela internet, por favor, 
 * envie uma notifica��o aos seus autores para que eles possam envi�-la para voc� imediatamente.
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
	
	class FiltroSQL {
		private $conector;
		private $operador;
		private $camposFiltro;
		const CONECTOR_E = 'AND';
		const CONECTOR_OU = 'OR';
		const OPERADOR_ENTRE = 'BETWEEN';
		const OPERADOR_EM = 'IN';
		const OPERADOR_CONTEM = 'LIKE';
		const OPERADOR_MAIOR = '>';
		const OPERADOR_MENOR = '<';
		const OPERADOR_MAIOR_IGUAL = '>=';
		const OPERADOR_MENOR_IGUAL = '<=';
		const OPERADOR_IGUAL = '=';
		const OPERADOR_META_PIPE = '|';
		const OPERADOR_META_DOIS_PONTOS = ':';
		const OPERADOR_META_ASTERISCO = '*';
		const OPERADOR_IS_NULL = 'is null';
		const OPERADOR_IS_NOT_NULL = 'is not null';
		
		public function __construct($conector, $operador, $camposFiltro) {
			$this->setConector($conector);
			$this->setOperador($operador);
			$this->setCamposFiltro($camposFiltro);
		}
		
		public function setConector($conector) {
			$this->conector = $conector;
		}
		
		public function getConector() {
			return $this->conector;
		}
		
		public function setOperador($operador) {
			$this->operador = $operador;
		}
		
		public function getOperador() {
			return $this->operador;
		}
		
		public function setCamposFiltro($camposFiltro) {
			$this->camposFiltro = $camposFiltro;
		}
		
		public function getCamposFiltro() {
			return $this->camposFiltro;
		}
		
		public function adicionaCampoFiltro($campo, $valor) {
			$this->camposFiltro[$campo] = $valor;
		}
		
		public function removeCampoFiltro($campo) {
			unset($this->camposFiltro[$campo]);
		}
		
		public function retornaValorCampo($campo) {
			return $this->camposFiltro[$campo];
		}
	}
?>