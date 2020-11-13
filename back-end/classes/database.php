<?php
class Database{    
    // Variável que vai fazer a conexão com o DB
    private static $connect;
    
    // static: A função pode ser chamada sem precisar instanciar um objeto
    // Retorna um objeto do qual criou a conexão com o DB
    public static function getConnection(){            
        // Arquivo JSON com os parametros do DB
        $str = file_get_contents('./json/database_local.json');
        $json = json_decode($str, true);

        // Atribuindo os parametros da conexão do DB
        $hostname = $json['hostname'];        
        $database = $json['database'];
        $username = $json['username'];
        $password = $json['password'];
        // Verifica se vai ocorrer um erro
        try{
            // Criando a conexão com o banco de dados
            $connect = new PDO("pgsql:host=$hostname;dbname=$database", $username, $password);
            // set the PDO error mode to exception (https://www.php.net/manual/pt_BR/pdo.setattribute.php)
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $connect->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        // Retorna uma mensagem de erro mais fácil de entender
        catch(PDOException $e)
        {
            echo "SQL ERROR: " . $e->getMessage();            
        }        
        return $connect;   
    }   
}
?>