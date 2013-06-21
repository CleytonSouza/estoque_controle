<?php
	$gmtDate = gmdate("D, d M Y H:i:s");
	header("Expires: {$gmtDate} GMT"); 
	header("Last-Modified: {$gmtDate} GMT"); 
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1");
	
	require_once ('_classes/FiltroSQL.class.php');
	require_once ('_classes/_MODEL/Entrada.class.php');
	require_once ('_classes/_HELPER/EntradaHelper.class.php');
	require_once ('_classes/_HELPER/ProdutoHelper.class.php');
	
	require_once ('_includes/confconexao.inc.php');
	require_once ('_includes/retornaconexao.inc.php');
	
	$banco      = $_SESSION[BANCO_SESSAO];
	$enthelper  = new EntradaHelper();
	$prodhelper = new ProdutoHelper();
	
	$acao   = isset($_POST['acao']) ? $_POST['acao'] : "";
	$cod    = isset($_POST['cod'])  ? $_POST['cod']  : null;
	$data   = isset($_POST['dt'])   ? $_POST['dt']   : null;
	$fornec = isset($_POST['fn'])   ? $_POST['fn']   : null;
	$prod   = isset($_POST['pd'])   ? $_POST['pd']   : null;
	$qtde   = isset($_POST['qt'])   ? $_POST['qt']   : null;
	
	$srccampo = isset($_POST['srccampo']) ? $_POST['srccampo'] : null;
	$dataini  = isset($_POST['dtini'])    ? $_POST['dtini']    : null;
	$datafim  = isset($_POST['dtfim'])    ? $_POST['dtfim']    : null;
	$srcfornc = isset($_POST['srcforn'])  ? $_POST['srcforn']  : null;
	$srccod   = isset($_POST['srccod'])   ? $_POST['srccod']   : null;
	
	
	/*
	$acao     = "cadastrar";
	
	$srccampo = "codigo";
	$srccod   = 1;
	
	$srccampo = "fornecedor";
	$srcfornc = 1;
	
	$srccampo = "periodo";
	$dataini  = "2013-05-07";
	$datafim  = "2013-05-08";
	
	$data = "2013-05-09";
	$fornec = 1;
	$prod = 1;
	$qtde = 100;*/
	
	if(isset($cod))
			$filtro = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_IGUAL, array("codigo" => $cod));
	
	switch($acao)
	{
		case 'consultar':
			if(isset($srccampo)){
				if(strcasecmp($srccampo, "codigo") == 0)
				{
					$filtroC = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_IGUAL, array("produto" => $srccod) );
					
					try {
						$lista = $enthelper->listarEntradas($banco, null, $filtroC);
						echo json_encode($lista);
					}
					catch (Exception $e) {
						$mensagem = "Não foi possível consultar entrada. Erro: ".$e->getMessage();
					}
				}
				if(strcasecmp($srccampo, "periodo") == 0)
				{
					$filtroC = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_ENTRE, array("data" => $dataini, $datafim) );
					
					try {
						$lista = $enthelper->listarEntradas($banco, null, $filtroC);
						echo json_encode($lista);
					}
					catch (Exception $e) {
						$mensagem = "Não foi possível consultar entrada. Erro: ".$e->getMessage();
					}
				}
				if(strcasecmp($srccampo, "fornecedor") == 0)
				{
					$filtroC = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_IGUAL, array("fornecedor" => $srcfornc) );
					
					try {
						$lista = $enthelper->listarEntradas($banco, null, $filtroC);
						echo json_encode($lista);
					}
					catch (Exception $e) {
						$mensagem = "Não foi possível consultar entrada. Erro: ".$e->getMessage();
					}
				}
			}
		break;
		
		case 'cancelar':
				if(!empty($qtde))
				{
					try {
						$camposValores['cancelado'] = 1;
						$enthelper->alterar($banco, $camposValores, $filtro);
						echo "cancelou";
					}
					catch (Exception $e) {
						echo "Não foi possível cancelar o registro: ".$cod.". Erro: ".$e->getMessage();
					}
					
					$filtro2 = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_IGUAL, array("codigo" => $prod));
					
					try{
						$produtos = $prodhelper->consultar($banco, null, $filtro2);
					}
					catch (Exception $e){
						echo "Não foi possível consultar o valor. Erro: " . $e->getMessage();
					}
					
					$qtatual = $produtos[0]->getQtde();
					
					try{
						$camposValQtde['qtde'] = ($qtatual - $qtde);
						$prodhelper->alterar($banco, $camposValQtde, $filtro2);
					}
					catch (Exception $e){
						echo "Não foi possível alterar o valor. Erro: " . $e->getMessage();
					}
				}
				else echo "Você deve preencher todos os campos!";
		break;
		
		case 'cadastrar':
				if(!empty($fornec) || !empty($prod)  || !empty($qtde))
				{
					try{
						$camposValores['data']       = $data;
						$camposValores['fornecedor'] = $fornec;
						$camposValores['produto']    = $prod;
						$camposValores['qtde']       = $qtde;
						$novoCodigo = $enthelper->incluir($banco, $camposValores);
						echo "cadastrou-".$novoCodigo;
					}
					catch (Exception $e){
						echo "Não foi possível incluir o registro. Erro: " . $e->getMessage();
					}
					
					$filtro2 = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_IGUAL, array("codigo" => $prod));
					
					try{
						$produtos = $prodhelper->consultar($banco, null, $filtro2);
					}
					catch (Exception $e){
						echo "Não foi possível consultar o valor. Erro: " . $e->getMessage();
					}
					
					$qtatual = $produtos[0]->getQtde();
					
					try{
						$camposValQtde['qtde'] = ($qtatual + $qtde);
						$prodhelper->alterar($banco, $camposValQtde, $filtro2);
					}
					catch (Exception $e){
						echo "Não foi possível alterar o valor. Erro: " . $e->getMessage();
					}
				}
				else echo "Você deve preencher todos os campos!";
		break;
		
		case 'listar':
				try {
					$lista = $enthelper->listarEntradas($banco, null);
					echo json_encode($lista);
				}
				catch (Exception $e) {
					echo "Não foi possível listar as entradas.".$e->getMessage();
				}
		break;
		
		default:
				try {
					$lista = $enthelper->listarEntradas($banco, null);
					echo json_encode($lista);
				}
				catch (Exception $e) {
					echo "Não foi possível listar as entradas.".$e->getMessage();
				}
		break;
	}
?>