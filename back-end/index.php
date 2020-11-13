<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . './vendor/autoload.php';
// Importando as classes Controllers [Devemos colocar o ponto devido ao Caminho Absoluto (https://phpdelusions.net/articles/paths)]
require_once('./classes/controller/comentariosController.php');
require_once('./classes/controller/membrosController.php');
require_once('./classes/controller/receitasController.php');

// Criando o aplicativo
$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// ---------------------------
// ROTAS HTTP (SEM TOKEN)
// ---------------------------

// Usuário ao se logar
$app->post('/api/login','MembrosController:Autenticar');
// Se cadastrar no sistema
$app->post('/api/login/cadastrar', 'MembrosController:Inserir');
// Mostrar todas as receitas cadastradas no BD
$app->get('app/api/lista_receitas', 'ReceitasController:Listar');
// Busca um receita através do ID
$app->get('/api/lista_receitas/{id}', 'ReceitasController:BuscarPorId');
// Esta rota serve para mostrar os comentários de uma receita especifica
$app->get('/api/lista_receitas/comentarios/{id}', 'ComentariosController:BuscarPorReceita');

// ---------------------------
// ROTAS HTTP (COM TOKEN)
// ---------------------------

// CRUD para a tabela membros
$app->group('/api/membros', function($app){
    $app->get('', 'MembrosController:Listar');
    #$app->post('', 'MembrosController:Inserir'); ->> Substituido por: /api/login/cadastrar
    $app->put('/{id}', 'MembrosController:Atualizar');
    $app->delete('/{id}', 'MembrosController:Deletar');
})->add('MembrosController:validarToken');

// CRUD para a tabela receitas
$app->group('/api/receitas', function($app){  
    #$app->get('', 'ReceitasController:Listar'); ->> Substituido por: /api/lista_receitas
    $app->post('', 'ReceitasController:Inserir');
    #$app->get('/{id}', 'ReceitasController:BuscarPorId'); ->> Substituido por: /api/lista_receitas/{id}  
    $app->put('/{id}', 'ReceitasController:Atualizar');
    $app->delete('/{id}', 'ReceitasController:Deletar');
    $app->post('/likes', 'ReceitasController:AtualizarLikes');
    $app->post('/avaliacao', 'ReceitasController:AtualizarAvaliacao');
})->add('MembrosController:validarToken');

// CRUD para a tabela comentarios
$app->group('/api/comentarios', function($app){
    $app->get('', 'ComentariosController:Listar');
    $app->post('', 'ComentariosController:Inserir');       
    $app->put('/{id}', 'ComentariosController:Atualizar');
    $app->delete('/{id}', 'ComentariosController:Deletar');
    $app->post('/likes', 'ComentariosController:AtualizarLikes');
})->add('MembrosController:validarToken');

// Executando o aplicativo
$app->run();