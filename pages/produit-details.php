<!-- this is the one that shows a product details -->
<p class="d-none d-md-block"><br><br></p>

<div class="container">
    <div class="row product-display">
        <?php
        $id = (int) (isset($_GET['id']))?(htmlspecialchars($_GET['id'])):'0'; //id of the product

        // ----check if product exists//----
        $nbr=$db->prepare('SELECT COUNT(*) FROM products WHERE pr_id=:id');
        $nbr->bindValue(':id', $id, PDO::PARAM_INT);
        $nbr->execute();
        $nbr = $nbr->fetchcolumn();

        if ($nbr == 0){ //no product found in data base with id $id
            echo '<div class="col-12">'.error("Aucun produit ne correspond à votre recherche. <a href='?q=produits'>Retour aux produits</a></div>");
        }
        else{ //product exists
            // -----get product details------
            $query=$db->prepare('SELECT * FROM products WHERE pr_id=:id LIMIT 1');
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $data = $query->fetch();
            // -----//get product details------
            ?>

            <!-- change page title and desc in head -->
            <script>
                document.querySelector('title').textContent = "<?php echo $data['pr_title'] ?>";
                document.querySelector('#pageDesc').textContent = "<?php echo $data['pr_description'] ?>";
            </script>
            <!-- //change page title and desc in head -->


            <!-- ----afiichage des details du produit---- -->
            <div class="col-md-6 photo">
                <div id="photosCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        <li data-target="#photosCarousel" data-slide-to="0" class="active"></li>
                        <?php if (strlen($data['pr_photo_2'])>4){ ?>
                            <li data-target="#photosCarousel" data-slide-to="1"></li>
                        <?php } ?>
                        <?php if (strlen($data['pr_photo_3'])>4){ ?>
                            <li data-target="#photosCarousel" data-slide-to="2"></li>
                        <?php } ?>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <center><img src="img/products/<?php echo $data['pr_photo_1'] ?>" class="img-fluid img-thumbnail"></center>
                        </div>
                        <?php if (strlen($data['pr_photo_2'])>4){ ?>
                            <div class="carousel-item">
                                <center><img src="img/products/<?php echo $data['pr_photo_2'] ?>" class="img-fluid img-thumbnail"></center>
                            </div>
                        <?php } ?>
                        <?php if (strlen($data['pr_photo_3'])>4){ ?>
                            <div class="carousel-item">
                                <center><img src="img/products/<?php echo $data['pr_photo_3'] ?>" class="img-fluid img-thumbnail"></center>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#photosCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#photosCarousel" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
                
                <!-- details// -->
            <div class="col-md-6 details">
                <h5><?php echo $data['pr_title'] ?></h5>
                <hr>
                <h2><?php echo $data['pr_prix'] ?><span style="font-size:14px"><?php echo $devise ?></h2>
                <p></p>
                <div class="">
                    <?php
                    $pme=$db->prepare('SELECT pme_logo, pme_nom FROM pme WHERE pme_id=:id LIMIT 1'); //get pme infos, the one qui vend ce produit
                    $pme->bindValue(':id', $data['pme_id'], PDO::PARAM_INT);
                    $pme->execute();
                    $pme=$pme->fetch();
                    ?>
                    <a href="?q=pme&pme=<?php echo $data['pme_id'] ?>">
                        <!-- the name// -->
                        <i>Vendu par <?php echo $pme['pme_nom'] ?></i>
                        <!-- the photo// -->
                        <div style="background:url('img/pme/<?php echo $pme['pme_logo'] ?>'); background-size:cover; background-position:center; width:50px; height:50px"></div>
                    </a>
                </div>
                <p></p>
                <button type="button" class="btn btn-success myBtn" onclick="addToCart('<?php echo $id ?>', '<?php echo $us_id ?>')">+<span class="fa fa-shopping-cart"></span> Ajouter au panier</button>
                <p></p>
                <p><?php echo $data['pr_description'] ?></p>
            </div>
            <!-- ----//afiichage des details du produit---- -->

            
            <div class="col-12">
                <hr><hr>
                <div class="textInLines">
                    <div class="line"></div>
                    <div class="text">PRODUITS SIMILAIRES</div>
                    <div class="line"></div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <?php
                    $query2=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_id ASC LIMIT 8');
                    $query2->bindValue(':type', $data['pr_type'], PDO::PARAM_STR);
                    $query2->execute();
                    
                    while ($data2=$query2->fetch()){
                        ?><div class="col-6 col-md-3"><?php
                        display_product($data2['pr_id'], $data2['pr_title'], $data2['pr_photo_1'], $data2['pr_prix']);
                        ?></div><?php
                    }
                    ?>
                </div>
            </div>
            <?php
        } //end of else 'product exists'
        ?>
    </div>
</div>