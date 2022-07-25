<!-- this is the one that shows le panier -->
<p><br><br></p>


<script>
    document.querySelector('title').textContent="Mon panier : <?php echo $site_name ?>"; //page title
</script>


<div class="container">
    <div class="row">
        <?php
        if ($us_id <= 0){ //user n'est pas connecté (son panier est dans session)
            ?>
            <script>
                //---retrieve user cart from session//----
                let panier = sessionStorage.getItem('cart');
                if (panier == null) panier=[];
                else panier = JSON.parse(panier);
            </script>
            <?php
        }
        else{ //user est connecté (son panier n'est pas dans session mais dans la base de donnée)
            $cart = $db->prepare('SELECT pr_id, ca_quantity FROM carts WHERE us_id=:us ORDER BY ca_id DESC');
            $cart->bindValue(':us', $us_id, PDO::PARAM_INT);
            $cart->execute();
            
            $cart_products = array();

            while ($elt = $cart->fetch()){
                $object = new stdClass();
                $object->id = $elt['pr_id'];
                $object->quantity = $elt['ca_quantity'];
                array_push($cart_products, $object);
            }

            $cart->closeCursor();
            ?>
            <script>
                //---retrieve user cart in session//----
                let panier = <?php echo json_encode($cart_products); ?>;
            </script>
            <?php
        }
        // session_destroy();
        ?>

        <!-- cart is displayed here avec ajax -->
        <div class="col-12">
            <center>
                <h3>MON PANIER</h3>
            </center>
        </div>
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Détails</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cart-display">
                    <!--content here by ajax bellow//--->
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <hr>
            <a href="?q=produits"><button class="btn btn-dark">+<span class="fa fa-shopping-cart"></span> Comment Commander</button></a> 
            <?php if ($us_id <= 0){ //user not connected ?>
                <a href="#" data-toggle="modal" data-target="#modalConnexion" id="validerPanier"><button class="btn btn-dark"><span class="fa fa-check"></span><span class="fa fa-shopping-cart"></span> Valider mon panier</button></a>
            <?php }else{ ?>
                <a href="?q=commande" id="validerPanier"><button class="btn btn-dark"><span class="fa fa-check"></span><span class="fa fa-shopping-cart"></span> Valider mon panier</button></a>
            <?php } ?>
        </div>
        <!-- //cart is displayed here avec ajax -->
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    if (panier.length <= 0){
        document.getElementById('validerPanier').style.display="none";
    }
    // console.log(cart)

    function displayCartElts(){
        document.getElementById('cart-display').innerHTML = "";
        
        if (parseInt('<?php echo $us_id ?>') > 0){ //user is connected, we will get cart content via pages/php/cart-display.php
            getContent = 'yes';
        }
        else getContent = 'no';
        
        for (j=0; j<panier.length; j++){ //for each elt of cart, display it
            var formData = {
                id: panier[j].id,
                quantity: panier[j].quantity,
                getContent: getContent
            };
            $.post("pages/php/cart-display.php", formData).done(function (data) {
                document.getElementById('cart-display').innerHTML += data;
            });
        }
    }
    displayCartElts();

    function actionOnCart(action, id=0){ //do some action on a card
        document.getElementById('cart-display').innerHTML = '<div class="spinner-border text-success"></div>'; //loader
        const userId = parseInt('<?php echo $us_id ?>');

        if (userId <= 0){ //user not connected (his cart is in session)
            if (action == 'qteMoins'){ //reduire la quantité
                for (k=0; k<panier.length; k++){
                    if (parseInt(panier[k].id) == parseInt(id)){
                        if (parseInt(panier[k].quantity) > 1){ //reduce quantity only if it's greater than 1
                            panier[k].quantity = parseInt(panier[k].quantity)-1;
                            sessionStorage.setItem('cart', JSON.stringify(panier));
                            displayCartElts();
                        }
                        break;
                    }
                }
            }
            else if (action == 'qtePlus'){ //augmenter la quantité
                for (k=0; k<panier.length; k++){
                    if (parseInt(panier[k].id) == parseInt(id)){
                        panier[k].quantity = parseInt(panier[k].quantity)+1;
                        sessionStorage.setItem('cart', JSON.stringify(panier));
                        displayCartElts();
                        break;
                    }
                }
            }
            else if (action == 'remove'){ //retirer du panier
                for (k=0; k<panier.length; k++){
                    if (parseInt(panier[k].id) == parseInt(id)){
                        panier.splice(k, 1);
                        sessionStorage.setItem('cart', JSON.stringify(panier));
                        displayCartElts();
                        break;
                    }
                }
            }
        }
        else{ //user is connected (his cart is in data base)
            var formData = {
                id: id,
                action: action
            };
            $.post("pages/php/action-on-cart.php", formData).done(function (data) {
                document.getElementById('cart-display').innerHTML = ""; //effacer l'affichage du panier
                if (data.substring(0, 11) == "cartCounter"){
                    document.querySelector('.cart-counter').textContent = parseInt(data.substring(12));
                    myAlert("Panier mis à jour");
                }
                
                //---to not reload the page, we just update here panier and display again it content//---
                if (action == 'qteMoins'){ //reduire la quantité
                    for (k=0; k<panier.length; k++){
                        if (parseInt(panier[k].id) == parseInt(id)){
                            if (parseInt(panier[k].quantity) > 1){ //reduce quantity only if it's greater than 1
                                panier[k].quantity = parseInt(panier[k].quantity)-1;
                            }
                            break;
                        }
                    }
                }
                else if (action == 'qtePlus'){ //augmenter la quantité
                    for (k=0; k<panier.length; k++){
                        if (parseInt(panier[k].id) == parseInt(id)){
                            panier[k].quantity = parseInt(panier[k].quantity)+1;
                            break;
                        }
                    }
                }
                else if (action == 'remove'){ //retirer du panier
                    for (k=0; k<panier.length; k++){
                        if (parseInt(panier[k].id) == parseInt(id)){
                            panier.splice(k, 1);
                            break;
                        }
                    }
                }
                
                displayCartElts(); //afficher à nouveau
            });
        }
    }

    function getDataBaseCart(){
        var formData = {
            action: 'getCart'
        };
        $.post("pages/php/action-on-cart.php", formData).done(function (data) {
            //---save the cart in js session//----
            sessionStorage.setItem('cart', JSON.stringify(data));
        });
    }
</script>