<div class="mantainer-fluid">
	<div class="row">
		<div class="mal-12">
			<h2><b>Produits du site</b></h2>
			<hr><hr>
		</div>
	</div>
	
	<div class="row">
		<?php
		//----------affichage des clients//------------
		$query=$db->prepare('SELECT * FROM products ORDER BY pr_id DESC LIMIT 1000');
		$query->execute();

        $nbr=0;
		while ($data=$query->fetch()){
            $nbr++;

            $pme=$db->prepare('SELECT pme_nom FROM pme WHERE pme_id='.$data['pme_id']);
            $pme->execute();
            $pme=$pme->fetch();
            ?>
            <div class="col-6 col-md-3" style="padding:20px">
                <div class="row" style="box-shadow:0 2px 4px 0 rgba(0,0,0,0.2); border-bottom-left-radius:5px; border-bottom-right-radius:5px; background:#fff; padding-bottom:20px">
                    <div class="col-12" style="padding:15px">
                        <div>
                            <h5><b>(<?php echo $nbr ?>)</b> <?php echo $data['pr_title'] ?></h5>
                            <hr>
                        </div>
                        <div>
                            <p><b>PME : </b><?php echo $pme['pme_nom'] ?></p>
                            <p><b>Type : </b><?php echo $data['pr_type'] ?></p>
                            <p><b>Prix : </b><?php echo $data['pr_prix'].$devise ?></p>
                        </div>
                        <div style="display:flex; flex-direction:row; flex-wrap:wrap">
                            <div id="accordion<?php echo $data['pr_id'] ?>" style="width:100%">
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link" data-toggle="collapse" href="#collapseA<?php echo $data['pr_id'] ?>">Description</a>
                                    </div>
                                    <div id="collapseA<?php echo $data['pr_id'] ?>" class="collapse" data-parent="#accordion<?php echo $data['pr_id'] ?>">
                                        <div class="card-body">
                                            <p><?php echo $data['pr_description'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (isset($data['pr_photo_1'])){ ?>
                                <img src="../img/products/<?php echo $data['pr_photo_1'] ?>" class="img-responsive" style="max-width:100px; margin:3px">
                            <?php } ?>
                            
                            <?php if (isset($data['pr_photo_2'])){ ?>
                                <img src="../img/products/<?php echo $data['pr_photo_2'] ?>" class="img-responsive" style="max-width:100px; margin:3px">
                            <?php } ?>
                            
                            <?php if (isset($data['pr_photo_3'])){ ?>
                                <img src="../img/products/<?php echo $data['pr_photo_3'] ?>" class="img-responsive" style="max-width:100px; margin:3px">
                            <?php } ?>
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