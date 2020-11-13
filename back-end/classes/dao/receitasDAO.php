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
            $stmt = $pdo->prepare("INSERT INTO receitas (titulo, descricao, tipocarne, membros_codmembro) VALUES (:titulo, :descricao, :tipocarne, :membros_codmembro);");
            // Vinculando parametros
            $stmt->bindParam(':titulo', $receita->GetTitulo());            
            $stmt->bindParam(':descricao', $receita->GetDescricao());            
            $stmt->bindParam(':tipocarne', $receita->GetTipoCarne(), PDO::PARAM_BOOL);            
            $stmt->bindParam(':membros_codmembro', $receita->GetMembrosCod());
            // Executa a query
            $stmt->execute();
            return true;           
        } 
        catch(PDOException $e){
            var_dump("SQL ERROR: " . $e->getMessage());
            return false; 
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
    
    function BuscarPorId($id){        
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM receitas WHERE codreceita = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();  
            if($stmt->rowCount() == 0){
                return false;
            }
            else{
                // FETCH_ASSOC => Retorna um array indexado pelo nome da coluna e o seu valor
                $stmt->setFetchMode(PDO::FETCH_ASSOC);            
                // Inicia a busca dos dados e o resultado é salvo na variável $result
                $result = $stmt->fetch();
                // Retorna um array
                return($result);
            }     
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
            return false;
        }
        $pdo = null;   
    }
    
    function Deletar($id){
        try{   
            $pdo = Database::getConnection();                     
            $stmt = $pdo->prepare("DELETE FROM receitas WHERE codreceita = :id");        
            $stmt->bindParam(':id', $id);        
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
    
    function Atualizar($id, Receitas $receitaAlterado){    
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE receitas SET titulo = :titulo, descricao = :descricao, tipocarne = :tipocarne, membros_codmembro = :membros_codmembro WHERE codreceita = :id");    
            $stmt->bindValue(':titulo', $receitaAlterado->GetTitulo());
            $stmt->bindValue(':descricao', $receitaAlterado->GetDescricao());            
            $stmt->bindValue(':tipocarne', $receitaAlterado->GetTipoCarne());            
            $stmt->bindValue(':membros_codmembro', $receitaAlterado->GetMembrosCod());
            $stmt->bindValue(":id", $id);
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

    function AtualizarLikes($id){
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE receitas SET likes = likes + 1 WHERE codreceita = :id;");            
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
    }
    
    function AtualizarAvaliacao($id, $valor){
        try{
            $pdo = Database::getConnection();
            // Executa função para calcular a média e colocarmos somente 1 variável dentro da Query
            $media = $this->CalcularMedia($this->GetAvaliacao($id), $valor, $this->GetUpdateVotos($id));            
            $stmt = $pdo->prepare("UPDATE receitas SET avaliacao = :media WHERE codreceita = :id;");            
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':media', $media);            
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

    // Retorna a avaliação atual da receita
    private function GetAvaliacao($id){
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT avaliacao FROM receitas WHERE codreceita = $id;");
            $stmt->execute();
            // Configura para retornar um array onde o índice é o nome da coluna
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            // Retorna o valor ao invés do array
            return $result['avaliacao'];
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();            
        }
    }

    // Atualiza e depois retorna a quantidade atual usuários que fizeram algum voto na receita
    private function GetUpdateVotos($id){
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE receitas SET totalvotos = totalvotos + 1 WHERE codreceita = :id;");            
            $stmt->bindValue(':id', $id);                 
            $stmt->execute();
            $stmt = $pdo->prepare("SELECT totalvotos FROM receitas WHERE codreceita = $id;");
            $stmt->execute();
            // Configura para retornar um array onde o índice é o nome da coluna
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            // Retorna o valor ao invés do array
            return $result['totalvotos'];
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();            
        }
    }

    // Simples função para calcular a média da avaliação antes de dar UPDATE na query
    private function CalcularMedia($avaliacao, $valor, $votos){
        $media = ($avaliacao + $valor) / $votos;
        return $media;
    }
}
?>