<?php

use Slim\Psr7\Response;

include_once('./classes/comentarios.php');
include_once('./classes/dao/comentariosDAO.php');

class ComentariosController{

    public function Listar($request, $response, $args){
        $dao = new ComentariosDAO();
        $payload = json_encode($dao->Listar());
        $response->getBody()->write($payload);
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function Inserir($request, $response, $args){
        $data = json_decode($request->getBody(), true);        
        $obj = new Comentarios($data);        
        $dao = new ComentariosDAO();
        $dao->Inserir($obj);
        $payload = json_encode($obj);
        $response->getBody()->write($payload);
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function Atualizar($request, $response, $args){
        // Argumento informado no Path da URI
        $id = $args['id'];
        $data = $request->getParsedBody();
        $comentario = new Comentarios($data);

        $dao = new ComentariosDAO();
        $dao->Atualizar($id, $comentario);

        $payload = json_encode($comentario);
        $response->getBody()->write($payload);
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function Deletar($request, $response, $args){
        $id = $args['id'];

        $dao = new ComentariosDAO();
        $dao->Deletar($id);
        $response->getBody()->write("REMOÇÃO REALIZADA!");
        return $response->withStatus(200);
    }
}
?>