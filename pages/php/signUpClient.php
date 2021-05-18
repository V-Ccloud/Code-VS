<?php
// this file handles user (not pme) sign up

if(session_status() == PHP_SESSION_NONE){ //check if sessions already started or not
    session_start(); //session has not started yet, start
}

include_once('../../includes/db.php'); //data base connexion
include_once('../../includes/functions.php'); //data base connexion

$nom = (isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
$prenom = (isset($_POST['prenom']))?(htmlspecialchars($_POST['prenom'])):'';
$email = (isset($_POST['email']))?(htmlspecialchars($_POST['email'])):'';
$mdp = (isset($_POST['mdp']))?(htmlspecialchars($_POST['mdp'])):'';
$nom = strip_tags($nom);
$prenom = strip_tags($prenom);
$email = strip_tags($email);
$mdp = strip_tags($mdp);

if (strlen($nom)<2 or strlen($nom)>20) echo "error_Nom invalide. 2 caractères min et 20 max."; //error_ helps js/main.js (signUpClient()) to know that the to display is an error
else if (strlen($prenom)<2 or strlen($prenom)>20) echo "error_Prénom invalide. 2 caractères min et 20 max";
else if (strlen($mdp)<6) echo "error_Mot de passe invalide. 6 caractères min";
else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) echo "error_Ceci n'est pas une adresse mail valide";
else{
    //---check if email not exists yet//-----
    $emailCheck = $db->prepare('SELECT COUNT(*) FROM users WHERE us_email=:mail');
    $emailCheck->bindValue(':mail', $email, PDO::PARAM_STR);
    $emailCheck->execute();
    $emailCheck=$emailCheck->fetchColumn();

    if ($emailCheck > 0){
        echo "error_Cette adresse mail existe déjà";
    }
    else{ //all ok
        $query=$db->prepare('INSERT INTO users(us_nom, us_prenom, us_email, us_password) VALUES(:nom, :pre, :mail, :mdp)');
        $query->bindValue(':nom', $nom, PDO::PARAM_STR);
        $query->bindValue(':pre', $prenom, PDO::PARAM_STR);
        $query->bindValue(':mail', $email, PDO::PARAM_STR);
        $query->bindValue(':mdp', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() == 0){
            echo "error_Erreur inconnue, impossible de créer votre compte";
        }
        else{
            $query2=$db->prepare('SELECT us_id FROM users WHERE us_email=:mail LIMIT 1');
            $query2->bindValue(':mail', $email, PDO::PARAM_STR);
            $query2->execute();
            $usId = $query2->fetch();
            $usId=$usId['us_id'];

            $_SESSION['us_id'] = $usId;

            echo success("Compte créé avec succès ! Chargement...");
        }
        $query->closeCursor();
    }
}
?>