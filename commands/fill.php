<?php 
require_once '../vendor/autoload.php';

use Faker\Factory;

$faker = Faker\Factory::create('fr_FR');
//Note : pas réussi à lancer le script dans le terminal de netbeans ,
//       se rendre sur localhost/API_REST/commands/fill.php pour lancer le 'script'
function getConnection() {
    $connection = null;

    try {
        $host = 'localhost\sqlexpress';
        $db_name = "MegaProductionBDD";
        $username = "MegaProduction_Admin";
        $password = "Not24Get";
        //new PDO("sqlsrv:Server=localhost;Database=testdb", "UserName", "Password");
        $connection = new PDO("sqlsrv:Server=" . $host . ";Database=" . $db_name, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        //$success=true;
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }

    return $connection;
}

$pdo = getConnection();


## Employe
for ($i = 0; $i < 10; $i++) {
    $query = "
            INSERT INTO Employe
            (Role,Nom, Prenom, Mail, Nom_Utilisateur, Mot_De_Passe)
            VALUES(
            '{$faker->numberBetween(1, 2)}',
            '{$faker->lastName}',
            '{$faker->firstName}',
            '{$faker->email}',
            '{$faker->userName}',
            '{$faker->word}')
             
;";
    //echo $query;
    try {
        $pdo->exec($query);
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }
}


##Annonceur
for($i = 0; $i < 10; $i++){
    $query="
        INSERT INTO Annonceur
        (Libelle,
        Mail,
        Telephone,
        Nom_Contact,
        Prenom_Contact,
        Mail_Contact,
        Telephone_Contact,
        Adresse,
        Code_Postal,
        Ville)
        VALUES (
        '{$faker->word}',
        '{$faker->email}',
        '{$faker->phoneNumber}',
        '{$faker->lastName}',
        '{$faker->firstName}',
        '{$faker->email}',
        '{$faker->phoneNumber}',
        '{$faker->streetAddress}',
        '{$faker->postcode}',
        '{$faker->city}'
        );";
    try {
        $pdo->exec($query);
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }    
}


##Metier
for($i = 0; $i < 15; $i++){
    $query="
        INSERT INTO Metier
        (Libelle,
        Domaine)
        VALUES
        ('{$faker->jobTitle}',
         '{$faker->numberBetween(1, 4)}'
        );";
 
    try {
        $pdo->exec($query);
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }   
}


##Casting
for ($i = 0; $i < 50; $i++) {
    $query = "
        INSERT INTO Casting
        (Libelle, 
        Date_Debut_Publication, 
        Nb_Jour_Diffusion, 
        Date_Debut_Contrat, 
        Nb_Poste, 
        Description_Poste, 
        Description_Profil, 
        Type_Contrat,
        Adresse_Contrat, 
        Annonceur,
        Employe_Createur, 
        Metier)
        VALUES(
        '{$faker->word}',
        '{$faker->numberBetween(1, 30)}/{$faker->numberBetween(1, 12)}/{$faker->numberBetween(2020, 2025)}',
        '{$faker->numberBetween(1, 50)}',
        '{$faker->numberBetween(1, 30)}/{$faker->numberBetween(1, 12)}/{$faker->numberBetween(2020, 2025)}',
        '{$faker->numberBetween(1, 35)}',
        '{$faker->paragraph(rand(5, 10))}',
        '{$faker->paragraph(rand(3, 5))}',
        '{$faker->word}',
        '{$faker->city}',
        '{$faker->numberBetween(1, 10)}',
        '{$faker->numberBetween(1, 10)}',
        '{$faker->numberBetween(1, 15)}');";
        
    try {
        $pdo->exec($query);
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }
}


##Offre
for($i = 0; $i < 5; $i++){
    $query="
        INSERT INTO Offre
        (Type,
        Tarif,
        Nb_Casting)
        VALUES
        ('{$faker->word}',
         '{$faker->randomFloat(2, 100, 5000)}',
         '{$faker->numberBetween(1, 100)}'
        );";
    
    try {
        $pdo->exec($query);
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }
}


##Partenaire de Diffusion
for($i = 0; $i < 15; $i++){
    $query="
        INSERT INTO PartenaireDiffusion
        (Libelle,
        Mail,
        Telephone,
        Nom_Contact,
        Prenom_Contact,
        Mail_Contact,
        Telephone_Contact,
        Adresse,
        Code_Postal,
        Ville,
        Site,
        Nom_Utilisateur,
        Mot_De_Passe)
        VALUES (
        '{$faker->word}',
        '{$faker->email}',
        '{$faker->phoneNumber}',
        '{$faker->lastName}',
        '{$faker->firstName}',
        '{$faker->email}',
        '{$faker->phoneNumber}',
        '{$faker->streetAddress}',
        '{$faker->postcode}',
        '{$faker->city}',
        '{$faker->domainName}', 
        '{$faker->userName}',
        '{$faker->word}'
        );";
    
    try {
        $pdo->exec($query);
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }
}


##Type de Contenu Editorial
for($i = 0; $i < 15; $i++){
    $query="
        INSERT INTO TypeContenuEditorial
        (Libelle) VALUES ('{$faker->word}')
        ;";
    
    try {
        $pdo->exec($query);
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }
}


##Contenu Editorial
for($i = 0; $i < 40; $i++){
    $query="
        INSERT INTO ContenuEditorial
        (Titre,
        Date_Publication,
        Type)
        VALUES
        ('{$faker->words(rand(1,5), true)}',
         '{$faker->numberBetween(1, 30)}/{$faker->numberBetween(1, 12)}/{$faker->numberBetween(2020, 2025)}',
         '{$faker->numberBetween(1, 15)}'
        );";
    
    try {
        $pdo->exec($query);
    } catch (PDOException $ex) {
        echo "Erreur de connexion : " . $ex->getMessage();
    }
}


//##Domaine par partenaire
//for($i = 0; $i < 50; $i++){
//    $query="
//        INSERT INTO 
//        );";
//    
//    try {
//        $pdo->exec($query);
//    } catch (PDOException $ex) {
//        echo "Erreur de connexion : " . $ex->getMessage();
//    }
//}


