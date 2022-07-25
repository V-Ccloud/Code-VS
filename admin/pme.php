<div class="mantainer-fluid">
	<div class="row">
		<div class="mal-12">
			<h2><b>PME</b> <a href="https://<?php echo $domaine ?>" target="_blank"><button class="btn btn-primary"><b>+ Ajouter nouvelle PME</b></button></a> <a href="accueil.php?type=confirmations"><button class="btn btn-dark"><b>Voir PME non confirmées</b></button></a></h2>
			<hr><hr>
		</div>
	</div>	
	<div class="row" style="background:silver; padding:30px">
		<div class="mal-12">
			<?php
            //----delete a client//----
			if (isset($_GET['delete'])){
                $id=$_GET['id'];
				echo '<hr>Êtes-vous sûre de bien vouloir supprimer cette pme ?<br>';
				echo '<a href="accueil.php?type=pme&delOk=true&id='.$id.'&ds=0"><button class="btn btn-warning">Confirmer la suppression ?</button></a> ';
				echo '<a href="accueil.php?type=pme"><button class="btn btn-primary">Annuler</button></a><hr>';
			}
            else if (isset($_GET['delOk'])){
                $query2=$db->prepare('DELETE FROM pme WHERE pme_id=:id LIMIT 1');
                $query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_INT);
                $query2->execute();
                $query2->closeCursor();
                
                error('PME supprimée avec succès !');
            }
			?>
		</div>
	</div>
	
	<div class="row">
		<?php
		//----------affichage des clients//------------
		$query=$db->prepare('SELECT * FROM pme ORDER BY pme_id DESC LIMIT 1000');
		$query->execute();

		while ($data=$query->fetch()){
            displayPme($data['pme_nom'], $data['pme_email'], $data['pme_description'], $data['pme_phone'], $data['pme_date'], $data['pme_id'], $data['pme_active']);
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