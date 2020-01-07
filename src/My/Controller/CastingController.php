<?php

namespace My\Controller;

use My\Config\Database;
use PDO;

class CastingController {
    //9 articles par page car ligne de 3 items
    const ITEM_PAR_PAGE = 9;
    
    private function getDatabase(){
        $database = new Database();
        $db = $database->getConnection();
        return $db;
    }
    
    //Regrouper toutes les queries ensemble et tout les GETS ensemble ou bien regrouper par routes (la query avec le get correspondant?)
    
    ##Queries
    
    private function countItem(){
        $db = $this->getDatabase();
        
        $query = sprintf("
            SELECT COUNT(Identifiant) as 'nbCastings'
            FROM Casting;"
            );
        try{
            $stmt = $db->prepare($query);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $stmt['nbCastings'];   
    }
    
    private function countItemByDomain($domaine){
        $db = $this->getDatabase();
        
        $query = sprintf("
            SELECT COUNT(Casting.Identifiant) as 'nbCastings'
            FROM Casting
            LEFT JOIN Metier
            ON Casting.Metier = Metier.Identifiant
            LEFT JOIN Domaine
            ON Metier.Domaine = Domaine.Identifiant
            WHERE Domaine.Libelle = :domaine;
             ");
        try{
            //$str = '\''.$domaine.'\'';
            $stmt = $db->prepare($query);
            $stmt->bindParam(':domaine',$domaine, PDO::PARAM_STR); 
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $stmt['nbCastings'];   
    }
    
    // Il faut utiliser les alias dans les requêtes afin d'éviter une confusion dans les fonctions Get car certains nom de colonnes reviennent dans plusieurs tables    
    private function queryCastings($pageCourante){
        $db = $this->getDatabase();
        $limite = (($pageCourante-1)*self::ITEM_PAR_PAGE);
        
        $query = sprintf("
            SELECT 
                Casting.Identifiant, 
                Casting.Libelle,
                Casting.Date_Debut_Publication,
                Nb_Poste,
                Type_Contrat,
                Casting.Adresse_Contrat,
                Metier.Libelle as 'Metier',
                Domaine.Libelle as 'Domaine',
                Annonceur.Libelle as 'Annonceur'
            FROM Casting 
            LEFT JOIN Annonceur
            ON Casting.Annonceur = Annonceur.Identifiant
            LEFT JOIN Metier
            ON Casting.Metier = Metier.Identifiant
            LEFT JOIN Domaine
            ON Metier.Domaine = Domaine.Identifiant
            ORDER BY Casting.Identifiant ASC
            OFFSET :limite ROWS FETCH NEXT :parPage ROWS ONLY;"
        );
        try{
            $stmt = $db->prepare($query);
            $stmt->bindParam(':limite',$limite, PDO::PARAM_INT);
            //Utiliser bindValue pour une constante (pas une ref)
            $stmt->bindValue(':parPage',self::ITEM_PAR_PAGE, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        return $stmt;
    }
    
    private function queryLastCastings(){
        $db = $this->getDatabase();        
        $query = sprintf("
            SELECT 
                TOP 10
                Casting.Identifiant, 
                Casting.Libelle,
                Casting.Date_Debut_Publication,
                Nb_Poste,
                Type_Contrat,
                Casting.Adresse_Contrat,
                Metier.Libelle as 'Metier',
                Domaine.Libelle as 'Domaine',
                Annonceur.Libelle as 'Annonceur'
            FROM Casting 
            LEFT JOIN Annonceur
            ON Casting.Annonceur = Annonceur.Identifiant
            LEFT JOIN Metier
            ON Casting.Metier = Metier.Identifiant
            LEFT JOIN Domaine
            ON Metier.Domaine = Domaine.Identifiant
            ORDER BY Casting.Date_Debut_Publication DESC"
        );
        try{
            $stmt = $db->prepare($query);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        return $stmt;
    }
    
    
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

    
    private function queryCastingByDomain($domaine, $pageCourante){
        $db = $this->getDatabase();
        $limite = (($pageCourante-1)*self::ITEM_PAR_PAGE);
        $query = sprintf("
            SELECT 
                Casting.Identifiant, 
                Casting.Libelle,
                Casting.Date_Debut_Publication,
                Nb_Poste,
                Type_Contrat,
                Casting.Adresse_Contrat,
		Metier.Libelle as 'Metier',
                Domaine.Libelle as 'Domaine',
                Annonceur.Libelle as 'Annonceur'
            FROM Casting 
            LEFT JOIN Annonceur
            ON Casting.Annonceur = Annonceur.Identifiant
            LEFT JOIN Metier
            ON Casting.Metier = Metier.Identifiant
            LEFT JOIN Domaine
            ON Metier.Domaine = Domaine.Identifiant
            WHERE Domaine.Libelle = :domaine
            ORDER BY Casting.Identifiant ASC
            OFFSET :limite ROWS FETCH NEXT :parPage ROWS ONLY;"            
        ); 
        //echo $query;
        try{
            $stmt = $db->prepare($query);
            $stmt->bindParam(':domaine',$domaine, PDO::PARAM_STR); 
            $stmt->bindParam(':limite',$limite, PDO::PARAM_INT);
            //Utiliser bindValue pour une constante (pas une ref)
            $stmt->bindValue(':parPage',self::ITEM_PAR_PAGE, PDO::PARAM_INT);
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

    public function getCastings($pageCourante)
    {       
        $nbItems = $this->countItem();
        $nbPage = ceil($nbItems/ self::ITEM_PAR_PAGE);
        $castings = array();
        //NE PAS OUBLIER DE DONNER UN NOM? AU TABLEAU SINON NULL PARAMETRE
        $castings["castings"]=array();
        $castings["nbPages"]=$nbPage;
        $stmtCasting = $this->queryCastings($pageCourante);
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

            $casting_item=array(
                "id"                => $row['Identifiant'],
                "libelleCasting"    => $row['Libelle'],
                "datePublication"   => $row['Date_Debut_Publication'],
                "nbPoste"           => $row['Nb_Poste'],
                "typeContrat"       => $row['Type_Contrat'],
                "lieuContrat"       => $row['Adresse_Contrat'],
                "metier"            => $row['Metier'],
                "domaine"           => $row['Domaine'],
                "libelleAnnonceur"  => $row['Annonceur']
            );
        
            array_push($castings["castings"], $casting_item);
        }
        
        //echo json_last_error_msg();
       // echo $nbItems;
        echo json_encode($castings);

    }
    
    
        public function getLastCastings()
    {       
        $castings = array();
        //NE PAS OUBLIER DE DONNER UN NOM? AU TABLEAU SINON NULL PARAMETRE
        $castings["castings"]=array();
        $stmtCasting = $this->queryLastCastings();
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

            $casting_item=array(
                "id"                => $row['Identifiant'],
                "libelleCasting"    => $row['Libelle'],
                "datePublication"   => $row['Date_Debut_Publication'],
                "nbPoste"           => $row['Nb_Poste'],
                "typeContrat"       => $row['Type_Contrat'],
                "lieuContrat"       => $row['Adresse_Contrat'],
                "metier"            => $row['Metier'],
                "domaine"           => $row['Domaine'],
                "libelleAnnonceur"  => $row['Annonceur']
            );
        
            array_push($castings["castings"], $casting_item);
        }
        
        //echo json_last_error_msg();
       // echo $nbItems;
        echo json_encode($castings);

    }
    

    public function getCasting($id)
    {
        $casting = array();
        $casting["casting"]=array();
        $stmtCasting = $this->queryCasting($id);
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

            $casting_item=array(
                "id"                        => $row['Identifiant'],
                "libelle"                   => $row['Libelle'],
                "dateDebutContrat"          => $row['Date_Debut_Contrat'],
                "nbPostes"                  => $row['Nb_Poste'],
                "descriptionPoste"          => $row['Description_Poste'],
                "descriptionProfil"         => $row['Description_Profil'],
                "typeContrat"               => $row['Type_Contrat'],
                "lieuContrat"               => $row['Adresse_Contrat'],
                "libelleAnnonceur"          => $row['Annonceur'],
                "nomContactAnnonceur"       => $row['Nom Contact Annonceur'],
                "prenomContactAnnocneur"    => $row['Prenom Contact Annonceur'],
                "mailContactAnnonceur"      => $row['Mail Contact Annonceur']
            );

            array_push($casting["casting"], $casting_item);
        }
        //var_dump($casting);
        echo json_encode($casting);
    }
    
    public function getCastingsByDomaine ($domaine, $pageCourante){
        $nbItems = $this->countItemByDomain($domaine);
        $nbPage = ceil($nbItems/ self::ITEM_PAR_PAGE);
        $castings = array();
        $castings["castings"]=array();
        $castings["nbPages"]=$nbPage;
        $stmtCasting = $this->queryCastingByDomain($domaine, $pageCourante);
        while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

            $casting_item=array(
                "id"                => $row['Identifiant'],
                "libelleCasting"    => $row['Libelle'],
                "datePublication"   => $row['Date_Debut_Publication'],
                "nbPoste"           => $row['Nb_Poste'],
                "typeContrat"       => $row['Type_Contrat'],
                "lieuContrat"       => $row['Adresse_Contrat'],
                "metier"            => $row['Metier'],
                "domaine"           => $row['Domaine'],
                "libelleAnnonceur"  => $row['Annonceur']
            );
        
            array_push($castings["castings"], $casting_item);
        }
        
        //echo json_last_error_msg();
       // var_dump($castings);
       // echo $nbItems;
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
