<?php 

__DIR__ ."/../Env.php";
class Database{
    private static ?PDO $instance=null;

private function __construct(){}
public static function getInstance() : PDO{
    if(self::$instance==null){
        try{

        self::$instance= new PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            );
         

           self::$instance->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION

           );
        }catch(PDOException $e){

        die ("Connexion echouee : ".$e->getMessage());
        }
    }return self::$instance;
}



}


?>