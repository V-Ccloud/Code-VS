<div class="mantainer-fluid">
	<div class="row">
		<div class="mal-12">
			<h2><b>Clients</b> <a href="https://<?php echo $domaine ?>" target="_blank"><button class="btn btn-primary"><b>+ Ajouter nouveau client</b></button></a></h2>
			<hr><hr>
		</div>
	</div>	
	<div class="row" style="background:silver; padding:30px">
		<div class="mal-12">
			<?php
            //----delete a client//----
			if (isset($_GET['delete'])){
                $id=$_GET['id'];
				echo '<hr>Êtes-vous sûre de bien vouloir supprimer ce client ?<br>';
				echo '<a href="accueil.php?type=users&delOk=true&id='.$id.'&ds=0"><button class="btn btn-warning">Confirmer la suppression ?</button></a> ';
				echo '<a href="accueil.php?type=users"><button class="btn btn-primary">Annuler</button></a><hr>';
			}
            else if (isset($_GET['delOk'])){
                $query2=$db->prepare('DELETE FROM users WHERE us_id=:id LIMIT 1');
                $query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_INT);
                $query2->execute();
                $query2->closeCursor();
                
                error('Client supprimé avec succès !');
            }
                
			//-------------editer un clients//-------------
			if (isset($_GET['edit'])){
				$query2=$db->prepare('SELECT * FROM users WHERE us_id=:id');
				$query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_INT);
				$query2->execute();
				$data2=$query2->fetch();
				?>
				<h3>Modification client</h3>
				<form class="row" method="post" action="accueil.php?type=users&editOk=1&id=<?php echo $_GET['id'] ?>">
					<div class="form-group col-xs-6 col-md-4">
						<label>Nom (*)</label>
						<input type="text" name="nom" value="<?php echo $data2['us_nom'] ?>" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Email (*)</label>
						<input type="email" name="email" value="<?php echo $data2['us_email'] ?>" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Adresse (*)</label>
						<input type="text" name="addr" value="<?php echo $data2['us_adresse'] ?>" class="form-control" required>
					</div>
					<div class="form-group col-xs-6 col-md-4">
						<label>Téléphone (*)</label>
						<input type="tel" name="phone" value="<?php echo $data2['us_phone'] ?>" class="form-control" required>
					</div>
					<div class="col-12">
						<hr>
						<button type="submit" class="btn btn-primary btn-lg">Modifier ce client</button> 
						<a href="accueil.php?type=users"><button type="button" class="btn btn-warning btn-lg">Annuler</button></a>
						<hr><hr>
					</div>
				</form>
				<?php
			}
			if (isset($_GET['editOk'])){
				$nom=(isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
				$addr=(isset($_POST['addr']))?(htmlspecialchars($_POST['addr'])):'';
				$email=(isset($_POST['email']))?(htmlspecialchars($_POST['email'])):'';
				$tel=(isset($_POST['phone']))?(htmlspecialchars($_POST['phone'])):'';
				
				$query2=$db->prepare('UPDATE users SET us_nom=:nom, us_email=:mail, us_adresse=:addr, us_phone=:tel WHERE us_id=:id');
				$query2->bindValue(':nom', $nom, PDO::PARAM_STR);
				$query2->bindValue(':addr', $addr, PDO::PARAM_STR);
				$query2->bindValue(':mail', $email, PDO::PARAM_STR);
				$query2->bindValue(':tel', $tel, PDO::PARAM_STR);
				$query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_STR);
				$query2->execute();
				$query2->closeCursor();
				
				success('Client modifié avec succès !');
			}
			?>
		</div>
	</div>
	
	<div class="row">
		<?php
		//----------affichage des clients//------------
		$query=$db->prepare('SELECT * FROM users ORDER BY us_id DESC LIMIT 1000');
		$query->execute();

		while ($data=$query->fetch()){
            displayClients($data['us_prenom'].' '.$data['us_nom'], $data['us_email'], $data['us_adresse'], $data['us_phone'], $data['us_pays'], $data['us_ville'], $data['us_date'], $data['us_id']);
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