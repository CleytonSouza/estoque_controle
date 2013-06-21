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
	require_once ('_classes/Loader.class.php');
	
	abstract class DAOPersistivel {
		private $nomeTabela;
		
		public function __construct($nomeTabela) {
			$this->setNomeTabela($nomeTabela);
		}
		
		public function incluir(DAOBanco $banco, $camposValores) {
			$sql = "";
			$size = 0;
			$conta = 0;
			$sql = "INSERT INTO " . $this->getNomeTabela() . " (";
			$size = count($camposValores);
			foreach ($camposValores as $campo => $valor) {
				$sql.= $campo;
				if ($conta < ($size-1)) {
					$sql.= ", ";
				}
				$conta++;
			}
			$sql.= ") VALUES (";
			$conta = 0;
			foreach ($camposValores as $valor) {
				$sql.= $this->formataValor($valor, $conta, $size);
				$conta++;
			}
			$sql.= ")";
			
			//echo "SQL: ".$sql."<br>";
			
			if ($banco->abreConexao() == true) {
				$retorno = $banco->incluir($sql);
				$banco->fechaConexao();
				return $retorno;
			}
			else {
				return false;
			}
		}
		
		public function alterar(DAOBanco $banco, $camposValores, FiltroSQL $filtro = null) {
			$sql = "";
			$size = 0;
			$conta = 0;
			$sizeFiltro = 0; 
			$sql = "UPDATE " . $this->getNomeTabela() . " SET ";
			$size = count($camposValores);
			foreach ($camposValores as $campo => $valor) {
				$sql.= $campo . " = ";
				$sql.= $this->formataValor($valor, $conta, $size);
				$conta++;
			}
			if (!is_null($filtro)) {
				$sizeFiltro = count($filtro->getCamposFiltro());
				if ($sizeFiltro > 0) {
					$conta = 0;
					$sql.= " WHERE ";
					foreach ($filtro->getCamposFiltro() as $campo => $valor) {
						$sql.= $campo . $filtro->getOperador() . $this->formataValor($valor, 0, 0);
						if ($conta < ($sizeFiltro-1)) {
							$sql.= " " . $filtro->getConector() . " ";
						}
						$conta++;
					}
				}
				//echo "SQL: ".$sql."<br>";
			}
			if ($banco->abreConexao() == true) {
				$retorno = $banco->alterar($sql);
				$banco->fechaConexao();
				return $retorno;
			}
			else {
				return false;
			}
		}
		
		public function excluir(DAOBanco $banco, FiltroSQL $filtro = null) {
			$sql = "";
			$sizeFiltro = 0;
			$conta = 0;
			$sql = "DELETE FROM " . $this->getNomeTabela();
			if (!is_null($filtro)) {
				$sizeFiltro = count($filtro->getCamposFiltro());
				$sql.= " WHERE ";
				foreach ($filtro->getCamposFiltro() as $campo => $valor) {
					$sql.= $campo . $filtro->getOperador() . $this->formataValor($valor, 0, 0);
					if ($conta < ($sizeFiltro-1)) {
						$sql.= " " . $filtro->getConector() . " ";
					}
					$conta++;
				}
			}
			if ($banco->abreConexao() == true) {
				$retorno = $banco->excluir($sql);
				$banco->fechaConexao();
				return $retorno;
			}
			else {
				return false;
			}
		}
		
		public function consultar(DAOBanco $banco, $campos, FiltroSQL $filtro = null){
			$sql = "";
			$size = 0;
			$sizeFiltro = 0;
			$conta = 0;
			$res = null;
			$size = count($campos);
			if ($size > 0) {
				$sql = "SELECT ";
				foreach ($campos as $valor) {
					$sql.= $valor;
					if ($conta < ($size-1)) {
						$sql.= ", ";
					}
					$conta++;
				}
			}
			else {
				$sql = "SELECT * ";
			}
			$sql.= " FROM " . $this->getNomeTabela();
			$conta = 0;
			if (!is_null($filtro)) {
				$sizeFiltro = count($filtro->getCamposFiltro());
				$sql.= " WHERE ";
				foreach ($filtro->getCamposFiltro() as $campo => $valor)
				{
					if($filtro->getOperador() == "BETWEEN" && $conta == ($sizeFiltro-1))
					{
						$sql.= $this->formataValor($valor, 0, 0);
					}
					else
					{
						$sql.= $campo . " " . $filtro->getOperador() . " " . $this->formataValor($valor, 0, 0);	
					}
					
					if ($conta < ($sizeFiltro-1))
					{
						$sql.= " " . $filtro->getConector() . " ";
					}
					$conta++;
				}				
				//echo "SQL: ".$sql."<br>";
			}
			if ($banco->abreConexao() == true) {
				$res = $banco->consultar($sql);
				$banco->fechaConexao();
				return $res;
			}
			else {
				return false;
			}
		}
		
		private function formataValor($valor, $posAtual, $posTotal) {
			if (is_string($valor)) {
				if (!get_magic_quotes_gpc()) {
					$retorno = "'".addslashes($valor)."'";
				}
				else {
					$retorno = "'".$valor."'";
				}
			}
			else if (empty($valor)) {
				$retorno = "NULL";
			}
			else {
				$retorno = $valor;
			}
			if ($posAtual < ($posTotal-1)) {
				$retorno.= ", ";
			}
			return $retorno;
		}
		
		public function getMaxId(DAOBanco $banco, $campo, FiltroSQL $filtro = null){
			$sql = "";
			$res = null;
			
			if(isset($campo))
			{
				$sql = "SELECT max(".$campo.") FROM " . $this->getNomeTabela();
				
				if (!is_null($filtro)) 
				{
					$sizeFiltro = count($filtro->getCamposFiltro());
					
					$sql.= " WHERE ";
					
					foreach ($filtro->getCamposFiltro() as $campo => $valor)
					{
						if($filtro->getOperador() == "BETWEEN" && $conta == ($sizeFiltro-1))
						{
							$sql.= $this->formataValor($valor, 0, 0);
						}
						else
						{
							$sql.= $campo . " " . $filtro->getOperador() . " " . $this->formataValor($valor, 0, 0);	
						}
						
						if ($conta < ($sizeFiltro-1))
						{
							$sql.= " " . $filtro->getConector() . " ";
						}
						$conta++;
					}				
					//echo "SQL: ".$sql."<br>";
				}
			}
			
			if($banco->abreConexao() == true){
				$res = $banco->consultar($sql);
				$banco->fechaConexao();
				return $res;
			}
			else
			{
				return false;
			}
		}
		
		public function existeId(DAOBanco $banco, FiltroSQL $filtro = null){
			$sql = "";
			$res = null;
			
			if(isset($filtro))
			{
				$sql = "SELECT * FROM " . $this->getNomeTabela();
				
				if (!is_null($filtro)) 
				{
					$sizeFiltro = count($filtro->getCamposFiltro());
					
					$sql.= " WHERE ";
					
					foreach ($filtro->getCamposFiltro() as $campo => $valor)
					{
						$sql.= $campo . " " . $filtro->getOperador() . " " . $this->formataValor($valor, 0, 0);	
						
						if ($conta < ($sizeFiltro-1))
						{
							$sql.= " " . $filtro->getConector() . " ";
						}
						$conta++;
					}				
					//echo "SQL: ".$sql."<br>";
				}
				
			}
			
			if($banco->abreConexao() == true)
			{
				$res = $banco->consultar($sql);
				$banco->fechaConexao();
				
				if(isset($res[0])) 
					return true;
				else
					return false;
			}
			else
			{
				return false;
			}
		}
		
		public function fakeId($num){
			if($num < 10)
			{
				$resultado = "00".$num;		
			}
			else if($num < 100)
			{
				$resultado = "0".$num;
			}
			else if($num < 1000)
			{
				$resultado = $num;
			}
			
			return $resultado;
		}
		
		protected function getNomeTabela() {
			return strtolower($this->nomeTabela);
		}
		
		protected function setNomeTabela($nomeTabela) {
			$this->nomeTabela = $nomeTabela;
		}
		
		public abstract function criaObjetos($resultados);
	}
?>