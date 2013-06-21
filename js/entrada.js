var Entradas = {
	linhasNovas: 0,
	dadosAtuais: null,
	linhaEmEdicao: null,
	produtos: Array(0),
	fornecedores: Array(0),
	
	init: function( config ){
		this.config = config;
		
		$.ajaxSetup({
			url: 'entradas.php',
			type: 'POST'
		});
		
		this.iniciaTabela();
		this.iniciaTabelaProd();
		this.listarFornecedores();
	},
	
	zeraFiltro: function( ){		
		this.config.srccampo.val(0);
		this.config.srccod.val(0);
		this.config.srcforn.val(0);
		this.config.dataini.val("");
		this.config.datafim.val("");
	},
	
	hideFiltro: function( ){		
		this.config.pesquisaInputCod.hide();
		this.config.pesquisaInputData.hide();
		this.config.pesquisaInputForn.hide();
	},
	
	exibeFiltro: function( campo ){		
		this.hideFiltro();
		switch(campo)
		{
			case 'codigo':
				this.config.pesquisaInputCod.show();
			break;
			
			case 'periodo':
				this.config.pesquisaInputData.show();
			break;
			
			case 'fornecedor':
				this.config.pesquisaInputForn.show();
			break;
			
			default:
				this.limpaFiltro();
			break;
		}
	},
	
	validarConsulta: function(c){		
		if(c.src == 0) alert("Selecione um filtro!");
		else
		{
			switch(c.src)
			{
				case 'codigo':
					if(c.cod == 0) alert("Selecione o código!");
					else return true;
					break;
					
				case 'periodo':
					if(c.dti == "" || c.dti == null) alert("Selecione a data inicial!");
					else if(c.dtf == "" || c.dtf == null) alert("Selecione a data final!");
					else return true;
					break;
					
				case 'fornecedor':
					if(c.frn == 0) alert("Selecione o fornecedor!");
					else return true;
					break;
			}
			return false;
		}
	},
	
	consultar: function(){
		var self = Entradas;
		var cons = {src:this.config.srccampo.val(),
					cod:this.config.srccod.val(),
					frn:this.config.srcforn.val(),
					dti:this.config.dataini.val(),
					dtf:this.config.datafim.val()};
		
		if(this.validarConsulta(cons))
		{
			$.ajax({
				data: { acao: 'consultar', srccod:cons.cod, srccampo:cons.src, srcforn:cons.frn, dtini:cons.dti, dtfim:cons.dtf },
				dataType: 'json',
				success: function( results ) {				
					self.config.tabelaEntradaBod.empty();
	
					if(!results || results == 0){
						self.config.tabelaEntradaBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
					}
					else
					{
						for(var u in results)
						{
							self.linhasNovas++;
							
							var linha = self.montaLinha(results[u]);
							
							self.config.tabelaEntradaBod.append(linha);
						}
						$('#tabelaEntrada tbody tr:odd').css("background-color", "#ececec");
					}
					
					self.config.tabelaEntradaBod.fadeIn();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(xhr.status);
					console.log(thrownError);
				}
			});
		}
	},
	
	listarFornecedores: function(){
		var self = Entradas;
		
		$.ajax({
			url: 'fornecedor.php',
			type: 'POST',
			data: { acao: 'listar' },
			dataType: 'json',
			success: function( results ) {
				self.fornecedores = results;
				if(!results || results == 0){
					//self.config.tabelaProdutosBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
				}
				else
				{
					for(var u in results){
						self.config.inputFornec.append( new Option(results[u].nome, results[u].cod, false, false) );
						self.config.pesquisaInputForn.find('select').append( new Option(results[u].nome, results[u].cod, false, false) );
					}
				}
				self.listarProdutos();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	},
	
	iniciaTabelaProd: function(){
		//thead
		for(var i=0; i<this.config.labelNamesCabProd.length; i++){
			if(i != (this.config.labelNamesCabProd.length-1)){
				this.config.tabelaProdutosCab.append('<th class='+this.config.classNamesCabProd[i]+'>'+this.config.labelNamesCabProd[i]+'</th>');				
			}
			else
				this.config.tabelaProdutosCab.append('<th colspan="4" class='+this.config.classNamesCabProd[i]+'>'+this.config.labelNamesCabProd[i]+'</th>');
		}
		//tfoot
		this.config.tabelaProdutosRod.append("<td colspan='4'>&nbsp;</td>");
	},
	
	listarProdutos: function(){
		var self = Entradas;
		
		$.ajax({
			url: 'produtos.php',
			type: 'POST',
			data: { acao: 'listar' },
			dataType: 'json',
			success: function( results ) {		
				self.config.tabelaProdutosBod.empty();
				self.config.tabelaProdutosBod.hide();

				if(!results || results == 0){
					self.config.tabelaProdutosBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
				}
				else
				{
					self.produtos = results;
					
					for(var u in results)
					{	
						var linha = self.montaLinhaProd(results[u]);	
						
						self.config.tabelaProdutosBod.append(linha);
						
						self.config.inputProd.append( new Option(results[u].nome, results[u].cod, false, false) );
						self.config.pesquisaInputCod.find('select').append( new Option(results[u].cod, results[u].cod, false, false) );
					}
					$('#tabelaProdutos tbody tr:odd').css("background-color", "#acd");
				}
				self.config.tabelaProdutosBod.fadeIn();
				self.listarEntradas();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	},
	
	montaLinhaProd: function( prod ){
		var str, acao, novoId = "";
		
		this.novoId = "prod" + prod.cod;
		
		this.str  = "";
		this.str += "<tr id='"+this.novoId+"'>";
		this.str +=    "<td class='cod'>"+prod.cod+"</td>";
		this.str +=    "<td class='nome'>"+prod.nome+"</td>";
		this.str +=    "<td class='preco'>"+prod.preco+"</td>";
		this.str +=    "<td class='qtde'>"+prod.qtde+"</td>";
		this.str += "</tr>";
		
		return this.str;
	},
	
	toggleProdutos: function(){
		this.tabelaProdutos.slideToggle("slow");
	},
	
	iniciaTabela: function(){
		//thead
		for(var i=0; i<this.config.labelNamesCab.length; i++){
			if(i != (this.config.labelNamesCab.length-1))
				this.config.tabelaEntradaCab.append('<th class='+this.config.classNamesCab[i]+'>'+this.config.labelNamesCab[i]+'</th>');				
			else
				this.config.tabelaEntradaCab.append('<th colspan="3" class='+this.config.classNamesCab[i]+'>'+this.config.labelNamesCab[i]+'</th>');
		}
		//tfoot
		this.config.tabelaEntradaRod.append("<td colspan='5'>&nbsp;</td>");
		this.config.tabelaEntradaRod.append("<td class='botao_novo'><a href=\"#\" onclick='Entradas.novoRegistro()'>Nova Entrada</a></td>");
	},
	
	listarEntradas: function(){
		var self = Entradas;
		
		$.ajax({
			data: { acao: 'listar' },
			dataType: 'json',
			success: function( results ) {				
				self.config.tabelaEntradaBod.empty();
				self.config.tabelaEntradaBod.hide();
				
				if(!results || results == 0 || results == null){
					self.config.tabelaEntradaBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
				}
				else
				{
					for(var u in results)
					{
						self.linhasNovas++;
						
						var linha = self.montaLinha(results[u]);
						
						self.config.tabelaEntradaBod.append(linha);
					}
					$('#tabelaEntrada tbody tr:odd').css("background-color", "#ececec");
				}
				
				self.config.tabelaEntradaBod.fadeIn();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	},
	
	montaLinha: function( ent ){
		var str, acao, novoId, bt_cancelar = "";
		var codigo, data, fornecedor, prod, quantidade = "";
		
		codigo = ent.cod;
		data   = ent.data.substring(0,10);
		forn   = ent.fornecedor;
		prod   = ent.produto;
		quant  = ent.qtde;
		
		this.novoId = "linha" + codigo;
		this.bt_cancelar = '<a href="#" onclick="Entradas.cancelar(\''+codigo+'\')">Cancelar</a>';
		
		this.str  = "";
		this.str += "<tr id='"+this.novoId+"'>";
		this.str +=    "<td class='cod'>"+codigo+"</td>";
		this.str +=    "<td class='data'>"+data+"</td>";
		this.str +=    "<td class='fornec'>"+Entradas.fornecedores[forn].nome+"</td>";
		//this.str +=    "<td class='produto'>"+prod+"</td>";
		this.str +=    "<td class='produto'>"+Entradas.produtos[prod].nome+"<span>"+prod+"</span></td>";
		this.str +=    "<td class='qtde'>"+quant+"</td>";
			
		if(ent.cancelado == 1)
			this.str +=    "<td class='cancelado'>Cancelado</td>";
		else		
			this.str +=    "<td class='botao1'>"+this.bt_cancelar+"</td>";
			
		this.str += "</tr>";
		
		return this.str;
	},
	
	exibePopUp: function(){			
		this.config.divPopUp.fadeIn(300);
		this.config.divPopUpBg.fadeIn(300);
	},
	
	fechaPopUp: function(){
		this.config.divPopUp.fadeOut(300);
		this.config.divPopUpBg.fadeOut(300);
	},
	
	state1: function(){
		this.config.inputCod.val("*");
		this.config.inputData.val("");
		this.config.inputFornec.val(0);
		this.config.inputProd.val(0);
		this.config.inputQtde.val("");
		this.config.formPopUp.children('a').eq(0).show();
		this.config.formPopUp.children('a').eq(1).hide();
		this.config.formPopUp.children('a').eq(2).text('Cancelar');
		this.config.msgPopUp.html("");
	},
	
	state2: function( ent ){
		this.config.inputCod.val(ent.cod);
		this.config.inputData.val(ent.data);
		this.config.inputFornec.val(ent.fornec);
		this.config.inputProd.val(ent.produto);
		this.config.inputQtde.val(ent.qtde);
		
		this.config.formPopUp.children('a').eq(0).hide();
		this.config.formPopUp.children('a').eq(1).show();
		this.config.formPopUp.children('a').eq(2).text('Cancelar');
		this.config.msgPopUp.html("");
	},
	
	state3: function(){
		this.config.formPopUp.children('a').eq(0).hide();
		this.config.formPopUp.children('a').eq(1).hide();
		this.config.formPopUp.children('a').eq(2).text('Fechar');
	},
	
	novoRegistro: function(){
		this.state1();
		this.exibePopUp();
	},
	
	cadastrar: function(){
		var self  = Entradas;
		
		var dat  = this.config.inputData.val();
		var forn = this.config.inputFornec.val();
		var prod = this.config.inputProd.val();
		var qtd  = this.config.inputQtde.val();
		
		if(this.validarForm())
		{
			$.ajax({
				data: { acao: 'cadastrar', dt:dat, fn:forn, pd:prod, qt:qtd },
				dataType: 'html',
				success: function( results ){
					if(results.substring(0,9) == 'cadastrou'){
						self.linhasNovas++;
						var novoCodigo = results.substring(10);
						
						var entrada = { cod:novoCodigo, data:dat, fornecedor:forn, produto:prod, qtde:qtd };
						
						var linha = self.montaLinha(entrada);
						self.config.tabelaEntradaBod.find(".linha").remove();
						self.config.tabelaEntradaBod.append(linha);
						self.config.msgPopUp.hide().fadeIn().html("Entrada " +novoCodigo+ " cadastrada com sucesso!");
						self.state3();

						var qtdProd = self.config.tabelaProdutosBod.find("#prod"+prod).find('td.qtde').html();
						var n1 = parseInt(qtdProd, 10);
						var n2 = parseInt(qtd, 10);
						
						var aux = (n1 + n2);
							self.config.tabelaProdutosBod.find("#prod"+prod).find('td.qtde').html(aux);
						
						$('tbody tr:odd').css("background-color", "#ececec");
					}
				}
			});
		}
	},
	
	cancelar: function( codigo ){
		var self = Entradas;

		var idlinha = 'linha' +codigo;
		var element = '#tabelaEntrada tbody tr#' +idlinha;
		
		var bg = $(element).css("background-color");		
				 $(element).css("background-color", "#F03");
				 
		var prod = $(element).find('td.produto span').html();
		var qtd  = $(element).find('td.qtde').html();
		
		if(confirm("Tem certeza que deseja cancelar este registro?"))
		{
			this.linhaEmEdicao = codigo;
			
			$.ajax({
				data: { acao: 'cancelar' , cod:codigo , pd:prod, qt:qtd },
				dataType: 'html',
				success: function( results ){					
					if(results == 'cancelou'){
						$(element).css("background-color", bg);
						$(element).find('td.botao1 a').removeAttr("href");
						$(element).find('td.botao1 a').removeAttr("onclick");
						$(element).find('td.botao1 a').css("color", "#F00");
						$(element).find('td.botao1 a').html("Cancelado");
						
						var qtdProd = self.config.tabelaProdutosBod.find("#prod"+prod).find('td.qtde').html();
						
						var n1 = parseInt(qtdProd, 10);
						var n2 = parseInt(qtd, 10);
						
						var aux = (n1 - n2);
						
						self.config.tabelaProdutosBod.find("#prod"+prod).find('td.qtde').html(aux);
					}
					else
					{
						alert("Ops, ocorreu algum erro, tente novamente.");	
					}
					self.linhaEmEdicao = null;
				}
			});
		}
		else $(element).css("background-color", bg);
	},
	
	validarForm: function(){
		if(this.config.inputFornec.val() == 0)
		{
			this.config.inputFornec.focus();
			this.config.msgPopUp.hide().fadeIn().html("Selecione o 'Fornecedor'.");
			return false;
		}
		if(this.config.inputProd.val() == 0)
		{
			this.config.inputProd.focus();
			this.config.msgPopUp.hide().fadeIn().html("Selecione o 'Produto'.");
			return false;
		}
		if(this.config.inputQtde.val() == "")
		{
			this.config.inputQtde.focus();
			this.config.msgPopUp.hide().fadeIn().html("Selecione a 'Quantidade'.");
			return false;
		}
		return true;
	}
};

$(function(){
	Entradas.init({
		secao: 			"entradas",
		msgNenhumUser:  "Nenhum registro encontrado!",
		imgAjaxProgress: "imagens/ajax-loader.gif",
		
		pesquisaInputCod:  $('#pesquisaInputCodigo'),
		pesquisaInputData: $('#pesquisaInputData'),
		pesquisaInputForn: $('#pesquisaInputFornecedor'),
		
		srccampo: $('#srccampo'),
		srccod:   $('#srccod'),
		srcforn:  $('#srcforn'),
		dataini:  $('#dtini'),
		datafim:  $('#dtfim'),
		
		//entradas
		labelNamesCab: new Array('Cód.','Data','Fornecedor','Produto','Qtde','Controle'),
		classNamesCab: new Array('cab_cod','cab_data','cab_fornec','cab_produto','cab_qtde','cab_controle'),
		classNamesLin: new Array('cod','data','fornec','produto','qtde','botao','botao'),
		
		//entradas
		labelNamesCabProd: new Array('Cód.','Nome','Preço','Quantidade'),
		classNamesCabProd: new Array('cab_cod','cab_nome','cab_preco','cab_qtde'),
		classNamesLinProd: new Array('cod','nome','preco','qtde'),
		
		tabelaProdutos:    $('#tabelaProdutos'),
		tabelaProdutosCab: $('#tabelaProdutos tr.cabecalho'),
		tabelaProdutosRod: $('#tabelaProdutos tr.rodape'),
		tabelaProdutosBod: $('#tabelaProdutos tbody'),
		
		tabelaEntrada:    $('#tabelaEntrada'),
		tabelaEntradaCab: $('#tabelaEntrada tr.cabecalho'),
		tabelaEntradaRod: $('#tabelaEntrada tr.rodape'),
		tabelaEntradaBod: $('#tabelaEntrada tbody'),
		
		divPopUp:    $('#popUp'),
		divPopUpBg:  $('#popUpBg'),
		formPopUp:   $('#popUpForm'),
		inputCod:    $('#codigo'),
		
		inputData:   $('#data'),
		inputFornec: $('#fornecedor'),
		inputProd:   $('#produto'),
		inputQtde:   $('#qtde'),
		msgPopUp:    $('#msg')
	});
	
	$('#data').datepicker({
		showOn: "button",
		buttonImage: "../_jquery/images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd'
	});
	
	$('#dtini').datepicker({
		showOn: "button",
		buttonImage: "../_jquery/images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", 
				      "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" ],
		dayNamesMin: [ "D", "S", "T", "Q", "Q", "S", "S" ]
	});
	
	$('#dtfim').datepicker({
		showOn: "button",
		buttonImage: "../_jquery/images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", 
				      "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" ],
		dayNamesMin: [ "D", "S", "T", "Q", "Q", "S", "S" ]
	});
});