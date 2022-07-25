<div class="mantainer-fluid">
	<div class="row">
		<div class="mal-12">
			<h2><b>Administrateurs</b> <a href="accueil.php?type=admins&addNew=true"><button class="btn btn-primary"><b>+ Ajouter nouvel admin</b></button></a></h2>
			<hr><hr>
		</div>
	</div>	
	<div class="row">
		<div class="mal-12">
			<?php
			if (isset($_GET['delete'])){
				echo '<hr>Êtes-vous sûre de bien vouloir supprimer cet admin ?<br>';
				echo '<a href="accueil.php?type=admins&deleteOk=true&id='.$_GET['id'].'"><button class="btn btn-danger">OUI</button></a> ';
				echo '<a href="accueil.php?type=admins"><button class="btn btn-primary">NON</button></a><hr>';
			}
			elseif (isset($_GET['deleteOk'])){
				$query2=$db->prepare('DELETE FROM admins WHERE ad_id=:id');
				$query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_INT);
				$query2->execute();
				$query2->closeCursor();
				
				success('Admin supprimé avec succès !');
			}
			//-------------editer un admin//-------------
			if (isset($_GET['edit'])){
				$query2=$db->prepare('SELECT * FROM admins WHERE ad_id=:id');
				$query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_INT);
				$query2->execute();
				$data2=$query2->fetch();
				?>
				<h3>Modification</h3>
				<form class="row" method="post" action="accueil.php?type=admins&editOk=1&id=<?php echo $_GET['id'] ?>" enctype="multipart/form-data">
					<div class="form-group col-xs-6 col-md-4">
						<label>Nom & prénom (*)</label>
						<input type="text" name="nom" value="<?php echo $data2['ad_nom'] ?>" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Email (*)</label>
						<input type="email" name="mail" value="<?php echo $data2['ad_mail'] ?>" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Ancien mot de passe (*)</label>
						<input type="password" name="mdp" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Nouveau mot de passe (*)</label>
						<input type="password" name="mdp2" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Rentrez de nouveau le mot de passe (*)</label>
						<input type="password" name="mdp3" class="form-control" required>
					</div>
					<div class="col-12">
						<hr>
						<button type="submit" class="btn btn-primary btn-lg">Modifier mes infos admin</button> 
						<a href="accueil.php?type=admins"><button type="button" class="btn btn-warning btn-lg">Annuler</button></a>
						<hr><hr>
					</div>
				</form>
				<?php
			}
			if (isset($_GET['editOk'])){
				$nom=(isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
				$mdp=(isset($_POST['mdp']))?(htmlspecialchars($_POST['mdp'])):'';
				$mdp2=(isset($_POST['mdp2']))?(htmlspecialchars($_POST['mdp2'])):'';
				$mdp3=(isset($_POST['mdp3']))?(htmlspecialchars($_POST['mdp3'])):'';
				$mail=(isset($_POST['mail']))?(htmlspecialchars($_POST['mail'])):'';
				
				$query2=$db->prepare('SELECT ad_mdp FROM admins WHERE ad_id=:id');
				$query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_INT);
				$query2->execute();
				$data2=$query2->fetch();
				
				if (!password_verify($mdp, $data2['ad_mdp'])){
					error('Ancien mot de passe incorrecte. Ce compte est-il vraiment le votre ?');
				}
				elseif ($mdp2 != $mdp3){
					error('Les deux nouveaux mots de passe ne correspondent pas. Ce compte est-il vraiment le votre ?');
				}
				elseif (strlen($mdp2)<6){
					error('Mot de passe trop court, minimum 6 caractères.');
				}
				else{
					$query2=$db->prepare('UPDATE admins SET ad_nom=:nom, ad_mail=:mail, ad_mdp=:mdp WHERE ad_id=:id');
					$query2->bindValue(':nom', $nom, PDO::PARAM_STR);
					$query2->bindValue(':mdp', password_hash($mdp2, PASSWORD_DEFAULT), PDO::PARAM_STR);
					$query2->bindValue(':mail', $mail, PDO::PARAM_STR);
					$query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_INT);
					$query2->execute();
					$query2->closeCursor();

					success('Admin modifié avec succès !');
				}
			}
			
			
			//-------------adjouter un admin//-------------
			if (isset($_GET['addNew'])){
				?>
				<h3>Nouvel administrateur</h3>
				<form class="row" method="post" action="accueil.php?type=admins&addNewOk=1">
					<div class="form-group col-xs-6 col-md-4">
						<label>Nom & prénom (*)</label>
						<input type="text" name="nom" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Email (*)</label>
						<input type="email" name="mail" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Mot de passe (*)</label>
						<input type="password" name="mdp" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Rentrez de nouveau le mot de passe (*)</label>
						<input type="password" name="mdp2" class="form-control" required>
					</div>
					<div class="col-12">
						<hr>
						<button type="submit" class="btn btn-primary btn-lg">Ajouter cet admin</button> 
						<a href="accueil.php?type=admins"><button type="button" class="btn btn-warning btn-lg">Annuler</button></a>
						<hr><hr>
					</div>
				</form>
				<?php
			}
			if (isset($_GET['addNewOk'])){
				$nom=(isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
				$mdp=(isset($_POST['mdp']))?(htmlspecialchars($_POST['mdp'])):'';
				$mdp2=(isset($_POST['mdp2']))?(htmlspecialchars($_POST['mdp2'])):'';
				$mail=(isset($_POST['mail']))?(htmlspecialchars($_POST['mail'])):'';
				
				$query2=$db->prepare('SELECT COUNT(*) FROM admins WHERE ad_mail=:mail');
				$query2->bindValue(':mail', $mail, PDO::PARAM_STR);
				$query2->execute();
				$nbr=$query2->fetchcolumn();
				
				if ($nbr>0){
					error('Cette adresse mail existe déjà.');
				}
				elseif ($mdp != $mdp2){
					error('Les deux mots de passe ne correspondent pas.');
				}
				elseif (strlen($mdp)<6){
					error('Mot de passe trop court, minimum 6 caractères.');
				}
				else{
					$query2=$db->prepare('INSERT INTO admins(ad_nom, ad_mail, ad_mdp, ad_date) VALUES(:nom, :mail, :mdp, :dat)');
					$query2->bindValue(':nom', $nom, PDO::PARAM_STR);
					$query2->bindValue(':mdp', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
					$query2->bindValue(':mail', $mail, PDO::PARAM_STR);
					$query2->bindValue(':dat', date('Y').'-'.date('m').'-'.date('d'), PDO::PARAM_STR);
					$query2->execute();
					$query2->closeCursor();

					success('Nouvel administrateur ajouté avec succès !');
				}
			}
			?>
		</div>
	</div>
	
	<div class="row">
		<?php
		//----------affichage des admins//------------
		$query=$db->prepare('SELECT * FROM admins ORDER BY ad_id DESC');
		$query->execute();

		while ($data=$query->fetch()){
			?>
			<div class="col-md-3" style="padding:20px">
				<?php if ($ad_id==$data['ad_id']){ //c'est son compte, il peut modifier et se supprimer lui même ?>
				<div class="coursBlock">
					<div>
						<b class="fa fa-user-circle"></b>
					</div>
					<span style="background: rgba(0,0,255,0.1)">
						<?php if ($data['ad_co']){ ?>
						<h5><b><?php echo $data['ad_nom'] ?></b> <i class="fa fa-dot-circle-o" style="color:lime"></i></h5>
						<?php }else { ?>
						<h5><b><?php echo $data['ad_nom'] ?></b> <i class="fa fa-dot-circle-o" style="color:red"></i></h5>
						<?php } ?>
						<p><b>Email:</b> <?php echo $data['ad_mail'] ?></p>
						<p><b>Inscription:</b> <?php echo $data['ad_date'] ?></p>
						<hr>
						<p>
							<a href="accueil.php?type=admins&edit=yes&id=<?php echo $data['ad_id'] ?>"><button class="btn btn-primary btn-sm">Editer</button></a> 
							<a href="accueil.php?type=admins&delete=yes&id=<?php echo $data['ad_id'] ?>"><button class="btn btn-warning btn-sm">supprimer</button></a>
						</p>
					</span>
				</div>
				<?php }else{ ?>
				<div class="coursBlock">
					<div>
						<b class="fa fa-user-circle"></b>
					</div>
					<span>
						<?php if ($data['ad_co']){ ?>
						<h5><b><?php echo $data['ad_nom'] ?></b> <i class="fa fa-dot-circle-o" style="color:lime"></i></h5>
						<?php }else { ?>
						<h5><b><?php echo $data['ad_nom'] ?></b> <i class="fa fa-dot-circle-o" style="color:red"></i></h5>
						<?php } ?>
						<p><b>Email:</b> <?php echo $data['ad_mail'] ?></p>
						<p><b>Inscription:</b> <?php echo $data['ad_date'] ?></p>
						<hr>
						<p>
							<button type="button" class="btn btn-primary btn-sm disabled">&times; Editer</button> 
							<button type="button" class="btn btn-warning btn-sm disabled">&times; supprimer</button>
						</p>
					</span>
				</div>
				<?php } ?>
			</div>
			<?php
		}
		?>
	</div>
</div>
<style>
/*-----------cours block-------------*/
.coursBlock div {
	position: absolute;
	left: inherit;
	top: 23%;
	background: #fff;
	width: 80px;
	height: 80px;
	border-radius: 50%;
	padding: 5px;
	font-size: 50px;
	border:1px solid #1A1452;
}
.coursBlock span {
	float: right;
	width: 90%;
	padding: 10px;
	border: 1px solid #1A1452;
	border-radius: 4px;
	text-align: right;
	color:#000;
}
.coursBlock span:hover {
	text-decoration: none;
	box-shadow: 2px 2px 10px 2px rgba(0,0,0,0.3);
	border: none;
}
/*-----------//cours block-------------*/
</style>