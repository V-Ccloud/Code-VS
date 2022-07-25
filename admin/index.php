<?php session_start(); ?>

<!--
Author: Josué
Author URL: http://mailto:jose.init.dev@gmail.com
Website Owner : Link PME
License: All Rights Reserved TN Shekina
License URL: 
First version date : 2021-06-21
Version: 1.0  -  date: 2021-06-21
-->

<!doctype html>
<html lang="fr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Admin : Link PME</title>

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- google fonts -->
  <link href="//fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900&display=swap" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="assets/images/icon-site.png" type="text/css" media="all" />
	<?php
	include('includes/constants.php');
	include('includes/db.php');
	include('includes/functions.php');
	
	//-----------permet de reconnaitre le client--------------
	if (isset($_SESSION['ad_nom']) and isset($_SESSION['ad_id'])){
		$ad_nom=$_SESSION['ad_nom'];
		$ad_id=$_SESSION['ad_id'];
	}
	else{
		$ad_nom = $nom_site;
		$ad_id = 0;
	}
	//-----------//permet de reconnaitre le client--------------
	?>
</head>
<body>

	<?php
	if ($ad_id>0){ //on est déjà connecté
		?><script>window.location.href="accueil.php";</script><?php
	}
	
	$mail=(isset($_POST['mail']))?(htmlspecialchars($_POST['mail'])):'';
	$mdp=(isset($_POST['mdp']))?(htmlspecialchars($_POST['mdp'])):'';
	//echo password_hash($mdp,PASSWORD_DEFAULT);
	
	if (isset($_GET['submit'])){
		$query=$db->prepare('SELECT ad_nom, ad_mail, ad_id, ad_mdp FROM admins WHERE ad_mail=:m');
		$query->bindValue(':m', $mail, PDO::PARAM_STR);
		$query->execute();
		$data=$query->fetch();
		
		if (!isset($data['ad_mail'])){
            $err="Email invalide";
        }
		elseif ($data['ad_mail'] != $mail){
			$err="Email invalide";
		}
		elseif (!password_verify($mdp, $data['ad_mdp'])){
			$err="Mot de passe invalide";
		}
		else{ //tout est ok
			$query=$db->prepare('UPDATE admins SET ad_co=1 WHERE ad_id=:id');
			$query->bindValue(':id', $data['ad_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
			
			$_SESSION['ad_id']=$data['ad_id'];
			$_SESSION['ad_nom']=$data['ad_nom'];
			?><script>window.location.href="accueil.php";</script><?php
		}
	}
	?>
	
<div style="background:rgba(162,14,34,0.3); height:100vh">
	<div class="container">
		<div class="row">
			<div class="col-xs-2 col-md-3">
				<p></p>
			</div>
			<div class="col-xs-8 col-md-6">
				<div style="height:20vh"></div>
				<div style="padding:20px; border-style:outset">
					<center><img src="assets/images/logo.png" style="height:70px"></center>
					<hr>
					<form method="post" action="index.php?submit=true">
						<div class="form-group">
							<center><h3 style="color:rgb(140,0,0)"><b>Connexion Admin VS-CLOTHS</b></h3></center>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control" name="mail" id="mail" value="<?php echo $mail ?>" required="" minlength="6">
						</div>
						<div class="form-group">
							<label>Mot de passe</label>
							<input type="password" class="form-control" name="mdp" id="mdp" value="<?php echo $mdp ?>" required="" minlength="6">
						</div>
						<div class="form-group">
							<center><button type="submit" class="btn btn-dark"><i class="fa fa-key"></i> CONNEXION</button></center>
						</div>
						<?php if (isset($err)){ ?>
						<div class="form-group alert alert-warning">
							<label><?php echo $err ?></label>
						</div>
						<?php } ?>
					</form>
				</div>
			</div>
			<div class="col-xs-2 col-md-3">
				<p></p>
			</div>
		</div>
	</div>
</div>

<!-- Bootstrap Core JavaScript -->
<script src="assets/js/bootstrap.min.js"></script>

</body>

</html>