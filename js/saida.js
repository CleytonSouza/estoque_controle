var Saidas = {
	linhasNovas: 0,
	dadosAtuais: null,
	linhaEmEdicao: null,
	produtos: Array(0),
	clientes: Array(0),
	
	init: function( config ){
		this.config = config;
		
		$.ajaxSetup({
			url: 'saidas.php',
			type: 'POST'
		});
		
		this.iniciaTabela();
		this.iniciaTabelaProd();
		this.listarClientes();
	},
	
	zeraFiltro: function( ){		
		this.config.srccampo.val(0);
		this.config.srccod.val(0);
		this.config.srcclie.val(0);
		this.config.dataini.val("");
		this.config.datafim.val("");
	},
	
	hideFiltro: function( ){		
		this.config.pesquisaInputCod.hide();
		this.config.pesquisaInputData.hide();
		this.config.pesquisaInputClie.hide();
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
			
			case 'cliente':
				this.config.pesquisaInputClie.show();
			break;
			
			default:
				this.zeraFiltro();
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
					
				case 'cliente':
					if(c.cli == 0) alert("Selecione o cliente!");
					else return true;
					break;
			}
			return false;
		}
	},
	
	consultar: function(){
		var self = Saidas;
		var cons = {src:this.config.srccampo.val(),
					cod:this.config.srccod.val(),
					cli:this.config.srcclie.val(),
					dti:this.config.dataini.val(),
					dtf:this.config.datafim.val()};
		
		if(this.validarConsulta(cons))
		{
			$.ajax({
				data: { acao: 'consultar', srccod:cons.cod, srccampo:cons.src, srcclie:cons.cli, dtini:cons.dti, dtfim:cons.dtf },
				dataType: 'json',
				success: function( results ) {				
					self.config.tabelaSaidaBod.empty();
	
					if(!results || results == 0){
						self.config.tabelaSaidaBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
					}
					else
					{
						for(var u in results)
						{
							self.linhasNovas++;
							
							var linha = self.montaLinha(results[u]);
							
							self.config.tabelaSaidaBod.append(linha);
						}
						$('#tabelaSaida tbody tr:odd').css("background-color", "#ececec");
					}
					
					self.config.tabelaSaidaBod.fadeIn();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(xhr.status);
					console.log(thrownError);
				}
			});
		}
	},
	
	listarClientes: function(){
		var self = Saidas;
		
		$.ajax({
			url: 'clientes.php',
			type: 'POST',
			data: { acao: 'listar' },
			dataType: 'json',
			success: function( results ) {
				self.clientes = results;
				if(!results || results == 0){
					//self.config.tabelaProdutosBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
				}
				else
				{
					for(var u in results){
						self.config.inputClie.append( new Option(results[u].nome, results[u].cod, false, false) );
						self.config.pesquisaInputClie.find('select').append( new Option(results[u].nome, results[u].cod, false, false) );
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
		var self = Saidas;
		
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
				self.listarSaidas();
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
				this.config.tabelaSaidaCab.append('<th class='+this.config.classNamesCab[i]+'>'+this.config.labelNamesCab[i]+'</th>');				
			else
				this.config.tabelaSaidaCab.append('<th colspan="3" class='+this.config.classNamesCab[i]+'>'+this.config.labelNamesCab[i]+'</th>');
		}
		//tfoot
		this.config.tabelaSaidaRod.append("<td colspan='5'>&nbsp;</td>");
		this.config.tabelaSaidaRod.append("<td class='botao_novo'><a href=\"#\" onclick='Saidas.novoRegistro()'>Nova Saída</a></td>");
	},
	
	listarSaidas: function(){
		var self = Saidas;
		
		$.ajax({
			data: { acao: 'listar' },
			dataType: 'json',
			success: function( results ) {				
				self.config.tabelaSaidaBod.empty();
				self.config.tabelaSaidaBod.hide();

				if(!results || results == 0){
					self.config.tabelaSaidaBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
				}
				else
				{
					for(var u in results)
					{
						self.linhasNovas++;
						
						var linha = self.montaLinha(results[u]);
						
						self.config.tabelaSaidaBod.append(linha);
					}
					$('#tabelaSaida tbody tr:odd').css("background-color", "#ececec");
				}
				
				self.config.tabelaSaidaBod.fadeIn();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	},
	
	montaLinha: function( ent ){
		var str, acao, novoId, bt_cancelar = "";
		var codigo, data, cliente, prod, quantidade = "";
		
		codigo = ent.cod;
		data   = ent.data.substring(0,10);
		clie   = ent.cliente;
		prod   = ent.produto;
		quant  = ent.qtde;
		
		this.novoId = "linha" + codigo;
		this.bt_cancelar = '<a href="#" onclick="Saidas.cancelar(\''+codigo+'\')">Cancelar</a>';
		
		this.str  = "";
		this.str += "<tr id='"+this.novoId+"'>";
		this.str +=    "<td class='cod'>"+codigo+"</td>";
		this.str +=    "<td class='data'>"+data+"</td>";
		this.str +=    "<td class='fornec'>"+Saidas.clientes[clie].nome+"</td>";
		//this.str +=    "<td class='produto'>"+prod+"</td>";
		this.str +=    "<td class='produto'>"+Saidas.produtos[prod].nome+"<span>"+prod+"</span></td>";
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
		this.config.inputClie.val(0);
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
		this.config.inputCliente.val(ent.clie);
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
		var self  = Saidas;
		
		var dat  = this.config.inputData.val();
		var clie = this.config.inputClie.val();
		var prod = this.config.inputProd.val();
		var qtd  = this.config.inputQtde.val();
		
		if(this.validarForm())
		{
			$.ajax({
				data: { acao: 'cadastrar', dt:dat, cl:clie, pd:prod, qt:qtd },
				dataType: 'html',
				success: function( results ){
					if(results.substring(0,9) == 'cadastrou'){
						self.linhasNovas++;
						var novoCodigo = results.substring(10);
						
						var saida = { cod:novoCodigo, data:dat, cliente:clie, produto:prod, qtde:qtd };
						
						var linha = self.montaLinha(saida);
						self.config.tabelaSaidaBod.find(".linha").remove();
						self.config.tabelaSaidaBod.append(linha);
						self.config.msgPopUp.hide().fadeIn().html("Saída " +novoCodigo+ " cadastrada com sucesso!");
						self.state3();

						var qtdProd = self.config.tabelaProdutosBod.find("#prod"+prod).find('td.qtde').html();
						var n1 = parseInt(qtdProd, 10);
						var n2 = parseInt(qtd, 10);
						
						var aux = (n1 - n2);
							self.config.tabelaProdutosBod.find("#prod"+prod).find('td.qtde').html(aux);
						
						$('tbody tr:odd').css("background-color", "#ececec");
					}
				}
			});
		}
	},
	
	cancelar: function( codigo ){
		var self = Saidas;

		var idlinha = 'linha' +codigo;
		var element = '#tabelaSaida tbody tr#' +idlinha;
		
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
		if(this.config.inputClie.val() == 0)
		{
			this.config.inputCliente.focus();
			this.config.msgPopUp.hide().fadeIn().html("Selecione o 'Cliente'.");
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
	Saidas.init({
		secao: 			"saidas",
		msgNenhumUser:  "Nenhum registro encontrado!",
		imgAjaxProgress: "imagens/ajax-loader.gif",
		
		pesquisaInputCod:  $('#pesquisaInputCodigo'),
		pesquisaInputData: $('#pesquisaInputData'),
		pesquisaInputClie: $('#pesquisaInputCliente'),
		
		srccampo: $('#srccampo'),
		srccod:   $('#srccod'),
		srcclie:  $('#srcclie'),
		dataini:  $('#dtini'),
		datafim:  $('#dtfim'),
		
		//saidas
		labelNamesCab: new Array('Cód.','Data','Cliente','Produto','Qtde','Controle'),
		classNamesCab: new Array('cab_cod','cab_data','cab_cliente','cab_produto','cab_qtde','cab_controle'),
		classNamesLin: new Array('cod','data','cliente','produto','qtde','botao','botao'),
		
		//saidas
		labelNamesCabProd: new Array('Cód.','Nome','Preço','Quantidade'),
		classNamesCabProd: new Array('cab_cod','cab_nome','cab_preco','cab_qtde'),
		classNamesLinProd: new Array('cod','nome','preco','qtde'),
		
		tabelaProdutos:    $('#tabelaProdutos'),
		tabelaProdutosCab: $('#tabelaProdutos tr.cabecalho'),
		tabelaProdutosRod: $('#tabelaProdutos tr.rodape'),
		tabelaProdutosBod: $('#tabelaProdutos tbody'),
		
		tabelaSaida:    $('#tabelaSaida'),
		tabelaSaidaCab: $('#tabelaSaida tr.cabecalho'),
		tabelaSaidaRod: $('#tabelaSaida tr.rodape'),
		tabelaSaidaBod: $('#tabelaSaida tbody'),
		
		divPopUp:  $('#popUp'),
		divPopUpBg:$('#popUpBg'),
		formPopUp: $('#popUpForm'),
		inputCod:  $('#codigo'),
		
		inputData: $('#data'),
		inputClie: $('#cliente'),
		inputProd: $('#produto'),
		inputQtde: $('#qtde'),
		msgPopUp:  $('#msg')
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