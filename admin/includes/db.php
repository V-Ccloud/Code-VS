<?php

//connexion à la bdd
// try
// {
// 	$db=new PDO ('mysql:host=91.216.107.182; dbname=tnshe1645146', 'tnshe1645146', 'K8Mz6R6Y6x8@', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
// }
// catch (Exception $e)
// {
// 	die('Erreur : ' . $e->getMessage());
// }


//connexion à la bdd en local
try
{
	$db=new PDO ('mysql:host=localhost; dbname=vscloths', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}
//this code is created by Josué - jose.init.dev@gmail.com

?>