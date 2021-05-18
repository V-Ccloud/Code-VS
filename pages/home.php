<?php
// this is home page


?>


<!----carousel---->
<div id="homeCarousel" class="carousel slide" data-ride="carousel">

<!-- Indicators -->
<ul class="carousel-indicators">
    <li data-target="#homeCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#homeCarousel" data-slide-to="1"></li>
    <li data-target="#homeCarousel" data-slide-to="2"></li>
</ul>

<!-- The slideshow -->
<div class="carousel-inner">
    <div class="carousel-item active myCarouselItem" style="background:url('img/carousel-laitiers.png'); background-size:cover; background-position:center">
        <div class="content">
            <div class="row">
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
                <div class="d-none d-sm-block col-dm-8 col-md-4 block">
                    <div class="image-laitier"></div>
                    <h1>Laitiers bios</h1>
                    <hr>
                    <h5>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</h5>
                    <hr>
                    <center>
                        <a href="#"><button class="btn btn-success myBtn">Découvrez <?php echo $site_name ?></button></a> 
                        <a href="?q=produits"><button class="btn btn-success myBtn">Découvrez nos produits</button></a>
                    </center>
                </div>
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
            </div>
        </div>
    </div>
    <div class="carousel-item myCarouselItem" style="background:url('img/carousel-legumes.png'); background-size:cover; background-position:center">
        <div class="content">
            <div class="row">
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
                <div class="d-none d-sm-block col-dm-8 col-md-4 block">
                    <div class="image-legume"></div>
                    <h1>Légumes bios</h1>
                    <hr>
                    <h5>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</h5>
                    <hr>
                    <center>
                        <a href="#"><button class="btn btn-success myBtn">Découvrez <?php echo $site_name ?></button></a> 
                        <a href="?q=produits"><button class="btn btn-success myBtn">Découvrez nos produits</button></a>
                    </center>
                </div>
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
            </div>
        </div>
    </div>
    <div class="carousel-item myCarouselItem" style="background:url('img/carousel-argumes.png'); background-size:cover; background-position:center">
        <div class="content">
            <div class="row">
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
                <div class="d-none d-sm-block col-dm-8 col-md-4 block">
                    <div class="image-argume"></div>
                    <h1>Agrûmes bios</h1>
                    <hr>
                    <h5>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</h5>
                    <hr>
                    <center>
                        <a href="#"><button class="btn btn-success myBtn">Découvrez <?php echo $site_name ?></button></a> 
                        <a href="?q=produits"><button class="btn btn-success myBtn">Découvrez nos produits</button></a>
                    </center>
                </div>
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
            </div>
        </div>
    </div>
</div>

<!-- Left and right controls -->
<a class="carousel-control-prev" href="#homeCarousel" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
</a>
<a class="carousel-control-next" href="#homeCarousel" data-slide="next">
    <span class="carousel-control-next-icon"></span>
</a>

</div>
<!----//carousel---->


<!-- promotions -->
<div class="container-fluid promotion">
    <div class="row">
        <div class="col-3 promotion-bg1">
            <span class="fa fa-shopping-cart icon" title="Livraison partout au Maroc"></span>
            <span class="d-none d-md-block">Livraison partout au Maroc</span>
        </div>
        <div class="col-3 promotion-bg2">
            <span class="fa fa-certificate icon" title="Qualité bio garantie"></span>
            <span class="d-none d-md-block">Qualité bio garantie</span>
        </div>
        <div class="col-3 promotion-bg1">
            <span class="fa fa-money icon" title="Paiement à la livraison"></span>
            <span class="d-none d-md-block">Paiement à la livraison</span>
        </div>
        <div class="col-3 promotion-bg2">
            <span class="fa fa-commenting icon" title="Service client 24h/7"></span>
            <span class="d-none d-md-block">Service client 24h/7</span>
        </div>
    </div>
</div>
<!-- promotions// -->

<!-- afficher quelques produits sur la page d'accueil -->
<div class="container myContainer">
    <div class="row">
        <div class="col-12">
            <h1>SELECTION DE PRODUITS</h1>
            <hr>
        </div>
        
        <!-- version pc et grand écran -->
        <div class="col-12 d-none d-md-block">
            <div class="row">
                <?php
                //--select all product depuis la base de données--
                $query=$db->prepare('SELECT * FROM products ORDER BY pr_id DESC LIMIT 12');
                $query->execute();
                //--//select all product depuis la base de données--

                //----affichage--
                while ($data=$query->fetch()){
                    ?><div class="col-md-3"><?php
                    display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                    ?></div><?php
                }
                //----//affichage--

                $query->closeCursor();
                ?>
            </div>
        </div>
        <!-- //version pc et grand écran -->

        <!-- version téléphone et tablette -->
        <div class="col-12 d-md-none">
            <div class="row">
                <?php
                //--select all product depuis la base de données--
                $query=$db->prepare('SELECT * FROM products ORDER BY pr_id DESC LIMIT 8');
                $query->execute();
                //--//select all product depuis la base de données--

                //----affichage--
                while ($data=$query->fetch()){
                    ?><div class="col-6"><?php
                    display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                    ?></div><?php
                }
                //----//affichage--

                $query->closeCursor();
                ?>
            </div>
        </div>
        <!-- //version pc et grand écran -->
    </div>
</div>
<!-- //afficher quelques produits sur la page d'accueil -->

<p><br><br></p>

<!-- products categories -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>CATEGORIES</h1>
            <hr>
        </div>
    </div>
</div>
<div class="container-fluid products-categories">
    <div class="row">
        <div class="col-md-4 content">
            <div class="cat-laitiers">
                <div class="categorie">
                    <h1>Produits</h1>
                    <h1>Laitiers</h1>
                </div>
            </div>
        </div>
        <div class="col-md-4 content">
            <div class="cat-legumes">
                <div class="categorie">
                    <h1>Produits</h1>
                    <h1>Légumes</h1>
                </div>
            </div>
        </div>
        <div class="col-md-4 content">
            <div class="cat-argumes">
                <div class="categorie">
                    <h1>Produits</h1>
                    <h1>Argûmes</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- products categories// -->

<p><br><br></p>