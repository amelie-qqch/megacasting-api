<?php

namespace My\Entity;

/**
 * Description of Annonceur
 *
 * @author Ame
 */
class Annonceur {
   
    /**
     *
     * @var PDO 
     */
    private $connection;
    private $table_name = "Annonceur";
    
    public $ID_ANN;
    public $NOM_CON_ANN;
    public $PRE_CON_ANN;
    public $MAIL_CON_ANN;
    public $TEL_CON_ANN;
    
    public function __construct($db){
        $this->connection = $db;
    }
    
    function getAnnonceur() {
        
        $query = "SELECT ID_ANN NOM_CON_ANN, PRE_CON_ANN, MAIL_CON_ANN, TEL_CON_ANN FROM " . $this->table_name;
        
        try{
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
        
        return $stmt;
    }
}
