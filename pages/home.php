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
    <div class="carousel-item active myCarouselItem" style="background:url('img/carousel.JPG'); background-size:cover; background-position:center">
        <div class="content">
            <div class="row">
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
               
            </div>
        </div>
    </div>
    <div class="carousel-item myCarouselItem" style="background:url('img/carousel-chemise.png'); background-size:cover; background-position:center">
        <div class="content">
            <div class="row">
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
                <div class="d-none d-sm-block col-dm-8 col-md-4 block">
                    <div class="chemise"></div>
                    <h1>Costumes</h1>
                    <hr>
                    <h5>Avec nous, le costume est un confort corporel pour vous rendre plus attentif et focus.</h5>
                    <hr>
                    <center>
                        <a href="?q=apropos"><button class="btn btn-primary myBtn">Découvrez <?php echo $site_name ?></button></a> 
                        <a href="?q=produits"><button class="btn btn-primary myBtn">Découvrez nos Tenues</button></a>
                    </center>
                </div>
                <div class="d-none d-sm-block col-sm-2 col-md-4"></div>
            </div>
        </div>
    </div>
    <div class="carousel-item myCarouselItem" style="background:url('img/carousel-pantal.JPG'); background-size:cover; background-position:center">
        <div class="content">
            <div class="row">
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
        <div class="col-3 promotion-bg2">
            <span class="fa fa-map-marker" title="Points relais de livraison"></span>
            <span class="d-none d-md-block">Points relais de retrait</span>
        </div>
        <div class="col-3 promotion-bg1">
            <span class="fa fa-certificate icon" title="Qualité de confection"></span>
            <span class="d-none d-md-block">Qualité de confection</span>
        </div>
        <div class="col-3 promotion-bg2">
            <span class="fa fa-money icon" title="Mode de paiement"></span>
            <span class="d-none d-md-block">Mode de paiement</span>
        </div>

        <div class="col-3 promotion-bg1">
                <span class="fa fa-question-circle" style="" title="Comment Commander"></span>
                
                <span class="d-none d-md-block">Comment Commander ?</span>
        </div>
        

    </div>
</div>
<!-- promotions// -->

<!-- afficher quelques produits sur la page d'accueil -->
<div class="container myContainer">
    <div class="row">
        <div class="col-12">
            <div class="textInLines">
                <div class="line"></div>
                <div class="text" style="font-size:30px">NOS KITS</div>
                <div class="line"></div>
            </div>
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
            <div class="row">
                <div class="col-12">
                    <hr>
                    <center>
                        <a href="?q=produits"><button type="button" class="btn btn-dark">VOIR TOUS NOS KITS</button></a>
                    </center>
                </div>
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
            <div class="row">
                <div class="col-12">
                    <hr>
                    <center>
                        <a href="?q=produits"><button type="button" class="btn btn-dark btn-sm">VOIR TOUS NOS KITS</button></a>
                    </center>
                </div>
            </div>
        </div>
        <!-- //version pc et grand écran -->
    </div>
</div>
<!-- //afficher quelques produits sur la page d'accueil -->

<p><br><br></p>

<!-- products categories -->
<div class="container">
    <!-- <div class="row">
        <div class="col-12">
            <div class="textInLines">
                <div class="line"></div>
                <div class="text" style="font-size:30px">CATEGORIES</div>
                <div class="line"></div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid products-categories">
    <div class="row">
        <div class="col-md-4 content">
            <a href="?q=produits&type=laitier">
            <div class="cat-laitiers">
                <div class="categorie">
                    <h1>KIT</h1>
                    <h1>COSTUME</h1>
                </div>
            </div>
            </a>
        </div>
        <div class="col-md-4 content">
            <a href="?q=produits&type=legume">
            <div class="cat-legumes">
                <div class="categorie">
                    <h1>CHEMISES</h1>
                </div>
            </div>
            </a>
        </div>
        <div class="col-md-4 content">
            <a href="?q=produits&type=argume">
            <div class="cat-argumes">
                <div class="categorie">
                    <h1>PANTALON</h1> -->
                </div>
            </div>
            </a>
        </div>
    </div>
</div>
<!-- products categories// -->

<p><br><br></p>