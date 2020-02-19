<?php

namespace My\Controller;
use My\Config\Database;
use PDO;

class AnnonceurController {
    const ITEM_PAR_PAGE = 9;
    
    private function getDatabase(){
        $database = new Database();
        $db = $database->getConnection();
        return $db;
    }
    
    ##Queries
    private function queryAnnonceurLibelle(){
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
    
    private function countItem(){
        $db = $this->getDatabase();
        
        $query = sprintf("
                SELECT COUNT(Identifiant) as 'nbAnnonceurs'
                FROM Annonceur;" 
        );
        
        try{
            $stmt = $db->prepare($query);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $stmt['nbAnnonceurs'];   
    }
    
    private function queryAnnonceurs($pageActuelle){
        $db = $this->getDatabase();       
        $limite = (($pageActuelle-1)*self::ITEM_PAR_PAGE);
        $query = sprintf("
           SELECT
                Identifiant,
                Libelle,
                Mail,
                Telephone,
                Code_Postal,
                Ville
            FROM Annonceur
            ORDER BY Annonceur.Libelle ASC
            OFFSET :limite ROWS FETCH NEXT :parPage ROWS ONLY;"
        );
        
        try{
            $stmt = $db->prepare($query);
            $stmt->bindParam(':limite',$limite, PDO::PARAM_INT);
            $stmt->bindValue(':parPage',self::ITEM_PAR_PAGE, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        return $stmt;
    }
    
    private function queryAnnonceur($id){
        $db = $this->getDatabase();
        
        $query = sprintf("
            SELECT * 
            FROM Annonceur
            WHERE Identifiant = :id;"
        );
        
        try{
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id',$id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        
        return $stmt;
    }
    
    
    ##Gets
    public function getAnnonceursLibelle(){
        $annonceurs = array();
        $annonceurs["annonceurs"] = array();
        $stmtAnnonceurs = $this->queryAnnonceurLibelle();
        while($row=$stmtAnnonceurs->fetch(PDO::FETCH_ASSOC)){
            $annonceurs_item=array(
                "libelle"   =>  $row['Libelle']
            );
            
            array_push($annonceurs["annonceurs"], $annonceurs_item);
        }
        
        echo json_encode($annonceurs);
    }
    
    
    public function getAnnonceurs($pageActuelle){
        $nbItems = $this->countItem();
        $nbPage = ceil($nbItems/ self::ITEM_PAR_PAGE);
        $annonceurs=array();
        $annonceurs["annonceurs"] = array();
        $annonceurs["nbPages"] = $nbPage;
        $stmtAnnonceurs = $this->queryAnnonceurs($pageActuelle);
        while($row=$stmtAnnonceurs->fetch(PDO::FETCH_ASSOC)){
            $annonceurs_item=array(
                "id"            =>  $row['Identifiant'],
                "annonceur"     =>  $row['Libelle'],
                "mail"          =>  $row['Mail'],
                "telephone"     =>  $row['Telephone'],
                "code_postal"   =>  $row['Code_Postal'],
                "ville"         =>  $row['Ville']
            );
            array_push($annonceurs["annonceurs"], $annonceurs_item);
        }
//      echo json_last_error_msg();
//      var_dump($annonceurs);
//      echo $nbItems;
//      echo $nbPage;
        echo json_encode($annonceurs);
    }
    
    
    public function getAnnonceur($id){
        $annonceur = array();
        $annonceur["annonceur"]=array();
        $stmtAnnonceur = $this->queryAnnonceur($id);
        while($row = $stmtAnnonceur->fetch(PDO::FETCH_ASSOC)){
            $annonceur_item=array(
                "id"                  =>$row['Identifiant'],
                "libelle"             =>$row['Libelle'],
                "mail"                =>$row['Mail'],
                "telephone"           =>$row['Telephone'],
                "nom_contact"         =>$row['Nom_Contact'],
                "prenom_contact"      =>$row['Prenom_Contact'],
                "mail_contact"        =>$row['Mail_Contact'],
                "telephone_contact"   =>$row['Telephone_Contact'],
                "adresse"             =>$row['Adresse'],
                "code_postal"         =>$row['Code_Postal'],
                "ville"               =>$row['Ville']
            );
            array_push($annonceur["annonceur"], $annonceur_item);
        }
        echo json_encode($annonceur);
    }
    
    
    public function getCountAnnonceurs(){
        $annonceurs["annonceurs"] = $this->countItem();
        
        echo json_encode($annonceurs);
    }
    
    
    
    
    
    
    
    
    
    
    
}
