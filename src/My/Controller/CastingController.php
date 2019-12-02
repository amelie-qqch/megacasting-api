<?php

namespace My\Controller;

use My\Config\Database;
use PDO;

class CastingController {
    
    private function getDatabase(){
        $database = new Database();
        $db = $database->getConnection();
        return $db;
    }
    
    
    //Queries
    private function queryCasting($id){
        $db = $this->getDatabase();
        $query = sprintf("
            SELECT 
                ID_CAS, 
                LIB_CAS, 
                DAT_DEB_CON_CAS, 
                NB_POS_CAS, 
                DES_POS_CAS, 
                DES_PRO_CAS,
                TYPE_CON_CAS,
                LIEU_CON_CAS,
                LIB_ANN,
                NOM_CON_ANN,
                PRE_CON_ANN,
                MAIL_CON_ANN                          
            FROM Casting
            LEFT JOIN Annonceur
            ON Casting.ANN_CAS=Annonceur.ID_ANN
            WHERE ID_CAS = :id;"
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
    
    private function queryCastings(){
        $db = $this->getDatabase();
        $query = sprintf("
            SELECT 
                ID_CAS, 
                LIB_CAS,
                TYPE_CON_CAS,
                LIEU_CON_CAS,
                LIB_ANN
            FROM Casting 
            LEFT JOIN Annonceur
            ON Casting.ANN_CAS=Annonceur.ID_ANN;"
        );
        try{
            $stmt = $db->prepare($query);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        return $stmt;
    }
    
    private function queryCastingByDomain($domaine){
        $db = $this->getDatabase();
        $query = sprintf("
            SELECT 
                LIB_CAS, 
                LIB_MET
            FROM Casting
                LEFT JOIN Metier
                ON MET_CAS = ID_MET
                LEFT JOIN Domaine
                ON Metier.DOM_MET = Domaine.ID_DOM
            WHERE Domaine.LIB_DOM = :domaine;"            
        ); 
        try{
            $stmt = $db->prepare($query);
            $stmt->bindParam(':domaine',$domaine, PDO::PARAM_STR); 
            $stmt->execute();
            
        } catch (PDOException $ex) {
            echo $ex;
        }
        return $stmt;
    }
    
    private function queryCastingByAnnonceur($annonceur){
        $db = $this->getDatabase();
        $query = sprintf("
            SELECT LIB_CAS
            FROM Casting
                LEFT JOIN Annonceur
                ON Casting.ANN_CAS = Annonceur.ID_ANN
            WHERE Annonceur.LIB_ANN = :annonceur;"      
        );
        try{
            $stmt = $db->prepare($query);
            $stmt->bindParam(':annonceur',$annonceur, PDO::PARAM_STR); 
            $stmt->execute();
        } catch (PDOException $ex) {
           echo $ex; 
        }
        
        return $stmt;
    }
    
    
    //Gets
    public function getCastings()
    {
        $castings = array();
        $castings["castings"]=array();
        $stmtCasting = $this->queryCastings();
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

        $casting_item=array(
            "id"                => $row['ID_CAS'],
            "libelleCasting"    => $row['LIB_CAS'],
            "typeContrat"       => $row['TYPE_CON_CAS'],
            "lieuContrat"       => $row['LIEU_CON_CAS'],
            "libelleAnnonceur"  => $row['LIB_ANN'],
        );
        
        array_push($castings["castings"], $casting_item);
        }
        
        echo json_encode($castings);
        //echo json_last_error_msg();

        //inutile du coup 
        //return json_encode($castings);

    }
    

    public function getCasting($id)
    {
        $casting = array();
        $casting["casting N° ".$id]=array();
        $stmtCasting = $this->queryCasting($id);
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

            $casting_item=array(
                "id"                        => $row['ID_CAS'],
                "libelle"                   => $row['LIB_CAS'],
                "dateDebutContrat"          => $row['DAT_DEB_CON_CAS'],
                "nbPostes"                  => $row['NB_POS_CAS'],
                "descriptionPoste"          => $row['DES_POS_CAS'],
                "descriptionProfilt"        => $row['DES_PRO_CAS'],
                "typeContrat"               => $row['TYPE_CON_CAS'],
                "lieuContrat"               => $row['LIEU_CON_CAS'],
                "libelleAnnonceur"          => $row['LIB_ANN'],
                "nomContactAnnonceur"       => $row['NOM_CON_ANN'],
                "prenomContactAnnocneur"    => $row['PRE_CON_ANN'],
                "mailContactAnnonceur"      => $row['MAIL_CON_ANN']
            );

            array_push($casting["casting N° ".$id], $casting_item);
        }
        
        echo json_encode($casting);
    }
    
    public function getCastingsByDomaine ($domaine){
        $castings = array();
        $castings["casting domaine : ".$domaine]=array();
        $stmtCasting = $this->queryCastingByDomain($domaine);
        
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){
            
            $casting_item = array(
                "libelleCasting"    => $row['LIB_CAS'], 
                "libelleMetier"     => $row['LIB_MET'],                 
            );
            
            array_push($castings["casting domaine : ".$domaine], $casting_item);
        }
        
        echo json_encode($castings);
    }
    
    public function getCastingsByAnnonceur ($annonceur){
        $castings = array();
        $castings["casting annonceur : ".$annonceur]=array();
        $stmtCasting = $this->queryCastingByAnnonceur($annonceur);
        
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){
            $casting_item = array(
                "libelleCasting"    => $row['LIB_CAS']
            );
            
            array_push($castings["casting annonceur : ".$annonceur], $casting_item);
            
        }
        
         echo json_encode($castings);
    }
    
}
