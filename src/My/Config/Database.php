<?php

namespace My\Config;

class Database{
    //TODO Gestion du mdp
    private $host = 'localhost\sqlexpress';
    private $db_name = "MegaProductionBDD";
    private $username = "MegaProduction_Admin";
    private $password = "Not24get";
    public $connection;
    
    /**
     * 
     * @return PDO
     */
    public function getConnection(){
        $this->connection = null;
        
        try{
            //new PDO("sqlsrv:Server=localhost;Database=testdb", "UserName", "Password");
            $this->connection = new PDO("sqlsrv:Server=".$this->host.";Database=".$this->db_name,$this->username,$this->password);
            $this->connection->exec("set names utf8");
            //$success=true;
        } catch (PDOException $ex) {
            echo "Erreur de connexion : ".$ex->getMessage();
        }
        
        return $this->connection;
    }
}

