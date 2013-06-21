var Produtos = {
	linhasNovas: 0,
	dadosAtuais: null,
	linhaEmEdicao: null,
	
	init: function( config ){
		this.config = config;
		
		$.ajaxSetup({
			url: 'produtos.php',
			type: 'POST'
		});
		
		this.iniciaTabela();
		this.listarProdutos();
	},
	
	iniciaTabela: function(){
		//thead
		for(var i=0; i<this.config.labelNamesCab.length; i++){
			if(i != (this.config.labelNamesCab.length-1))
				this.config.minhaTabelaCab.append('<th class='+this.config.classNamesCab[i]+'>'+this.config.labelNamesCab[i]+'</th>');				
			else
				this.config.minhaTabelaCab.append('<th colspan="3" class='+this.config.classNamesCab[i]+'>'+this.config.labelNamesCab[i]+'</th>');
		}
		//tfoot
		this.config.minhaTabelaRod.append("<td colspan='4'>&nbsp;</td>");
		this.config.minhaTabelaRod.append("<td colspan='3' class='botao_novo'><a href=\"#\" onclick='Produtos.novoRegistro()'>Novo Produto</a></td>");
	},
	
	listarProdutos: function(){
		var self = Produtos;
		
		$.ajax({
			data: { acao: 'listar' },
			dataType: 'json',
			success: function( results ) {			
				self.config.minhaTabelaBod.empty();
				self.config.minhaTabelaBod.hide();

				if(!results || results == 0){
					self.config.minhaTabelaBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
				}
				else
				{
					for(var u in results)
					{
						self.linhasNovas++;
						
						var linha = self.montaLinha(results[u]);	
						
						self.config.minhaTabelaBod.append(linha);
					}
					$('tbody tr:odd').css("background-color", "#ececec");
				}
				
				self.config.minhaTabelaBod.fadeIn();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	},
	
	montaLinha: function( prod ){
		var str, acao, novoId, bt_habil, bt_editar, bt_excluir = "";
		
		this.acao   = prod.habil ? 'desabilitar' : 'habilitar';
		this.novoId = "linha" + prod.cod;
		
		this.bt_habil  = '<a href="#" onclick="Produtos.' +this.acao+ '(\'' +prod.cod+ '\')">';
		this.bt_habil += '<img src="imagens/habil' +prod.habil+ '.png" alt="' +this.acao+ '" />';
		this.bt_habil += '</a>';
		
		this.bt_editar  = '<a href="#" onclick="Produtos.editar(\''+prod.cod+'\')">Editar</a>';
		this.bt_excluir = '<a href="#" onclick="Produtos.excluir(\''+prod.cod+'\')">Excluir</a>';
		
		this.str  = "";
		this.str += "<tr id='"+this.novoId+"'>";
		this.str +=    "<td class='cod'>"+prod.cod+"</td>";
		this.str +=    "<td class='nome'>"+prod.nome+"</td>";
		this.str +=    "<td class='preco'>"+prod.preco+"</td>";
		this.str +=    "<td class='qtde'>"+prod.qtde+"</td>";
		this.str +=    "<td class='habil'>"+this.bt_habil+"</td>";
		this.str +=    "<td class='botao1'>"+this.bt_editar+"</td>";
		this.str +=    "<td class='botao2'>"+this.bt_excluir+"</td>";
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
		this.config.inputNome.val("");
		this.config.inputPreco.val("");
		this.config.formPopUp.children('a').eq(0).show();
		this.config.formPopUp.children('a').eq(1).hide();
		this.config.formPopUp.children('a').eq(2).text('Cancelar');
		this.config.msgPopUp.html("");
	},
	
	state2: function( prod ){
		this.config.inputCod.val(prod.cod);
		this.config.inputNome.val(prod.nome);
		this.config.inputPreco.val(prod.preco);
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
		var self  = Produtos;
		
		var nom = this.config.inputNome.val();
		var prc = this.config.inputPreco.val();
		
		if(self.validarForm())
		{
			console.log("valid");
			$.ajax({
				data: { acao: 'cadastrar', nm:nom, pc:prc },
				dataType: 'html',
				success: function( results ){
					if(results.substring(0,9) == 'cadastrou'){
						self.linhasNovas++;
						var novoCodigo = results.substring(10);
						
						var produto = { cod:novoCodigo, nome:nom, preco:prc, qtde:0, habil:0 };
						
						var linha = self.montaLinha(produto);
						self.config.minhaTabelaBod.append(linha);
						self.config.msgPopUp.hide().fadeIn().html("Produto " +novoCodigo+ " cadastrado com sucesso!");
						self.state3();
						
						$('tbody tr:odd').css("background-color", "#ececec");
					}
				}
			});
		}
	},
	
	editar: function( codigo ){
		var sefl = Produtos;
		
		var idlinha = 'linha' +codigo;
		var element = 'tbody tr#' +idlinha;
		
		this.linhaEmEdicao = element;
		
		var nom = $(element).find('td.nome').html();
		var prc = $(element).find('td.preco').html();
		
	 	var produto = { cod:codigo, nome:nom, preco:prc };
		
		this.state2(produto);
		this.exibePopUp();
	},
	
	atualizar: function(){		
		var self  = Produtos;
		
		var cd  = this.config.inputCod.val();
		var nom = this.config.inputNome.val();
		var prc = this.config.inputPreco.val();
		
		if(this.validarForm())
		{
			$.ajax({
				data: { acao:'atualizar', cod:cd, nm:nom, pc:prc },
				dataType: 'html',
				success: function( results ){
					if(results == 'atualizou'){
						var el = self.linhaEmEdicao;

						$(el).find('td.cod').html(cd);
						$(el).find('td.nome').html(nom);
						$(el).find('td.preco').html(prc);
		
						self.config.msgPopUp.hide().fadeIn().html("Produto " +cd+ " atualizada com sucesso!");
						self.state3();
						self.linhaEmEdicao = null;
					}
				},
				error: function( param1, param2 ){
					console.log(param1);
					console.log(param2);
				}
			});
		}
	},
	
	excluir: function( codigo ){
		var sefl = Produtos;

		var idlinha = 'linha' +codigo;
		var element = 'tbody tr#' +idlinha;
		
		var bg = $(element).css("background-color");		
				 $(element).css("background-color", "#F03");

		if(confirm("Tem certeza que deseja excluir este registro?"))
		{
			this.linhaEmEdicao = codigo;
			
			$.ajax({
				data: { acao: 'excluir' , cod: codigo },
				dataType: 'html',
				success: function( results ){
					if(results == 'excluiu'){
						$(element).remove();
					}
					else
					{
						alert("Ops, ocorreu algum erro, tente novamente.");	
					}
					sefl.linhaEmEdicao = null;
				}
			});
		}
		else $(element).css("background-color", bg);
	},
	
	habilitar: function( codigo ){
		var idlinha = 'tbody tr#linha' +codigo+ ' td.habil';
		$(idlinha).html("<img src='"+this.config.imgAjaxProgress+"' alt='Loading' />");
		$.ajax({
			data: { acao: 'habilitar' , cod: codigo },
			dataType: 'html',
			success: function( results ){
				var bt_habil = "";
				if(results == 'habilitou'){
					this.bt_habil  = '<a href="#" onclick="Produtos.desabilitar(\'' +codigo+ '\')">';
					this.bt_habil += '<img src="imagens/habil1.png" alt="desabilitar" />';
					this.bt_habil += '</a>';
					
					$(idlinha).html(this.bt_habil);
				}
			}
		});
	},
	
	desabilitar: function( codigo ){	
		var idlinha = 'tbody tr#linha' +codigo+ ' td.habil';
		$(idlinha).html("<img src='"+this.config.imgAjaxProgress+"' alt='Loading' />");
		$.ajax({
			data: { acao: 'desabilitar' , cod: codigo },
			dataType: 'html',
			success: function( results ){
				var bt_habil = "";
				if(results == 'desabilitou'){
					this.bt_habil  = '<a href="#" onclick="Produtos.habilitar(\'' +codigo+ '\')">';
					this.bt_habil += '<img src="imagens/habil0.png" alt="habilitar" />';
					this.bt_habil += '</a>';
					
					$(idlinha).html(this.bt_habil);
				}
			}
		});
	},
	
	validarForm: function(){		
		if(this.config.inputNome.val() == "")
		{
			this.config.inputNome.focus();
			this.config.msgPopUp.hide().fadeIn().html("Preencha o campo 'Nome'.");
			return false;
		}
		if(this.config.inputPreco.val() == "")
		{
			this.config.inputPreco.focus();
			this.config.msgPopUp.hide().fadeIn().html("Preencha o campo 'Preco'.");
			return false;
		}
		return true;
	}
};

$(function(){
	Produtos.init({		
		secao: 			"produtos",
		msgNenhumUser:  "Nenhum registro encontrado!",
		imgAjaxProgress: "imagens/ajax-loader.gif",
		
		labelNamesCab: new Array('Cód.','Nome','Preço','Quantidade','Controle'),
		classNamesCab: new Array('cab_cod','cab_nome','cab_preco','cab_qtde','cab_controle'),
		classNamesLin: new Array('cod','nome','preco','qtde','botao','botao'),
		
		minhaTabela:    $('table#minhaTabela'),
		minhaTabelaCab: $('tr.cabecalho'),
		minhaTabelaRod: $('tr.rodape'),
		minhaTabelaBod: $('tbody'),
		
		divPopUp:   $('#popUp'),
		divPopUpBg: $('#popUpBg'),
		formPopUp:  $('#popUpForm'),
		msgPopUp:   $('#msg'),
		
		inputCod:   $('#codigo'),
		inputNome:  $('#nome'),
		inputPreco: $('#preco')
	});
});