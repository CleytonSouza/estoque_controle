var Main = {
	sessao: null,
	logado: false,
	usuario: {nome: null, nivel: null},
	modulos: Array("usuarios","produtos","clientes","fornecedor", "entrada","saida"),
	
	init: function( config ){
		this.config = config;
		this.sessao = this.modulos[1];
		this.getSessUser();
	},
	
	continueInit: function(){
		this.exibeUser();
		this.loadMenu();
		if(this.usuario.nivel == 0)
			this.selectMenu(this.config.topoMenu.find('li').eq(2));
		else
			this.selectMenu(this.config.topoMenu.find('li').first());
			
		this.loadContent(this.sessao);
	},
	
	getSessUser: function(){
		var self = Main;

		$.ajax({
			url: 'sess_user.php',
			type: 'POST',
			dataType: 'json',
			success: function( results ){
				var unome  = results[0].nome;
				var univel = results[0].nivel;
				
				if(unome == null && univel == null)
					document.location.href = "logout.php";
				else
				{
					self.usuario.nome  = unome;
					self.usuario.nivel = univel;
					self.continueInit();				
				}
			},
			error: function (xhr, ajaxOptions, exception){
				//console.log(xhr.status);
				//console.log(exception);
				document.location.href = "logout.php";
			}
		});
	},
	
	getUserNome: function(){
		return this.usuario.nome;
	},
	
	getUserNivel: function(){
		return this.usuario.nivel;
	},
	
	exibeUser: function(){
		this.config.statusUser.find('span').html("User: "+this.getUserNome()+ "(" +this.getUserNivel()+ ")");
	},
	
	loadMenu: function(){	
		var self = Main;
		var n    = this.getUserNivel();
		var x    = n != 0 ? 1 : 0;
		
		for(i=x; i<this.modulos.length; i++)
		{
			var li = document.createElement('li');
				li.className = 'bt1';
				li.innerHTML = this.modulos[i].toUpperCase();

			$(li).on('click', function(){
				Main.loadContent(this.innerHTML.toLowerCase());
				Main.selectMenu(this);
			});
			this.config.topoMenu.append(li);
			this.config.topoMenu.append("<li class='sep'>|</li>");
		}
		//this.config.topoMenu.append("<li class='bt1'><a href='../site' target='_blank'>Site</a></li>");
		//this.config.topoMenu.append("<li class='sep'>|</li>");
		this.config.topoMenu.append("<li class='bt1'><a href='logout.php'>Logout</a></li>");
		this.config.topoMenu.append("<li class='sep'>|</li>");
	},
	
	selectMenu: function(item){
		$(item).siblings('li')
			   .css("color", "#bababa")
			   .end()
			   .css("color", "#ff0");
	},
	
	loadContent: function( sessao ){
		this.sessao = sessao;
		this.config.linkSessao.attr('href', 'css/'+sessao+'.css');
		this.config.divLabel.text(sessao.toUpperCase());
		this.config.divConteudo.load("html/"+sessao+".html");
		$.getScript("js/"+sessao+".js")
		 .done(function(script, textStatus){
			console.log( "getScript:"+textStatus );
		 })
		 .fail(function(jqxhr, settings, exception){
			console.log( "Triggered ajaxError handler." );
			console.log(jqxhr.status);
			console.log(exception);
		 });
	}
};

$(function(){
	Main.init({
		divLabel:    $('#label'),
		divConteudo: $('#conteudo'),
		tagHead:     $('head'),
		linkSessao:  $('link.linksessao'),
		statusUser:  $('#topo_status'),
		topoMenu:    $('#topo_menu ul')
	});
});