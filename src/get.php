<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/Database.php';
include_once './objects/Casting.php';
include_once './objects/Annonceur.php';

$database = new Database();
$db = $database->getConnection();

$annonceur = new Annonceur($db);
$casting = new Casting($db);

//stmt = statement?
//TODO Factoriser
$stmtAnnonceur = $annonceur->getAnnonceur();
$stmtCasting = $casting->getCasting();
$numAnnonceur = $stmtAnnonceur->rowCount();
$numCasting = $stmtCasting->rowCount();

//TODO faire la même avec Casting
if($numAnnonceur>0){
    
    $annonceur_arr = array();
    $annonceur_arr["records"]=array();
    
    while($row = $stmtAnnonceur->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $annonceur_item=array(
            "id"=>$id,
            "nom du contact" => $nomContact,
            "prenom du contact" => $prenomContact,
            "mail du contact" => $mailContact,
            "telephone du contact"=> $telContact      
        );
        
        array_push($annonceur_arr["records"], $annonceur_item);
    }
    
    http_response_code(200);
    
    echo json_encode($annonceur_arr);
}else{
    
    http_response_code(404);
    
    echo json_encode(
            array("message" => "Aucun Annonceur trouvé."));
}


