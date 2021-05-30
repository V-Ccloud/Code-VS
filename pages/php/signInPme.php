<?php
// this file handles pme sign in

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
    $emailCheck = $db->prepare('SELECT COUNT(*) FROM pme WHERE pme_email=:mail');
    $emailCheck->bindValue(':mail', $email, PDO::PARAM_STR);
    $emailCheck->execute();
    $emailCheck=$emailCheck->fetchColumn();

    if ($emailCheck == 0){
        echo "error_Email invalide";
    }
    else{
        //---check if password id valid//----
        $mdpCheck = $db->prepare('SELECT pme_id, pme_password FROM pme WHERE pme_email=:mail');
        $mdpCheck->bindValue(':mail', $email, PDO::PARAM_STR);
        $mdpCheck->execute();
        $mdpCheck=$mdpCheck->fetch();

        if (!password_verify($mdp, $mdpCheck['pme_password'])) echo "error_Mot de passe invalide";
        else{ //check if account is activated
            $acountCheck = $db->prepare('SELECT pme_active FROM pme WHERE pme_id=:id');
            $acountCheck->bindValue(':id', $mdpCheck['pme_id'], PDO::PARAM_STR);
            $acountCheck->execute();
            $acountCheck=$acountCheck->fetch();

            if (!$acountCheck['pme_active']){ //compte pme not activated yet
                echo "enVerification_Nous avons trouvé votre compte, mais il n'a pas encore été validé par notre équipe.<br>Cela prend du temps ? <a href='?q=contact'>Contactez-nous</a>";
            }
            else{ //ouf, all is ok, connect to the pme account
                $_SESSION['pme_id'] = $mdpCheck['pme_id'];
                
                echo success("Connexion avec succès ! Chargement...");
            }
        }
    }
}
?>