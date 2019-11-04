<?php

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
    
    function GetCastings($pdo){
    $requete = 'SELECT * FROM Casting';
    $castings = $pdo->query($requete)->fetchAll();
    $castingsJSON = json_encode($castings);
    return $castingsJSON;
}
}
