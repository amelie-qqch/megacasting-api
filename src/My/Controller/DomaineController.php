<?php


namespace My\Controller;
use My\Config\Database;
use PDO;


class DomaineController {
  
    private function getDatabase(){
        $database = new Database();
        $db = $database->getConnection();
        return $db;
    }
    
    ##Queries
    private function queryDomaines(){
        $db = $this->getDatabase();
        $query = sprintf("
            SELECT Libelle
            FROM Domaine;"
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
    public function getDomaines(){
        $domaines = array();
        $domaines["domaines "] = array();
        $stmtDomaines = $this->queryDomaines();
        while($row=$stmtDomaines->fetch(PDO::FETCH_ASSOC)){
            $domaines_item=array(
                "libelle"   =>  $row['Libelle']
            );
            
            array_push($domaines["domaines"], $domaines_item);
        }
        
        echo json_encode($domaines);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
