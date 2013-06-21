var Usuarios = {
	linhasNovas: 0,
	dadosAtuais: null,
	linhaEmEdicao: null,
	
	init: function( config ){
		this.config = config;
		
		$.ajaxSetup({
			url: 'usuarios.php',
			type: 'POST'
		});
		
		this.iniciaTabela();
		this.listarUsuarios();
	},
	
	iniciaTabela: function(){
		//thead
		for(var i=0; i<this.config.labelNamesCab.length; i++)
			if(i == (this.config.labelNamesCab.length-1))
				this.config.minhaTabelaCab.append('<th colspan="3" class='+this.config.classNamesCab[i]+'>'+this.config.labelNamesCab[i]+'</th>');
			else
				this.config.minhaTabelaCab.append('<th class='+this.config.classNamesCab[i]+'>'+this.config.labelNamesCab[i]+'</th>');
		
		//tfoot
		this.config.minhaTabelaRod.append("<td colspan='6'>&nbsp;</td>");
		this.config.minhaTabelaRod.append("<td colspan='3' class='botao_novo'><a href=\"#\" onclick='Usuarios.novoRegistro()'>Novo usuario</a></td>");
	},
	
	listarUsuarios: function(){
		var self = Usuarios;
		
		$.ajax({
			data: { acao: 'listar' },
			dataType: 'json',
			success: function( results ) {				
				self.config.minhaTabelaBod.hide();

				if(!results){
					self.config.minhaTabelaBod.append("<tr><td colspan='9' class='linha'>"+ self.config.msgNenhumUser +"</td></tr>");
				}
				else
				{
					for(var u in results)
					{
						self.linhasNovas++;
						
						if(results[u].cod == 1)
							var linha = self.montaLinhaAdm(results[u]);	
						else
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
	
	montaLinha: function( user ){
		var str, acao, novoId, bt_habil, bt_editar, bt_excluir = "";
		
		this.acao   = user.habil ? 'desabilitar' : 'habilitar';
		this.novoId = "linha" + user.cod;
				
		this.bt_habil  = '<a href="#" onclick="Usuarios.' +this.acao+ '(\'' +user.cod+ '\')">';
		this.bt_habil += '<img src="imagens/habil' +user.habil+ '.png" alt="' +this.acao+ '" />';
		this.bt_habil += '</a>';
		
		this.bt_editar  = '<a href="#" onclick="Usuarios.editar(\''+user.cod+'\')">Editar</a>';
		this.bt_excluir = '<a href="#" onclick="Usuarios.excluir(\''+user.cod+'\')">Excluir</a>';
		
		this.str  = "";
		this.str += "<tr id='"+this.novoId+"'>";
		this.str +=    "<td class='cod'>"+user.cod+"</td>";
		this.str +=    "<td class='nome'>"+user.nome+"</td>";
		this.str +=    "<td class='email'>"+user.email+"</td>";
		this.str +=    "<td class='login'>"+user.login+"</td>";
		this.str +=    "<td class='senha'>"+user.senha+"</td>";
		this.str +=    "<td class='nivel'>"+user.nivel+"</td>";
		this.str +=    "<td class='habil'>"+this.bt_habil+"</td>";
		this.str +=    "<td class='botao1'>"+this.bt_editar+"</td>";
		this.str +=    "<td class='botao2'>"+this.bt_excluir+"</td>";
		this.str += "</tr>";
		
		return this.str;
	},
	
	montaLinhaAdm: function( user ){
		var str, acao, novoId, bt_habil, bt_editar, bt_excluir = "";
		
		this.novoId     = "linha" + user.cod;
		this.bt_habil   = '';
		this.bt_editar  = '';
		this.bt_excluir = '';
		
		this.str  = "";
		this.str += "<tr id='"+this.novoId+"'>";
		this.str +=    "<td class='cod'>"+user.cod+"</td>";
		this.str +=    "<td class='nome'>"+user.nome+"</td>";
		this.str +=    "<td class='email'>"+user.email+"</td>";
		this.str +=    "<td class='login'>"+user.login+"</td>";
		this.str +=    "<td class='senha'>"+user.senha+"</td>";
		this.str +=    "<td class='nivel'>"+user.nivel+"</td>";
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
		this.config.inputEmail.val("");
		this.config.inputLogin.val("");
		this.config.inputSenha.val("");
		this.config.inputNivel.val("");
		this.config.formPopUp.children('a').eq(0).show();
		this.config.formPopUp.children('a').eq(1).hide();
		this.config.formPopUp.children('a').eq(2).text('Cancelar');
		this.config.msgPopUp.html("");
	},
	
	state2: function( user ){
		this.config.inputCod.val(user.cod);
		this.config.inputNome.val(user.nome);
		this.config.inputEmail.val(user.email);
		this.config.inputLogin.val(user.login);
		this.config.inputSenha.val(user.senha);
		this.config.inputNivel.val(user.nivel);
		
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
		var self  = Usuarios;
		
		var nm = this.config.inputNome.val();
		var em = this.config.inputEmail.val();
		var lg = this.config.inputLogin.val();
		var sn = this.config.inputSenha.val();
		var nv = this.config.inputNivel.val();
		
		if(this.validarForm())
		{
			$.ajax({
				data: { acao: 'cadastrar', n:nm, e:em, l:lg, s:sn, v: nv },
				dataType: 'html',
				success: function( results ){
					if(results.substring(0,9) == 'cadastrou'){
						self.linhasNovas++;
						var novoCodigo = results.substring(10);
						
						var user = { cod:novoCodigo, nome:nm, email:em, login:lg, senha:sn, nivel:nv, habil:0 };
						
						var linha = self.montaLinha(user);
						self.config.minhaTabelaBod.append(linha);
						self.config.msgPopUp.hide().fadeIn().html("Usuário " +novoCodigo+ " cadastrado com sucesso!");
						self.state3();
						
						$('tbody tr:odd').css("background-color", "#ececec");
					}
				}
			});
		}
	},
	
	editar: function( codigo ){
		var sefl = Usuarios;
		
		var idlinha = 'linha' +codigo;
		var element = 'tbody tr#' +idlinha;
		
		this.linhaEmEdicao = element;
		
		//var cd = $(element).find('td.cod').html();
		var nm = $(element).find('td.nome').html();
		var em = $(element).find('td.email').html();
		var lg = $(element).find('td.login').html();
		var sn = $(element).find('td.senha').html();
		var nv = $(element).find('td.nivel').html();
		
		var user = { cod:codigo, nome:nm, email:em, login:lg, senha:sn, nivel:nv };
		
		this.state2(user);
		this.exibePopUp();
	},
	
	atualizar: function(){		
		var self  = Usuarios;
		
		var cd = this.config.inputCod.val();
		var nm = this.config.inputNome.val();
		var em = this.config.inputEmail.val();
		var lg = this.config.inputLogin.val();
		var sn = this.config.inputSenha.val();
		var nv = this.config.inputNivel.val();		
		
		if(this.validarForm())
		{
			$.ajax({
				data: { acao:'atualizar',  cod:cd, n:nm, e:em, l:lg, s:sn, v:nv },
				dataType: 'html',
				success: function( results ){
					if(results == 'atualizou'){
						var el = self.linhaEmEdicao;

						$(el).find('td.cod').html(cd);
						$(el).find('td.nome').html(nm);
						$(el).find('td.email').html(em);
						$(el).find('td.login').html(lg);
						$(el).find('td.senha').html(sn);
						$(el).find('td.nivel').html(nv);
		
						self.config.msgPopUp.hide().fadeIn().html("Usuário " +cd+ " atualizado com sucesso!");
						self.state3();
						self.linhaEmEdicao = null;
					}
				}
			});
		}
	},
	
	excluir: function( codigo ){
		var sefl = Usuarios;

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
					this.bt_habil  = '<a href="#" onclick="Usuarios.desabilitar(\'' +codigo+ '\')">';
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
					this.bt_habil  = '<a href="#" onclick="Usuarios.habilitar(\'' +codigo+ '\')">';
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
		if(this.config.inputEmail.val() == "" || this.config.inputEmail.val().indexOf("@") == -1 || this.config.inputEmail.val().indexOf(".") == -1)
		{
			this.config.inputEmail.focus();
			this.config.msgPopUp.hide().fadeIn().html("Preencha o campo 'Email', corretamente.");
			return false;
		}
		if(this.config.inputLogin.val() == "")
		{
			this.config.inputLogin.focus();
			this.config.msgPopUp.hide().fadeIn().html("Preencha o campo 'Login'.");
			return false;
		}
		if(this.config.inputSenha.val() == "")
		{
			this.config.inputSenha.focus();
			this.config.msgPopUp.hide().fadeIn().html("Preencha o campo 'Senha'.");
			return false;
		}
		if(this.config.inputNivel.val() == "")
		{
			this.config.inputNivel.focus();
			this.config.msgPopUp.hide().fadeIn().html("Preencha o campo 'Nível'.");
			return false;
		}
		return true;
	}
};

$(function(){
	Usuarios.init({
		secao: 			"usuarios",
		msgNenhumUser:  "Nenhum registro encontrado!",
		imgAjaxProgress: "imagens/ajax-loader.gif",
		
		labelNamesCab: new Array('Cód.','Nome','E-mail','Login','Senha','Nível','Controle'),
		classNamesCab: new Array('cab_cod','cab_nome','cab_email','cab_login','cab_senha','cab_nivel','cab_control'),
		classNamesLin: new Array('cod','nome','email','senha','nivel','habil','botao','botao'),
		
		minhaTabela:    $('table#minhaTabela'),
		minhaTabelaCab: $('tr.cabecalho'),
		minhaTabelaRod: $('tr.rodape'),
		minhaTabelaBod: $('tbody'),
		
		divPopUp:   $('#popUp'),
		divPopUpBg: $('#popUpBg'),
		formPopUp:  $('#popUpForm'),
		inputCod:   $('#codigo'),
		inputNome:  $('#nome'),
		inputEmail: $('#email'),
		inputLogin: $('#login'),
		inputSenha: $('#senha'),
		inputNivel: $('#nivel'),
		msgPopUp:   $('#msg')
	});
});