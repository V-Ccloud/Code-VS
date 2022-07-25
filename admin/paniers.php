<div class="mantainer-fluid">
	<div class="row">
		<div class="mal-12">
			<h2><b>Paniers des clients</b></h2>
            <i>Ce sont les paniers contenant des produits non encore validés</i>
			<hr><hr>
		</div>
	</div>	
	<div class="row" style="background:silver; padding:30px">
		<div class="mal-12">
			<?php
            //----delete a client//----
			if (isset($_GET['delete'])){
                $id=$_GET['id'];
				echo '<hr>Êtes-vous sûre de bien vouloir supprimer ce produit du panier du client ?<br>';
				echo '<a href="accueil.php?type=paniers&delOk=true&id='.$id.'&ds=0"><button class="btn btn-warning">Confirmer la suppression ?</button></a> ';
				echo '<a href="accueil.php?type=paniers"><button class="btn btn-primary">Annuler</button></a><hr>';
			}
            else if (isset($_GET['delOk'])){
                $query2=$db->prepare('DELETE FROM paniers WHERE pa_id=:id LIMIT 1');
                $query2->bindValue(':id', (int) $_GET['id'], PDO::PARAM_INT);
                $query2->execute();
                $query2->closeCursor();
                
                error('Produit supprimé du panier avec succès !');
            }
			?>
		</div>
	</div>
	
	<div class="row">
		<?php
		//----------affichage des clients//------------
		$query=$db->prepare('SELECT DISTINCT users.us_nom, paniers.us_id FROM paniers INNER JOIN users ON users.us_id=paniers.us_id ORDER BY paniers.pa_id DESC LIMIT 1000');
		$query->execute();

		while ($data=$query->fetch()){
            ?>
            <div class="col-6 col-md-3" style="padding:20px">
                <div class="row" style="box-shadow:0 2px 4px 0 rgba(0,0,0,0.2); border-bottom-left-radius:5px; border-bottom-right-radius:5px; background:#fff; padding-bottom:20px">
                    <div class="col-12" style="padding:15px">
                        <div>
                            <h5>Panier de <b><?php echo $data['us_nom'] ?></b></h5>
                            <hr>
                        </div>
                        <div style="display:flex; flex-direction:row; flex-wrap:wrap">
                            <?php
                            $query3=$db->prepare('SELECT pa_id, pa_quantite, art_titre, art_prix, art_photo_1 FROM articles INNER JOIN paniers ON articles.art_id=paniers.art_id WHERE paniers.us_id=:id ORDER BY paniers.pa_id DESC');
                            $query3->bindValue(':id', $data['us_id'], PDO::PARAM_INT);
                            $query3->execute();

                            while ($data3=$query3->fetch()){
                                ?>
                                <div style="border:1px solid silver; padding:4px; border-radius:5px; margin:2px">
                                    <p><b>Article : </b><?php echo $data3['art_titre'] ?></p>
                                    <p><b>Quantité : </b><?php echo $data3['pa_quantite'] ?></p>
                                    <p><b>Prix : </b><?php echo $data3['art_prix'].' '.$devise ?></p>
                                    <img src="../images/articles/<?php echo $data3['art_photo_1'] ?>" class="img-responsive" style="max-width:100px; margin:3px">
                                    <br>
                                    <a href="accueil.php?type=paniers&delete=true&id=<?php echo $data3['pa_id'] ?>"><button class="btn btn-dark btn-sm"> Supprimer du panier</button></a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
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