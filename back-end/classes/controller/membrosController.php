<?php

use \Firebase\JWT\JWT;
use Slim\Psr7\Response;

include_once('./classes/membros.php');
include_once('./classes/dao/membrosDAO.php');

class MembrosController{

    private $secretKey = "t3st3";
    private $secretKeyAdmin = "t3st3_admin";

    public function Inserir($request, $response, $args){
        $data = $request->getParsedBody();                                
        $obj = new Membros($data);             
        $dao = new MembrosDAO();       
        $return = $dao->Inserir($obj);        
        if($return == false){
            return $response->withStatus(400);
        }
        else{            
            return $response->withStatus(201);
        }                
    }

    public function Autenticar($request, $response, $args){
        $str = file_get_contents('./json/token.json');
        $json = json_decode($str, true);

        $data = $request->getParsedBody();
        $dao = new MembrosDAO();
        $membro = $dao->buscarPorLogin($data['usuario']);
        if($membro->senha == $data['senha']){
            $token = array(
                'user' => strval($membro->codmembro),
                'nome' => $membro->nome
            );
            if($membro->tipopermissao == true){
                $jwt = JWT::encode($token, $this->secretKeyAdmin);    
            }
            else{
                $jwt = JWT::encode($token, $this->secretKey);            
            }            
            $response->getBody()->write(json_encode($jwt));
            return $response->withStatus(202)->withHeader('Content-Type', 'application/json');                    
        }
        else{
            return $response->withStatus(401);
        }
    }

    public function ValidarToken($request, $handler){   
        $response = new Response();
        $token = $request->getHeader('Authorization');

        // Verifica decodificação tanto para usuário admin quanto usuário normal
        if($token && $token[0]){
            try{                
                $decoded = JWT::decode($token[0], $this->secretKeyAdmin, array('HS256'));
                if($decoded){
                    $response = $handler->handle($request);
                    return($response);
                }
            } 
            catch(Exception $error){
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
        }        
    }

    public function Listar($request, $response, $args){
        $dao = new MembrosDAO();
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

    public function Atualizar($request, $response, $args){
        // Argumento informado no Path da URI
        $id = $args['id'];
        $data = $request->getParsedBody();
        $membro = new Membros($data);
        $dao = new MembrosDAO();
        $return = $dao->Atualizar($id, $membro);        
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(201);
        }        
    }

    public function Deletar($request, $response, $args){
        $id = $args['id'];

        $dao = new MembrosDAO();
        $return = $dao->Deletar($id);
        if($return == false){
            return $response->withStatus(406);
        }
        else{
            return $response->withStatus(200);
        } 
    }
}
?>