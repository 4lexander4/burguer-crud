<?php

use Slim\Psr7\Response;

include_once('./classes/comentarios.php');
include_once('./classes/dao/comentariosDAO.php');

class ComentariosController{

    public function Listar($request, $response, $args){
        $dao = new ComentariosDAO();
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
        $obj = new Comentarios($data);        
        $dao = new ComentariosDAO();
        $return = $dao->Inserir($obj);
        if($return == false){
            return $response->withStatus(400);
        }
        else{
            return $response->withStatus(201);
        }        
    }

    public function Atualizar($request, $response, $args){
        // Argumento informado no Path da URI
        $id = $args['id'];
        $data = $request->getParsedBody();
        $comentario = new Comentarios($data);
        $dao = new ComentariosDAO();
        $return = $dao->Atualizar($id, $comentario);
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(201);
        }
    }

    public function Deletar($request, $response, $args){
        $id = $args['id'];
        $dao = new ComentariosDAO();
        $return = $dao->Deletar($id);
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(200);
        } 
    }

    public function BuscarPorReceita($request, $response, $args){
        // Código da Receita a ser pesquisada
        $id = $args['id'];
        $dao = new ComentariosDAO();
        $return = $dao->BuscarPorReceita($id);
        if($return == false){
            return $response->withStatus(404);
        }
        else{  
            $payload = json_encode($return);
            $response->getBody()->write($payload);
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }
    }

    public function AtualizarLikes($request, $response, $args){
        $data = $request->getParsedBody();
        $dao = new ComentariosDAO();
        $return = $dao->AtualizarLikes($data['receitas_codreceita'], $data['codcomentario']);
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(201);
        }
    }
}
?>