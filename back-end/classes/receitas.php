<?php
class Receitas{

    private $codreceita;
    private $titulo;
    private $descricao;
    private $likes = 0;
    // False = Comum | True = Vegana
    private $tipocarne;
    private $avaliacao = 0.00;
    private $membros_codmembro;   

    // Colocamos os parametros do JSON para instânciar o objeto
    public function __construct($params){        
        foreach($params as $index => $value){            
            $this->{$index} = $value;                        
        }
    }

    public function GetCodReceita(){
        return $this->codreceita;
    }

    public function GetTitulo(){
        return $this->titulo;
    }

    public function GetDescricao(){
        return $this->descricao;
    }

    public function GetLikes(){
        return $this->codreceita;
    }

    public function GetTipoCarne(){
        return $this->tipocarne;
    }

    public function GetAvaliacao(){
        return $this->avaliacao;
    }

    public function GetMembrosCod(){
        return $this->membros_codmembro;
    }
}
?>