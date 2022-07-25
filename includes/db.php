<?php
//connexion à la bdd
//try
//{
//	$db=new PDO ('mysql:host=localhost; dbname=ogondoco_ogondo', 'ogondoco', '9d8-0fYYBh5(pD', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
//}
//catch (Exception $e)
//{
//	die('Erreur : ' . $e->getMessage());
//}


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