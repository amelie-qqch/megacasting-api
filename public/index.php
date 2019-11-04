<?php
header('Content-Type: application/json');

//TODO api rest
/*
 * Connexion à la BDD 
 * => ne pas mettre le mdp en clair
 * => Changer localhost par l'adresse du srv
 * X Création de MegaProduction_Lecteur mappé en public et data reader sur la bdd
 * => Mettre la chaine de connexion dans une fonction pour pouvoir la réutiliser
 */



    if ($success){
        echo GetCastings($pdo);
    }else{
        echo'</br> connexion à la bdd KO';
    }
 
    
    
function GetCastings($pdo){
    $requete = 'SELECT * FROM Casting';
    $castings = $pdo->query($requete)->fetchAll();
    $castingsJSON = json_encode($castings);
    return $castingsJSON;
}

