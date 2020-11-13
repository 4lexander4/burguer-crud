<?php
// Importando classes (O path é relativo ao arquivo index.php)
require_once('./classes/membros.php');
require_once('./classes/database.php');

class MembrosDAO {
    // Variável que vai fazer a conexão com o DB
    private $pdo;

    public function Inserir(Membros $membro){
        try{
            // Prepared statement (https://www.w3schools.com/php/php_mysql_prepared_statements.asp)
            /**
             * A idéia do prepared statement é evitar que seja colocado diretamente a varíavel na query,
             * ao invés disso, colocamos um parametro substituto do qual vamos vincular a variável
             * ao parametro e depois executar a query.
             */
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("INSERT INTO membros (nome, sobrenome, cidade, estado, email, usuario, senha, datacriacao, tipopermissao) VALUES (:nome, :sobrenome, :cidade, :estado, :email, :usuario, :senha, :datacriacao, :tipopermissao);");
            // Vinculando parametros                                 
            $stmt->bindParam(':nome', $membro->GetNome());            
            $stmt->bindParam(':sobrenome', $membro->GetSobrenome());
            $stmt->bindParam(':cidade', $membro->GetCidade());
            $stmt->bindParam(':estado', $membro->GetEstado());
            $stmt->bindParam(':email', $membro->GetEmail());
            $stmt->bindParam(':usuario', $membro->GetUsuario());
            $stmt->bindParam(':senha', $membro->GetSenha());
            $stmt->bindParam(':datacriacao', $membro->GetDataCriacao());
            $stmt->bindParam(':tipopermissao', $membro->GetTipoPermissao(), PDO::PARAM_BOOL);            
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
    
    public function Listar(){
        $pdo = Database::getConnection();
        // Array onde será armazenado os dados do DB
        $result = array();
        try{
            $stmt = $pdo->prepare("SELECT * FROM membros ORDER BY codmembro");
            $stmt->execute();            
            /**
             * Para construtores com parâmetros, deve-se passar valores iniciais para o fetch iniciar.
             * E o fetch_props_late serve para chamar o construtor e depois atribuir
             * os dados - do contrário, o PDO faz o inverso (ou seja, os valores seriam os do array)
             * https://www.php.net/manual/pt_BR/pdostatement.fetch.php */            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e){
            var_dump("SQL ERROR: " . $e->getMessage());
            return false;
        }
        $pdo = null;
    }
    
    public function BuscarPorId($id){        
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM membros WHERE codmembro = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();            
            // FETCH_ASSOC => Retorna um array indexado pelo nome da coluna e o seu valor
            $stmt->setFetchMode(PDO::FETCH_ASSOC); 
            // Inicia a busca dos dados e o resultado é salvo na variável $obj
            $result = $stmt->fetch();
            // Retorna uma instância da classe Produto
            return new Membros($result);
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
        }
        $pdo = null;   
    }

    public function BuscarPorLogin($user){
        try{        
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM membros WHERE usuario = :user");
            $stmt->bindParam(":user", $user);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);            
            return $result;
        }
        catch(PDOException $e){
            echo "SQL ERROR: " . $e->getMessage();
        }

    }
    
    public function Deletar($id){
        try{   
            $pdo = Database::getConnection();                                  
            $stmt = $pdo->prepare("DELETE FROM membros WHERE codmembro = :id");        
            $stmt->bindParam(':id', $id);        
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
            var_dump("SQL ERROR: " . $e->getMessage());
            return false;
        }
        $pdo = null;  
    } 

    public function Atualizar($id, Membros $membroAlterado){    
        try{
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE membros SET nome = :nome, sobrenome = :sobrenome, cidade = :cidade, estado = :estado, email = :email, usuario = :usuario, senha = :senha, datacriacao = :datacriacao, tipopermissao = :tipopermissao WHERE codmembro = :id");    
            $stmt->bindParam(':nome', $membroAlterado->GetNome());            
            $stmt->bindParam(':sobrenome', $membroAlterado->GetSobrenome());
            $stmt->bindParam(':cidade', $membroAlterado->GetCidade());
            $stmt->bindParam(':estado', $membroAlterado->GetEstado());
            $stmt->bindParam(':email', $membroAlterado->GetEmail());
            $stmt->bindParam(':usuario', $membroAlterado->GetUsuario());
            $stmt->bindParam(':senha', $membroAlterado->GetSenha());
            $stmt->bindParam(':datacriacao', $membroAlterado->GetDataCriacao());
            $stmt->bindParam(':tipopermissao', $membroAlterado->GetTipoPermissao(), PDO::PARAM_BOOL);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return true;
        }
        catch(PDOException $e)
        {
            var_dump("SQL ERROR: " . $e->getMessage());
            return false;
        }
        $pdo = null;     
    }
}
?>