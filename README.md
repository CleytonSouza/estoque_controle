estoque_controle1.0<br>
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
Sistema academico de Controle de Estoque em PHP OO, MVC, MySQL, JQuery/AJAX.<br>
Esse sistema foi desenvolvido utilizando PHP 5.0 seguindo as melhores práticas da Orientação à Objetos e alguns Padrões de Projeto como MVC, DAO e FACTORY.<br>
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
--------------------------<br>
Back-End:<br>
--------------------------<br>
A arquitetura do back-end desse sistema foi tirada do livro "PHP Profissional":<br>

- MVC: Divide o sistema em camadas de Modelo, Vizualização, Controle e Regras de Negócios;<br>
- DAO: Objeto para persistir no banco de dados, preparado para trabalhar com mais de um banco de dados:
       Interface DAO;
       DAOBanco implementa DAO;
       MySQLDAOBanco extends DAOBanco;
       ORACLEDAOBanco extends DAOBanco;//qualquer outro banco extends DAOBanco
       SQLSERVERDAOBanco extends DAOBanco;
       DAOPersistivel classe abstrata para ser extendida por qualquer classe que persista no banco de dados;
- FACTORY: responsável por fabricar(instanciar) o objeto referente ao banco de dados escolhido;
<br><br>
Autores:<br>
Alexandre Altair de Melo <alexandre@phpsc.com.br><br>
Mauricio G. F. Nascimento <mauricio@prophp.com.br><br>
http://www.novatec.com.br/livros/phppro<br>
ISBN: 978-85-7522-141-9<br>
Editora Novatec, 2008 - todos os direitos reservados<br>

--------------------------<br>
Front-End:<br>
--------------------------<br>
A camada responsável pela visualização do conteúdo é acessada por apenas um arquivo, "main.html".
O arquivo "main.html" carrega o arquivo "main.js" que é o responsável pelo controle das informações.
Utilizando Jquery e Ajax para o controle do conteúdo e a comunicação com o servidor.
O main.js é responsável por buscar a sessão do usuário no servidor, caso exista, exibe o nome do usuário,
monta o menu e carrega a página inicial e os arquivos .js e .css referente as mesmas.<br>

Autores:<br>
Murilo Contreira Alves <murilo@mcontreira.com.br><br>
http://www.mcontreira.com.br/mc<br>

=======================================================================================================================
