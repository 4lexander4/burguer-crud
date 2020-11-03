<?php
class Comentarios{
    private $codcomentario;
    private $comentario;
    private $datacriacao;
    private $likes = 0;    
    private $receitas_codreceita;
    private $membros_codmembro;      

    // Colocamos os parametros do JSON para instânciar o objeto
    public function __construct($params = array()){
        foreach ($params as $index => $value) {
            $this->{$index} = $value;
        }
    }

    // Função para retornar o valor de uma variável da classe
    public function GetCodComentario(){
        return $this->codcomentario;
    }

    public function GetComentario(){
        return $this->comentario;
    }

    public function GetDataCriacao(){
        return $this->datacriacao;
    }

    public function GetLikes(){
        return $this->likes;
    }

    public function GetReceitasCod(){
        return $this->receitas_codreceita;
    }

    public function GetMembrosCod(){
        return $this->membros_codmembro;
    }
}
?>