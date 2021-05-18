<?php
// this file handles user (not pme) sign in

if(session_status() == PHP_SESSION_NONE){ //check if sessions already started or not
    session_start(); //session has not started yet, start
}

include_once('../../includes/db.php'); //data base connexion
include_once('../../includes/functions.php'); //data base connexion

$email = (isset($_POST['email']))?(htmlspecialchars($_POST['email'])):'';
$mdp = (isset($_POST['mdp']))?(htmlspecialchars($_POST['mdp'])):'';
$email = strip_tags($email);
$mdp = strip_tags($mdp);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) echo "error_Ceci n'est pas une adresse mail valide";
else{
    //---check if email is correct//-----
    $emailCheck = $db->prepare('SELECT COUNT(*) FROM users WHERE us_email=:mail');
    $emailCheck->bindValue(':mail', $email, PDO::PARAM_STR);
    $emailCheck->execute();
    $emailCheck=$emailCheck->fetchColumn();

    if ($emailCheck == 0){
        echo "error_Email invalide";
    }
    else{
        //---check if password id valid//----
        $mdpCheck = $db->prepare('SELECT us_id, us_password FROM users WHERE us_email=:mail');
        $mdpCheck->bindValue(':mail', $email, PDO::PARAM_STR);
        $mdpCheck->execute();
        $mdpCheck=$mdpCheck->fetch();

        if (!password_verify($mdp, $mdpCheck['us_password'])) echo "error_Mot de passe invalide";
        else{ //all ok
            $_SESSION['us_id'] = $mdpCheck['us_id'];

            echo success("Connexion avec succès ! Chargement...");
        }
    }
}
?>