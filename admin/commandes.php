<div class="mantainer-fluid">
	<div class="row">
		<div class="mal-12">
			<h2><b>Commandes reçues</b></h2>
			<hr><hr>
		</div>
	</div>
	
    <!-- commande non livrée -->
	<div class="row" style="max-height: 1000px; overflow:auto">
		<?php
		$query=$db->prepare('SELECT * FROM commandes WHERE cm_livree=0 ORDER BY cm_id DESC LIMIT 1000');
		$query->execute();

		while ($data=$query->fetch()){
            ?>
            <div class="col-6 col-md-3" style="padding:20px; max-height:400px; overflow:auto">
                <div class="row" style="box-shadow:0 2px 4px 0 rgba(0,0,0,0.2); border-bottom-left-radius:5px; border-bottom-right-radius:5px; background:#fff; padding-bottom:20px">
                    <div class="col-12" style="padding:15px">
                        <?php
                        $articles=explode(';;', $data['cm_articles']);

                        if ($data['cm_livree']){
                            echo '<span class="badge badge-success">Livrée</span>';
                        }
                        else{
                            echo '<span class="badge badge-danger">Non Livrée</span>';
                        }
                        ?>
                        <h6><b>Date : </b> <?php echo substr($data['cm_date'],0,10).' <b>à</b> '.substr($data['cm_date'],10) ?></h6>
                        <h6><b>ID commande : </b> <?php echo $data['cm_commandeId'] ?></h6>
                        <h6><b>Prix total :</b> <?php echo $data['cm_prix_total'].' '.$devise ?></h6>
                        <h6><b>Mode de paiement : </b> <?php echo $data['cm_mode_paiement'] ?></h6>
                        <h6><b>Mode de livraison : </b> <?php echo $data['cm_mode_livraison'] ?></h6>
                        <h6><b>Adresse de livraison : </b> <?php echo $data['cm_adresse_livraison'] ?></h6>
                        <h6><b>Articles :</b></h6>

                        <div style="display:flex; flex-direction:row; flex-wrap:wrap">
                            <?php
                            foreach ($articles as $article){
                                $article = explode(';', $article);

                                $articleId = (int) $article[0];

                                $query2=$db->prepare('SELECT pr_photo_1, pr_title, pr_prix FROM products WHERE pr_id=:id');
                                $query2->bindValue(':id', $articleId, PDO::PARAM_INT);
                                $query2->execute();
                                $data2=$query2->fetch();
                                
                                if (isset($data2['pr_photo_1'])){
                                    ?>
                                    <div style="padding:2px; border:1px solid silver">
                                        <b style="color:blue"><?php echo $data2['pr_title'] ?></b><br>
                                        <i style="color:blue"><?php echo $data2['pr_prix'].$devise ?></i><br>
                                        <img src="../img/products/<?php echo $data2['pr_photo_1'] ?>" class="img-responsive" style="max-width:100px; margin:3px">
                                    </div>
                                    <?php
                                }
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
    <!-- //commande non livrée -->
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