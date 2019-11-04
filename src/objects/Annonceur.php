<?php

/**
 * Description of Annonceur
 *
 * @author Ame
 */
class Annonceur {
   
    private $connection;
    private $table_name = "Annonceur";
    
    public $id;
    public $nomContact;
    public $prenomContact;
    public $mailContact;
    public $telContact;
    
    public function __construct($db){
        $this->connection = $db;
    }
    
    function getAnnonceur(){
        
        $query = "SELECT NOM_CON_ANN, PRE_CON_ANN, MAIL_CON_ANN, TEL_CON_ANN FROM". $this->table_name;
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
}
