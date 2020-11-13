<?php

use Slim\Psr7\Response;

include_once('./classes/receitas.php');
include_once('./classes/dao/receitasDAO.php');

class ReceitasController{

    public function Listar($request, $response, $args){
        $dao = new ReceitasDAO();
        $return = $dao->Listar();
        if($return == false){
            return $response->withStatus(404);
        }
        else{
            $payload = json_encode($return);
            $response->getBody()->write($payload);
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }        
    }

    public function Inserir($request, $response, $args){
        $data = $request->getParsedBody();       
        $obj = new Receitas($data);                       
        $dao = new ReceitasDAO();
        $return = $dao->Inserir($obj);
        if($return == false){
            return $response->withStatus(400);
        }
        else{
            return $response->withStatus(201);
        }
    }

    public function BuscarPorId($request, $response, $args){
        // Argumento informado no Path da URI
        $id = $args['id'];
        $dao = new ReceitasDAO();
        $return = $dao->BuscarPorId($id);
        if($return == false){
            return $response->withStatus(404);
        }
        else{
            $payload = json_encode($return);
            $response->getBody()->write($payload);
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');       
        }
    }

    public function Atualizar($request, $response, $args){
        // Argumento informado no Path da URI
        $id = $args['id'];
        $data = $request->getParsedBody();
        $receita = new Receitas($data);
        $dao = new ReceitasDAO();
        $return = $dao->Atualizar($id, $receita);
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(201);
        }
    }

    public function Deletar($request, $response, $args){
        $id = $args['id'];
        $dao = new ReceitasDAO();
        $return = $dao->Deletar($id);
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(200);
        }        
    }

    public function AtualizarLikes($request, $response, $args){
        $data = $request->getParsedBody();
        $dao = new ReceitasDAO();
        $return = $dao->AtualizarLikes($data['codreceita']);
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(201);
        }
    }

    public function AtualizarAvaliacao($request, $response, $args){
        $data = $request->getParsedBody();
        $dao = new ReceitasDAO();
        $return = $dao->AtualizarAvaliacao($data['codreceita'], $data['avaliacao']);
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(201);
        }
    }
}
?>