<?php
class Membros{
    private $codmembro;
    private $nome;
    private $sobrenome;
    private $cidade;
    private $estado;
    private $email;
    private $usuario;
    private $senha;
    private $datacriacao;
    // False = User | True = Admin
    private $tipopermissao;

    // Colocamos os parametros do JSON para instânciar o objeto
    public function __construct($params){        
        foreach($params as $index => $value){            
            $this->{$index} = $value;                        
        }
    }

    // Funções para retornar o valor de uma variável da classe
    public function GetCodMembro(){
        return $this->codmembro;
    }

    public function GetNome(){
        return $this->nome;
    }

    public function GetSobrenome(){
        return $this->sobrenome;
    }    

    public function GetCidade(){
        return $this->cidade;
    }

    public function GetEstado(){
        return $this->estado;
    }

    public function GetEmail(){
        return $this->email;
    }

    public function GetUsuario(){
        return $this->usuario;
    }

    public function GetSenha(){
        return $this->senha;
    }

    public function GetDataCriacao(){
        return $this->datacriacao;
    }

    public function GetTipoPermissao(){
        return $this->tipopermissao;
    }  
}
?>