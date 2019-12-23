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
    
    //Regrouper toutes les queries ensemble et tout les GETS ensemble ou bien regrouper par routes (la query avec le get correspondant?)
    
    ##Queries
    // Il faut utiliser les alias dans les requêtes afin d'éviter une confusion dans les fonctions Get car certains nom de colonnes reviennent dans plusieurs tables
    private function queryCasting($id){
        $db = $this->getDatabase();
        $query = sprintf("
            SELECT 
                Casting.Identifiant, 
                Casting.Libelle, 
                Date_Debut_Contrat, 
                Nb_Poste, 
                Description_Poste, 
                Description_Profil,
                Type_Contrat,
                Adresse_Contrat,
                Annonceur.Libelle as 'Annonceur',
                Annonceur.Nom_Contact as 'Nom Contact Annonceur',
                Annonceur.Prenom_Contact as 'Prenom Contact Annonceur',
                Annonceur.Mail_Contact as 'Mail Contact Annonceur'                        
            FROM Casting
            LEFT JOIN Annonceur
            ON Casting.Annonceur = Annonceur.Identifiant
            WHERE Casting.Identifiant = :id;"
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
                Casting.Identifiant, 
                Casting.Libelle,
                Type_Contrat,
                Casting.Adresse_Contrat,
                Annonceur.Libelle as 'Annonceur'
            FROM Casting 
            LEFT JOIN Annonceur
            ON Casting.Annonceur = Annonceur.Identifiant;"
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
                Casting.Libelle as 'Casting', 
                Domaine.Libelle as 'Domaine'
            FROM Casting
                LEFT JOIN Metier
                ON Casting.Metier = Metier.Identifiant
                LEFT JOIN Domaine
                ON Metier.Domaine = Domaine.Identifiant
            WHERE Domaine.Libelle = :domaine;"            
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
            SELECT Casting.Libelle
            FROM Casting
                LEFT JOIN Annonceur
                ON Casting.Annonceur = Annonceur.Identifiant
            WHERE Annonceur.Libelle = :annonceur;"      
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
        //NE PAS OUBLIER DE DONNER UN NOM? AU TABLEAU SINON NULL PARAMETRE
        $castings["castings"]=array();
        $stmtCasting = $this->queryCastings();
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

            $casting_item=array(
                "id"                => $row['Identifiant'],
                "libelleCasting"    => $row['Libelle'],
                "typeContrat"       => $row['Type_Contrat'],
                "lieuContrat"       => $row['Adresse_Contrat'],
                "libelleAnnonceur"  => $row['Annonceur']
            );
        
            array_push($castings["castings"], $casting_item);
        }
        
        //echo json_last_error_msg();

        echo json_encode($castings);

    }
    

    public function getCasting($id)
    {
        $casting = array();
        $casting["castings"]=array();
        $stmtCasting = $this->queryCasting($id);
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

            $casting_item=array(
                "id"                        => $row['Identifiant'],
                "libelle"                   => $row['Libelle'],
                "dateDebutContrat"          => $row['Date_Debut_Contrat'],
                "nbPostes"                  => $row['Nb_Poste'],
                "descriptionPoste"          => $row['Description_Poste'],
                "descriptionProfilt"        => $row['Description_Profil'],
                "typeContrat"               => $row['Type_Contrat'],
                "lieuContrat"               => $row['Adresse_Contrat'],
                "libelleAnnonceur"          => $row['Annonceur'],
                "nomContactAnnonceur"       => $row['Nom Contact Annonceur'],
                "prenomContactAnnocneur"    => $row['Prenom Contact Annonceur'],
                "mailContactAnnonceur"      => $row['Mail Contact Annonceur']
            );

            array_push($casting["castings"], $casting_item);
        }
        
        echo json_encode($casting);
    }
    
    public function getCastingsByDomaine ($domaine){
        $castings = array();
        $castings["casting domaine : ".$domaine]=array();
        $stmtCasting = $this->queryCastingByDomain($domaine);
        
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){
            
            $casting_item = array(
                "libelleCasting"    => $row['Casting'], 
                "libelleMetier"     => $row['Domaine']                 
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
                "libelleCasting"    => $row['Libelle']
            );
            
            array_push($castings["casting annonceur : ".$annonceur], $casting_item);
            
        }
        
         echo json_encode($castings);
    }
    
}
