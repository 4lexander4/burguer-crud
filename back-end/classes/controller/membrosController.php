<?php

use \Firebase\JWT\JWT;
use Slim\Psr7\Response;

include_once('./classes/membros.php');
include_once('./classes/dao/membrosDAO.php');

class MembrosController{
    private $secretKey = "t3st3";

    public function Inserir($request, $response, $args){
        $data = $request->getParsedBody();                                
        $obj = new Membros($data['nome'], $data['sobrenome'], $data['cidade'], $data['estado'], $data['email'], $data['usuario'], $data['senha'], $data['datacriacao'], $data['tipopermissao']);             
        $dao = new MembrosDAO();       
        $dao->Inserir($obj);
        $payload = json_encode($obj);
        $response->getBody()->write($payload);
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');        
    }

    public function Autenticar($request, $response, $args){
        $data = $request->getParsedBody();

        $dao = new MembrosDAO();
        $membro = $dao->buscarPorLogin($data['usuario']);
        if($membro->senha == $data['senha']){
            $token = array(
                'user' => strval($membro->codmembro),
                'nome' => $membro->nome
            );
            $jwt = JWT::encode($token, $this->secretKey);            
            $response->getBody()->write(json_encode($jwt));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');                    
        }
        else{
            return $response->withStatus(401);
        }
    }

    public function ValidarToken($request, $handler){
        $response = new Response();
        $token = $request->getHeader('Autorization');

        if($token && $token[0]){
            try{
                $decoded = JWT::decode($token[0], $this->secretKey, array('HS256'));
                
                if($decoded){
                    $response = $handler->handle($request);
                    return($response);
                }
            } 
            catch(Exception $error){
                return $response->withStatus(401);
            }
        }
        return $response->withStatus(401);
    }

    public function Listar($request, $response, $args){
        $dao = new MembrosDAO();                    
        $payload = json_encode($dao->Listar());
        $response->getBody()->write($payload);
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function Atualizar($request, $response, $args){
        // Argumento informado no Path da URI
        $id = $args['id'];
        $data = $request->getParsedBody();
        $membro = new Membros($data['nome'], $data['sobrenome'], $data['cidade'], $data['estado'], $data['email'], $data['usuario'], $data['senha'], $data['datacriacao'], $data['tipopermissao']);

        $dao = new MembrosDAO();
        $dao->Atualizar($id, $membro);
        
        $payload = json_encode($membro);
        $response->getBody()->write($payload);
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function Deletar($request, $response, $args){
        $id = $args['id'];

        $dao = new MembrosDAO();
        $dao->Deletar($id);
        $response->getBody()->write("REMOÇÃO REALIZADA!");
        return $response->withStatus(200);      
    }
}
?>