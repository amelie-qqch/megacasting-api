<?php

namespace My\Controller;
use My\Config\Database;
use PDO;

class AnnonceurController {

    private function getDatabase(){
        $database = new Database();
        $db = $database->getConnection();
        return $db;
    }
    
    ##Queries
    private function queryAnnonceur(){
        $db = $this->getDatabase();
        $query = sprintf("
            SELECT Libelle
            FROM Annonceur;"
        );
        
        try{
            $stmt = $db->prepare($query);
            $stmt->execute();
            
        } catch (PDOException $ex) {
            echo $ex;
        }
        
        return $stmt;
                
    }
    
    
    ##Gets
    public function getAnnonceurs(){
        $annonceurs = array();
        $annonceurs["annonceurs"] = array();
        $stmtAnnonceurs = $this->queryAnnonceur();
        while($row=$stmtAnnonceurs->fetch(PDO::FETCH_ASSOC)){
            $annonceurs_item=array(
                "libelle"   =>  $row['Libelle']
            );
            
            array_push($annonceurs["annonceurs"], $annonceurs_item);
        }
        
        echo json_encode($annonceurs);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
