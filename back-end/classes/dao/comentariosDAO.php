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
            $stmt = $pdo->prepare("INSERT INTO comentarios (comentario, datacriacao, receitas_codreceita, membros_codmembro) VALUES (:comentario, :datacriacao, :receitas_codreceita, :membros_codmembro);");
            // Vinculando parametros
            $stmt->bindParam(':comentario', $comentario->GetComentario());
            $stmt->bindParam(':datacriacao', $comentario->GetDataCriacao());            
            $stmt->bindParam(':receitas_codreceita', $comentario->GetReceitasCod());
            $stmt->bindParam(':membros_codmembro', $comentario->GetMembrosCod());                      
            // Executa a query
            $stmt->execute();
            return true;
        } 
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
            return false;        
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
            if($stmt->rowCount() == 0){
                return false;
            }
            else{           
                $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $lista;
            }
        }
        catch(PDOException $e)
        {
            echo "SQL ERROR: " . $e->getMessage();
            return false;
        }
        $pdo = null;
    }  
    
    function Deletar($id){
        try{   
            $pdo = Database::getConnection();         
            $stmt = $pdo->prepare("DELETE FROM comentarios WHERE codcomentario = :id");        
            $stmt->bindParam(':id',$id);        
            $stmt->execute();
            if($stmt->rowCount() == 0){
                return false;
            }
            else{
                return true;
            }
        }
        catch(PDOException $e)
        {
            echo "SQL ERROR: " . $e->getMessage();
            return false;
        }
        $pdo = null;  
    }
    
    function Atualizar($id, Comentarios $comentarioAlterado)
    {    
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE comentarios SET comentario = :comentario, datacriacao = :datacriacao, receitas_codreceita = :receitas_codreceita, membros_codmembro = :membros_codmembro WHERE codcomentario = :id");    
            $stmt->bindValue(':comentario', $comentarioAlterado->GetComentario());
            $stmt->bindValue(':datacriacao', $comentarioAlterado->GetDataCriacao());            
            $stmt->bindValue(':receitas_codreceita', $comentarioAlterado->GetReceitasCod());
            $stmt->bindValue(':membros_codmembro', $comentarioAlterado->GetMembrosCod());
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            if($stmt->rowCount() == 0){
                return false;
            }
            else{
                return true;
            }
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
            return false;
        }
        $pdo = null;     
    }

    // Pesquisa todos os comentários de uma receita específica
    function BuscarPorReceita($id){
        try{
            $pdo = Database::getConnection();
            $result = array();            
            $stmt = $pdo->prepare("SELECT comentario, c.datacriacao, c.likes, nome, sobrenome, cidade, estado 
                                FROM comentarios AS c, membros AS m 
                                WHERE receitas_codreceita = :id
                                AND m.codmembro = c.membros_codmembro
                                GROUP BY comentario, c.datacriacao, c.likes, nome, sobrenome, cidade, estado;");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            if($stmt->rowCount() == 0){
                return false;
            }
            else{
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_PROPS_LATE);                        
                return $result;
            }
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
            return false;
        }
        $pdo = null;
    }

    function AtualizarLikes($idRec, $idCom){
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE comentarios SET likes = likes + 1 WHERE codcomentario = :idCom AND receitas_codreceita = :idRec;");            
            $stmt->bindValue(':idRec', $idRec);
            $stmt->bindValue(':idCom', $idCom);
            $stmt->execute();
            if($stmt->rowCount() == 0){
                return false;
            }
            else{
                return true;
            }
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
            return false;
        }
    }
}
?>