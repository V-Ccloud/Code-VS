<?php
//get cart articles number from data base

include_once('../../includes/db.php'); //data base connexion

if(session_status() == PHP_SESSION_NONE){ //check if sessions already started or not
    session_start(); //session has not started yet, start
}

$cartCounter=$db->prepare('SELECT COUNT(*) FROM carts WHERE us_id=:us');
$cartCounter->bindValue(':us', $_SESSION['us_id'], PDO::PARAM_INT);
$cartCounter->execute();
$nbr=$cartCounter->fetchcolumn();
$cartCounter->closeCursor();

echo $nbr; //!!!IMPORTANT!!! don't echo something else
?>