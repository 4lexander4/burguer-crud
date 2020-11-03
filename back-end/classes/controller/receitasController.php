<?php

use Slim\Psr7\Response;

include_once('./classes/receitas.php');
include_once('./classes/dao/receitasDAO.php');

class ReceitasController{

    public function Listar($request, $response, $args){
        $dao = new ReceitasDAO();
        $payload = json_encode($dao->Listar());
        $response->getBody()->write($payload);
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function Inserir($request, $response, $args){
        $data = json_decode($request->getBody(), true);        
        $obj = new Receitas($data);        
        $dao = new ReceitasDAO();
        $dao->Inserir($obj);
        $payload = json_encode($obj);
        $response->getBody()->write($payload);
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function BuscarPorId($request, $response, $args){
        // Argumento informado no Path da URI
        $id = $args['id'];
        $dao = new ReceitasDAO();
        $payload = json_encode($dao->BuscarPorId($id));
        $response->getBody()->write($payload);
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');       
    }

    public function Atualizar($request, $response, $args){
        // Argumento informado no Path da URI
        $id = $args['id'];
        $data = $request->getParsedBody();
        $receita = new Receitas($data);

        $dao = new ReceitasDAO();
        $dao->Atualizar($id, $receita);

        $payload = json_encode($receita);
        $response->getBody()->write($payload);
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function Deletar($request, $response, $args){
        $id = $args['id'];

        $dao = new ReceitasDAO();
        $dao->Deletar($id);
        $response->getBody()->write("REMOÇÃO REALIZADA!");
        return $response->withStatus(200);
    }
}
?>