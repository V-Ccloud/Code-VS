<div class="mantainer-fluid">
	<div class="row">
		<div class="mal-12">
			<h2>PME en attente de confirmation</h2>
			<hr><hr>
		</div>
	</div>	
	<div class="row" style="background:silver; padding:30px">
		<div class="mal-12">
			<?php
            //----delete a client//----
            if (isset($_GET['setActive'])){
                if ($_GET['setActive'] == '1'){ //activer
                    $query2=$db->prepare('UPDATE pme SET pme_active=1 WHERE pme_id=:id LIMIT 1');
                    $query2->bindValue(':id', (int) $_GET['pme'], PDO::PARAM_INT);
                    $query2->execute();
                    $query2->closeCursor();
                
                    success('PME activée avec succès !');
                }
                else if ($_GET['setActive'] == '0'){ //désactiver
                    $query2=$db->prepare('UPDATE pme SET pme_active=0 WHERE pme_id=:id LIMIT 1');
                    $query2->bindValue(':id', (int) $_GET['pme'], PDO::PARAM_INT);
                    $query2->execute();
                    $query2->closeCursor();
                
                    error('PME désactivée avec succès !');
                }
            }
			?>
		</div>
	</div>
	
	<div class="row">
		<?php
		//----------affichage des clients//------------
		$query=$db->prepare('SELECT * FROM pme WHERE pme_active=0 ORDER BY pme_id DESC LIMIT 1000');
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