<?php

header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");

use My\Config\Database;
use My\Entity\Annonceur;
use My\Entity\Casting;

$database = new Database();
$db = $database->getConnection();

//Pas une bonne pratique de devoir faire un new Annonceur pour recup après les annonceurs
$casting = new Casting($db);

$stmtCasting = $casting->getCastings();
var_dump($stmtCasting->fetchAll());die;

    $casting_arr = array();
    $casting_arr["records"]=array();
    
    while($row = $stmtCasting->fetch(PDO::FETCH_ASSOC)){

        $casting_item=array(
            "id"=>$row['ID_CAS'],
            "libelle" => $row['LIB_CAS'],
            "dateDebutPublication" => $row['DAT_DEB_PUB_CAS'],
            "nbJoursDiffusion" => $row['NB_JOU_DIF_CAS'],
            "dateDebutContrat"=> $row['DAT_DEB_CON_CAS'],
            "nbPostes"=>$row['NB_POS_CAS'],
            "numeroVoie" => $row['NUM_VOIE_CAS'],
            "typeVoie" => $row['TYPE_VOIE_CAS'],
            "voie" => $row['VOIE_CAS'],
            "ville"=> $row['VIL_CAS'],
            "codePostale" => $row['CP_CAS'],
            "descriptionPoste" => $row['DES_POS_CAS'],
            "descriptionProfilt" => $row['DES_PRO_CAS'],
            "tannonceur"=> $row['ANN_CAS']
        );
        
        array_push($casting_arr["records"], $casting_item);
    }
    
    
    http_response_code(200);
    echo json_encode($casting_arr);


