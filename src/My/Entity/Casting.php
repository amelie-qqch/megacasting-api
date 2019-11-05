<?php

namespace My\Entity;

/**
 * Description of Casting
 *
 * @author Ame
 */
class Casting {
    
    private $connection;
    private $table_name = "Casting";
    
    //Question : Je laisse tout en public ?
    public $id;
    public $libelle;
    public $dateDebutPublication;
    public $nbJourDiffusion;
    public $dateDebutContrat;
    public $nbPoste;
    public $addrNumVoie;
    public $addrTypeVoie;
    public $addrVoie;
    public $addrVille;
    public $addrCodePostal;
    public $descriptionPoste;
    public $descriptionProfil;
    public $annonceur; //clé étrangère
    
    //public $metier; Ajouter si bsn de plus d'info sur le metier?
    
    public function __construct($db){
        $this->connection = $db;
    }
    
    function getCastings(){
        $query = sprintf("
            SELECT 
                ID_CAS, 
                LIB_CAS, 
                DAT_DEB_PUB_CAS, 
                NB_JOU_DIF_CAS, 
                DAT_DEB_CON_CAS, 
                NB_POS_CAS, 
                NUM_VOIE_CAS,
                TYPE_VOIE_CAS, 
                VOIE_CAS,
                VIL_CAS, 
                CP_CAS,
                DES_POS_CAS, 
                DES_PRO_CAS 
            FROM %s",
            $this->table_name
        );
        try{
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        return $stmt;
    }
}
