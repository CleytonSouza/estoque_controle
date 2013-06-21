estoque_controle
================
Sistema academico de Controle de Estoque.
Tecnologias utilizadas: PHP OO, MVC, MySQL, JQuery/AJAX.
 
A arquitetura desse sistema foi tirada do livro "PHP Profissional":
Esse sistema foi desenvolvido utilizando PHP 5.0 seguindo as melhores práticas da Orientação à Objetos
e alguns Padrões de Projeto como MVC, DAO e FACTORY.

Back-End:
- MVC: Divide o sistema em camadas de Modelo, Vizualização, Controle e Regras de Negócios;
- DAO: Objeto para persistir no banco de dados, preparado para trabalhar com mais de um banco de dados:
       Interface DAO;
       DAOBanco implementa DAO;
       MySQLDAOBanco extends DAOBanco;
       ORACLEDAOBanco extends DAOBanco;//qualquer outro banco extends DAOBanco
       SQLSERVERDAOBanco extends DAOBanco;
       DAOPersistivel classe abstrata para ser extendida por qualquer classe que persista no banco de dados;
- FACTORY: responsável por fabricar(instanciar) o objeto referente ao banco de dados escolhido;

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
Front-End:
A camada responsável pela visualização do conteúdo é acessada por apenas um arquivo chamado "main.html".
