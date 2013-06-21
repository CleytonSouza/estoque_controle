var Login = {
	init: function( config ){
		this.config = config;
		this.config.divLabel.text(this.config.secao.toUpperCase());
	},
	
	auth: function( login, senha ){
		var self = Login;
		
		if( login == "" || senha == "" )
			this.config.divAlert.hide().fadeIn().text(this.config.mensagem1);
		else
		{
			self.config.divImgLog.show();
			$.ajax({
			  type: "POST",
			  url: "login.php",
			  data: { l: login, s: senha }
			}).done( function( msg ){
				if( msg == "invalido"){
					self.config.divImgLog.hide();
					self.config.divAlert.hide().fadeIn().text(self.config.mensagem2);
				}
				else
					document.location.href = "main.html";
			});
		}
	}
};

window.onload = function(){
	Login.init({
		secao:     "login do sistema",
		mensagem1: "Preencha o campo 'login' e 'senha'.",
		mensagem2: "'Login' ou 'Senha' inválido!",
		divLabel:  $('#label'),
		divAlert:  $('#alerta'),
		divImgLog: $('#formLoginSubmitImg img')
	});
}