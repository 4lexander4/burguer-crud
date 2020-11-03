<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . './vendor/autoload.php';
// Importando as classes Controllers
// Devemos colocar o ponto devido ao Caminho Absoluto (https://phpdelusions.net/articles/paths)
require_once('./classes/controller/comentariosController.php');
require_once('./classes/controller/membrosController.php');
require_once('./classes/controller/receitasController.php');

// Criando o aplicativo
$app = AppFactory::create();
$app->addBodyParsingMiddleware();

//CRIANDO AS ROTAS HTTP
$app->post('/api/login','MembrosController:Autenticar');
$app->get('/api/lista_receitas', 'ReceitasController:Listar');
$app->get('/api/lista_receitas/comentarios/{id}', 'ComentariosController:Listar');

$app->group('/api/membros', function($app){
    $app->get('', 'MembrosController:Listar');
    $app->post('', 'MembrosController:Inserir');   
    $app->put('/{id}', 'MembrosController:Atualizar');
    $app->delete('/{id}', 'MembrosController:Deletar');
})->add('MembrosController:validarToken');

$app->group('/api/receitas', function($app){  
    #$app->get('', 'ReceitasController:Listar');  
    $app->post('', 'ReceitasController:Inserir');
    $app->get('/{id}', 'ReceitasController:BuscarPorId');    
    $app->put('/{id}', 'ReceitasController:Atualizar');
    $app->delete('/{id}', 'ReceitasController:Deletar');
})->add('MembrosController:validarToken');

$app->group('/api/comentarios', function($app){
    #$app->get('/{id}', 'ComentariosController:Listar');   
    $app->post('', 'ComentariosController:Inserir');       
    $app->put('/{id}', 'ComentariosController:Atualizar');
    $app->delete('/{id}', 'ComentariosController:Deletar');
})->add('MembrosController:validarToken');

// Executando o aplicativo
$app->run();