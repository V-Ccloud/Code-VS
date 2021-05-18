<?php
session_start();

include('includes/constants.php');
include('includes/db.php');
include('includes/functions.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $site_name ?></title>
        <meta id="pageDesc" name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style2.css">

        <?php
        // ---user connected or not---
        if (isset($_SESSION['us_id'])){
            $us_id = $_SESSION['us_id'];
        }
        else{
            $us_id = 0;
        }
        // ---//user connected or not---
        ?>
    </head>
    <body>
        <div class="container myHeader">
            <div class="row">
                <div class="col-sm-6 d-none d-md-block left">
                    <a href="?q=home"><img src="img/logo_linkpme.png" class="logoToMoveOnScroll" alt="LOGO"></a>
                </div>
                <div class="col-12 col-sm-6 right">
                    <div>
                        <span class="fa fa-phone"></span> <a href="tel:<?php echo $site_phone ?>"><?php echo $site_phone ?></a>
                    </div>
                    <div>
                        <span class="fa fa-envelope"></span> <a href="mailto:<?php echo $site_email ?>"><?php echo $site_email ?></a>
                    </div>
                    <div>
                        <a href="<?php echo $site_facebook ?>" target="_blank"><span class="fa fa-facebook-square icon"></span></a>
                        <a href="<?php echo $site_instagram ?>" target="_blank"><span class="fa fa-instagram icon"></span></a>
                        <a href="<?php echo $site_twitter ?>" target="_blank"><span class="fa fa-twitter-square icon"></span></a>
                        <a href="<?php echo $site_whatsapp ?>" target="_blank"><span class="fa fa-whatsapp icon"></span></a>
                    </div>
                </div>
            </div>
        </div>
        
        
        <!--navbar-->
        <div class="myNavbar sticky-top">
            <nav class="navbar navbar-expand-md navbar-dark navContent">
                <!---- Brand//---->
                <a class="navbar-brand" href="?q=home"><?php echo $site_name ?></a>
                <!---- Toggler/collapsibe Button //---->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!---- Navbar links// ---->
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                <?php echo $site_name ?>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">A propos de <?php echo $site_name ?></a>
                                <a class="dropdown-item" href="#">Nos produits sont-ils bio ?</a>
                                <div class="dropdown-about-image"></div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                Nos produits
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="?q=produits">Tous nos produits</a>
                                <a class="dropdown-item" href="?q=produits&type=laitier">Laitiers</a>
                                <a class="dropdown-item" href="?q=produits&type=legume">Légumes</a>
                                <a class="dropdown-item" href="?q=produits&type=argume">Argûmes</a>
                                <div class="dropdown-produits-image"></div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Nos partenaires</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <form class="form-inline" action="/action_page.php">
                            <input class="myInput mr-sm-2" type="text" placeholder="Recherchez ici" required>
                            <button class="btn btn-default" type="submit" style="margin-left:-40px"><span class="fa fa-search"></span></button>
                        </form>
                        <li class="nav-item">
                            <?php if ($us_id <= 0){ //user not connected ?>
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#modalConnexion"><span class="fa fa-user-circle icon"></span></a>
                            <?php }else{ ?>
                                <a class="nav-link" href="#"><span class="fa fa-user-circle icon"></span></a>
                            <?php } ?>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?q=panier">
                                <span class="fa fa-shopping-cart icon"></span>
                                <span class="cart-counter">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!--//navbar-->
        

        <?php
        //----page content is displayed here by php based on the value de q----
        $page=(isset($_GET['q']))?(htmlspecialchars($_GET['q'])):'home';

        switch($page){
            case 'home': //home page
                include('pages/home.php');
            break;

            case 'produits': //display all products
                include('pages/produits.php');
            break;

            case 'produit-details': //display details of specific product
                include('pages/produit-details.php');
            break;

            case 'panier': //client cart
                include('pages/panier.php');
            break;

            default: //error 404 page (when a requet isn't trouvée)
                include('pages/404.html');
            break;
        }
        //----//page content is displayed here by php based on the value de q----
        ?>


        <footer class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <h5>Réseaux Sociaux</h5>
                    <hr>
                    <a href="<?php echo $site_facebook ?>" target="_blank"><span class="fa fa-facebook-square icon"></span></a>
                    <a href="<?php echo $site_instagram ?>" target="_blank"><span class="fa fa-instagram icon"></span></a>
                    <a href="<?php echo $site_twitter ?>" target="_blank"><span class="fa fa-twitter-square icon"></span></a>
                    <a href="<?php echo $site_whatsapp ?>" target="_blank"><span class="fa fa-whatsapp icon"></span></a>
                </div>
                <div class="col-md-4">
                    <h5>Nous Contacter</h5>
                    <hr>
                    <p><span class="fa fa-map-marker"></span> <a href="https://www.google.com/maps?q=<?php echo $site_adresse ?>" target="_blank"><?php echo $site_adresse ?></a></p>
                    <p><span class="fa fa-envelope"></span> <a href="mailto:<?php echo $site_email ?>"><?php echo $site_email ?></a></p>
                    <p><span class="fa fa-phone"></span> <a href="tel:<?php echo $site_phone ?>"><?php echo $site_phone ?></a></p>
                </div>
                <div class="col-md-4">
                    <h5>Nos Produits</h5>
                    <hr>
                    <div>
                        <a href="#"><img src="img/laitier.png" class="img-thumbnail img-fluid" style="max-width:60px"> Laitiers</a><br>
                        <a href="#"><img src="img/legumes.png" class="img-thumbnail img-fluid" style="max-width:60px"> Légumes</a><br>
                        <a href="#"><img src="img/Argume.png" class="img-thumbnail img-fluid" style="max-width:60px"> Argûmes</a>
                    </div>
                </div>

                <div class="col-12 copyright">
                    <hr>
                    <center>
                        <i>&copy; Copyrights <?php echo $site_name ?> 2021, tous droits réservés</i>
                    </center>
                </div>
            </div>
        </footer>

        <!-- alert that displays message, it's called by js/main.js -->
        <div id="myAlert" style="z-index:99999"></div>
        <!-- //alert that displays message, it's called by js/main.js -->

        <!-- modal connexion -->
            <!-- The Modal// -->
        <div class="modal fade" id="modalConnexion">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <!-- Modal Header// -->
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="fa fa-user"></span> CONNEXION <b>CLIENT</b></h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body// -->
                    <div class="modal-body row" style="padding:0px">
                        <div class="col-md-5 d-none d-md-block">
                            <div style="background:url('img/legumes.png'); background-size:cover; background-position:center; width:100%; height:100%"></div>
                        </div>
                        <form class="col-md-7" onsubmit="signInClient(event)" style="padding:15px">
                            <div class="form-group col">
                                <label>Email</label>
                                <input type="email" required name="email" placeholder="Adresse email" class="form-control myInput">
                            </div>
                            <div class="form-group col">
                                <label>Mot de passe</label>
                                <input type="password" required name="mdp" placeholder="Mot de passe" class="form-control myInput">
                            </div>
                            <div class="form-group col">
                                <button type="submit" class="btn btn-dark"><span class="fa fa-key"></span> Connexion</button>
                            </div>
                            <div class="form-group col-12">
                                <hr>
                                <a class="nav-link close" href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalInscription" style="color:green; font-size:15px; opacity:1; font-weight:normal">Pas encore de compte ?</a>
                                <a class="nav-link close" href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalConnexionPme" style="color:green; font-size:15px; opacity:1; font-weight:normal">Connexion PME</a>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- //modal connexion -->



        <!-- modal connexion PME -->
            <!-- The Modal// -->
        <div class="modal fade" id="modalConnexionPme">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <!-- Modal Header// -->
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="fa fa-user"></span> CONNEXION <b>PME</b></h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body// -->
                    <div class="modal-body row" style="padding:0px">
                        <div class="col-md-5 d-none d-md-block">
                            <div style="background:url('img/Argume.png'); background-size:cover; background-position:center; width:100%; height:100%"></div>
                        </div>
                        <form class="col-md-7" onsubmit="signInPme(event)" style="padding:15px">
                            <div class="form-group col">
                                <label>Vous êtes une PME ? Connectez-vous à votre espace.</label>
                            </div>
                            <div class="form-group col">
                                <label>Email</label>
                                <input type="email" required name="email" placeholder="Adresse email" class="form-control myInput">
                            </div>
                            <div class="form-group col">
                                <label>Mot de passe</label>
                                <input type="password" required name="mdp" placeholder="Mot de passe" class="form-control myInput">
                            </div>
                            <div class="form-group col">
                                <button type="submit" class="btn btn-dark"><span class="fa fa-key"></span> Connexion</button>
                            </div>
                            <div class="form-group col-12">
                                <hr>
                                <a class="nav-link close" href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalInscription" style="color:green; font-size:15px; opacity:1; font-weight:normal">Pas encore de compte ?</a>
                                <a class="nav-link close" href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalConnexion" style="color:green; font-size:15px; opacity:1; font-weight:normal">Connexion client</a>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- //modal connexion PME -->


        <!-- modal inscription client -->
            <!-- The Modal// -->
        <div class="modal fade" id="modalInscription">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <!-- Modal Header// -->
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="fa fa-user"></span> INSCRIPTION <b>CLIENT</b></h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body// -->
                    <div class="modal-body row" style="padding:0px">
                        <div class="col-md-5 d-none d-md-block">
                            <div style="background:url('img/carousel-laitiers.png'); background-size:cover; background-position:center; width:100%; height:100%"></div>
                        </div>
                        <form class="col-md-7" onsubmit="signUpClient(event)" style="padding:15px">
                            <div class="form-group col">
                                <label>Créez votre compte client.</label>
                            </div>
                            <div class="form-group col">
                                <label>Prénom</label>
                                <input type="text" required name="prenom" placeholder="Votre prénom" class="form-control myInput">
                            </div>
                            <div class="form-group col">
                                <label>Nom</label>
                                <input type="text" required name="nom" placeholder="Votre nom" class="form-control myInput">
                            </div>
                            <div class="form-group col">
                                <label>Email</label>
                                <input type="email" required name="email" placeholder="Adresse email" class="form-control myInput">
                            </div>
                            <div class="form-group col">
                                <label>Mot de passe</label>
                                <input type="password" required name="mdp" placeholder="Mot de passe" class="form-control myInput">
                            </div>
                            <div class="form-group col">
                                <button type="submit" class="btn btn-dark"><span class="fa fa-key"></span> Inscription</button>
                            </div>
                            <div class="form-group col-12">
                                <hr>
                                <a class="nav-link close" href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalConnexionPme" style="color:green; font-size:15px; opacity:1; font-weight:normal">Connexion PME</a>
                                <a class="nav-link close" href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalConnexion" style="color:green; font-size:15px; opacity:1; font-weight:normal">Connexion client</a>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- //modal inscription client -->


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="js/main2.js" defer></script>

        <!-- cart counter update (on page top right) -->
        <script defer>
            let myCart = sessionStorage.getItem('cart');
            if (myCart === null) myCart = [];
            else myCart = JSON.parse(myCart);

            function setCartCounter(){
                if (parseInt(<?php echo json_encode($us_id) ?>) <= 0){ //user is not connected, cart is in session
                    document.querySelector('.cart-counter').textContent = myCart.length;
                }
                else{ //user is connected, so his cart is in data base
                    var formData = {};
                    $.post("pages/php/cartCounter.php", formData).done(function (data) {
                        document.querySelector('.cart-counter').textContent = parseInt(data);
                    });
                }
            }
            setCartCounter();
        </script>
        <!-- //cart counter update (on page top right) -->
    </body>
</html>