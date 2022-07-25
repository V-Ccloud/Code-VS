<?php
//====================================================================================
// ETAPE 1 : on vérifie si l'IP se trouve déjà dans la table
// Pour faire ça, on n'a qu'à compter le nombre d'entrées dont le champ "ip" est l'adresse ip du visiteur
$retour = $db->prepare('SELECT COUNT(*) AS nbre_entrees FROM visiteurs WHERE ip=:a');
$retour->bindValue(':a',$_SERVER['REMOTE_ADDR'],PDO::PARAM_STR);
$retour->execute();
$donnees = $retour->fetch();
if ($donnees['nbre_entrees']==0) // L'ip ne se trouve pas dans la table, on va l'ajouter
{
    $query=$db->prepare('INSERT INTO visiteurs(ip, timestamp, jour, mois, annees, date) VALUES(:ip, :ts, :jr, :mois, :an, :date)');
	$query->bindValue(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
	$query->bindValue(':ts', time(), PDO::PARAM_INT);
	$query->bindValue(':mois', (int) date('m'), PDO::PARAM_INT);
	$query->bindValue(':an', (int) date('Y'), PDO::PARAM_INT);
	$query->bindValue(':jr', (int) date('d'), PDO::PARAM_INT);
	$query->bindValue(':date', date('Y-m-d'), PDO::PARAM_STR);
	$query->execute();
	$query->closeCursor();
}
else // L'ip se trouve déjà dans la table, on le remet si il y a 5min ecoulées
{
	$query=$db->prepare('select timestamp from visiteurs where ip=:b and timestamp>:c');
	$query->bindValue(':b', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
	$query->bindValue(':c', time()-300, PDO::PARAM_INT);
	$query->execute();
	$donnees2=$query->fetch();
	if (!empty($donnees2)) //on renouvelle l'ip car le visiteur est toujours connecté au site
	{
	    $query2=$db->prepare('UPDATE visiteurs SET timestamp=:a, date=:b, jour=:jr, mois=:m, annees=:an WHERE ip=:c');
		$query2->bindValue(':a',time(),PDO::PARAM_INT);
		$query2->bindValue(':b',date('d/m/Y'),PDO::PARAM_INT);
		$query2->bindValue(':m', (int) date('m'),PDO::PARAM_INT);
		$query2->bindValue(':an', (int) date('Y'), PDO::PARAM_INT);
		$query2->bindValue(':jr', (int) date('d'),PDO::PARAM_INT);
		$query2->bindValue(':c',$_SERVER['REMOTE_ADDR'],PDO::PARAM_STR);
		$query2->execute();
		$query2->closeCursor();
	}
	else //sinon on met de nouveau l'ip dans la bdd
	{
		$query1=$db->prepare('INSERT INTO visiteurs(ip, timestamp, jour, mois, annees, date) VALUES(:ip, :ts, :jr, :mois, :an, :date)');
		$query1->bindValue(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
		$query1->bindValue(':ts', time(), PDO::PARAM_INT);
		$query1->bindValue(':mois', (int) date('m'), PDO::PARAM_INT);
		$query1->bindValue(':an', (int) date('Y'), PDO::PARAM_INT);
		$query1->bindValue(':jr', (int) date('d'), PDO::PARAM_INT);
		$query1->bindValue(':date', date('Y-m-d'), PDO::PARAM_STR);
		$query1->execute();
		$query1->closeCursor();
	}
	$query->closeCursor();
}
$retour->closeCursor();  //=======================================================================

if (date('d')==1) //si c'est le debut du mois, on vide la table des visiteurs si 2 mois se sont écoulés
{
	$visit = $db->prepare('UPDATE visiteurs SET nbr_mois=nbr_mois+1'); //ajout 1 au nombre de mois
	$visit->execute();
	$visit->closeCursor();
}
?>