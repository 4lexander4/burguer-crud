<?php
// Importando classes
require_once('./classes/comentarios.php');
require_once('./classes/database.php');

class ComentariosDAO {
    // Variável que vai fazer a conexão com o DB
    private $pdo;

    function Inserir(Comentarios $comentario){
        try{
            // Prepared statement (https://www.w3schools.com/php/php_mysql_prepared_statements.asp)
            /**
             * A idéia do prepared statement é evitar que seja colocado diretamente a varíavel na query,
             * ao invés disso, colocamos um parametro substituto do qual vamos vincular a variável
             * ao parametro e depois executar a query.
             */
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, datacriacao, likes, receitas_codreceita, membros_codmembro) VALUES (:comentario, :datacriacao, :likes, :receitas_codreceita, :membros_codmembro);");
            // Vinculando parametros
            $stmt->bindParam(':comentario', $comentario->GetComentario());
            $stmt->bindParam(':datacriacao', $comentario->GetDataCriacao());
            $stmt->bindParam(':likes', $comentario->GetLikes());
            $stmt->bindParam(':receitas_codreceita', $comentario->GetReceitasCod());
            $stmt->bindParam(':membros_codmembro', $comentario->GetMembrosCod());                      
            // Executa a query
            $stmt->execute();
        } 
        catch(PDOException $e)
        {
            echo "SQL ERROR: " . $e->getMessage();        
        }
        // Encerra a conexão com o banco de dados
        $pdo = null;
    }
    
    function Listar(){
        // Array onde será armazenado os dados do DB
        $pdo = Database::getConnection();
        $lista = array();
        try{
            $stmt = $pdo->prepare("SELECT * FROM comentarios");
            $stmt->execute();            
            /**
             * Para construtores com parâmetros, deve-se passar valores iniciais para o fetch iniciar.
             * E o fetch_props_late serve para chamar o construtor e depois atribuir
             * os dados - do contrário, o PDO faz o inverso (ou seja, os valores seriam os do array)
             * https://www.php.net/manual/pt_BR/pdostatement.fetch.php */            
            $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        }
        catch(PDOException $e)
        {
            echo "SQL ERROR: " . $e->getMessage();
        }
        $pdo = null;
    }  
    
    function Deletar($id){
        try{   
            $pdo = Database::getConnection();         
            $stmt = $pdo->prepare("DELETE FROM comentarios WHERE codcomentario = :id");        
            $stmt->bindParam(':id',$id);        
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "SQL ERROR: " . $e->getMessage();
        }
        $pdo = null;  
    }
    
    function Atualizar($id, Comentarios $comentarioAlterado)
    {    
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE comentarios SET comentario = :comentario, datacriacao = :datacriacao, likes = :likes, receitas_codreceita = :receitas_codreceita, membros_codmembro = :membros_codmembro WHERE codcomentario = :id");    
            $stmt->bindValue(':comentario', $comentarioAlterado->GetComentario());
            $stmt->bindValue(':datacriacao', $comentarioAlterado->GetDataCriacao());
            $stmt->bindValue(':likes', $comentarioAlterado->GetLikes());
            $stmt->bindValue(':receitas_codreceita', $comentarioAlterado->GetReceitasCod());
            $stmt->bindValue(':membros_codmembro', $comentarioAlterado->GetMembrosCod());
            $stmt->bindValue(':id', $id);
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "SQL ERROR: " . $e->getMessage();
        }
        $pdo = null;     
    }
}
?>