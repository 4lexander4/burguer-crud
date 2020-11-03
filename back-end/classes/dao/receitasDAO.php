<?php
// Importando classes
require_once('./classes/receitas.php');
require_once('./classes/database.php');

class ReceitasDAO {
    // Variável que vai fazer a conexão com o DB
    private $pdo;

    function Inserir(Receitas $receita){
        try{
            // Prepared statement (https://www.w3schools.com/php/php_mysql_prepared_statements.asp)
            /**
             * A idéia do prepared statement é evitar que seja colocado diretamente a varíavel na query,
             * ao invés disso, colocamos um parametro substituto do qual vamos vincular a variável
             * ao parametro e depois executar a query.
             */
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("INSERT INTO receitas (titulo, descricao, likes, tipocarne, avaliacao, membros_codmembro) VALUES (:titulo, :descricao, :likes, :tipocarne, :avaliacao, :membros_codmembro);");
            // Vinculando parametros
            $stmt->bindParam(':titulo', $receita->GetTitulo($titulo));
            $stmt->bindParam(':descricao', $receita->GetDescricao($descricao));
            $stmt->bindParam(':likes', $receita->GetLikes($likes));
            $stmt->bindParam(':tipocarne', $receita->GetTipoCarne($tipoCarne), PDO::PARAM_BOOL);
            $stmt->bindParam(':avaliacao', $receita->GetAvaliacao($avaliacao));
            $stmt->bindParam(':membros_codmembro', $receita->GetMembrosCod($membros_codmembro));
            // Executa a query
            $stmt->execute();
        } 
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();        
        }
        // Encerra a conexão com o banco de dados
        $pdo = null;
    }
    
    function Listar(){
        // Retorna a conexão ao DB que foi criada na classe Database        
        $pdo = Database::getConnection();
        // Array onde será armazenado os dados do DB
        $result = array();
        try{
            $stmt = $pdo->prepare("SELECT * FROM receitas");
            $stmt->execute();            
            /**
             * Para construtores com parâmetros, deve-se passar valores iniciais para o fetch iniciar.
             * E o fetch_props_late serve para chamar o construtor e depois atribuir
             * os dados - do contrário, o PDO faz o inverso (ou seja, os valores seriam os do array)
             * https://www.php.net/manual/pt_BR/pdostatement.fetch.php */            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_PROPS_LATE);
            return $result;
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
        }
        $pdo = null;
    }
    
    function BuscarPorId($id){        
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM receitas WHERE codreceita = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();            
            // FETCH_ASSOC => Retorna um array indexado pelo nome da coluna e o seu valor
            $stmt->setFetchMode(PDO::FETCH_ASSOC);            
            // Inicia a busca dos dados e o resultado é salvo na variável $result
            $result = $stmt->fetch();
            // Retorna uma instância da classe Produto
            return($result);
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
        }
        $pdo = null;   
    }
    
    function Deletar($id){
        try{   
            $pdo = Database::getConnection();         
            $stmt = $pdo->prepare("DELETE FROM receitas WHERE codreceita=:id");        
            $stmt->bindParam(':id',$id);        
            $stmt->execute();
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
        }
        $pdo = null;  
    }
    
    function Atualizar($id, Receitas $receitaAlterado){    
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE receitas SET titulo = :titulo, descricao = :descricao, likes = :likes, tipocarne = :tipocarne, avaliacao = :avaliacao, membros_codmembro = :membros_codmembro WHERE codreceita = :id");    
            $stmt->bindValue(':titulo', $receitaAlterado->GetTitulo());
            $stmt->bindValue(':descricao', $receitaAlterado->GetDescricao());
            $stmt->bindValue(':likes', $receitaAlterado->GetLikes());
            $stmt->bindValue(':tipocarne', $receitaAlterado->GetTipoCarne());
            $stmt->bindValue(':avaliacao', $receitaAlterado->GetAvaliacao());
            $stmt->bindValue(':membros_codmembro', $receitaAlterado->GetMembrosCod());
            $stmt->bindValue(":id", $id);
            $stmt->execute();
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
        }
        $pdo = null;     
    }
}
?>