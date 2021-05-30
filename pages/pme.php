<!-- this pme page
here, we detect the user visiting the page with $_SESSION['pme_id']
if it's his pme, we show him some more features like editing and adding products
if not, we just show him pme preview like when you visit someone's facebook page
let's go coding now, lol -->

<?php
$pme = (int) (isset($_GET['pme']))?(htmlspecialchars($_GET['pme'])):'0'; //id of the pme account
if ($pme <= 0){
    $pme = (isset($_SESSION['pme_id']))?($_SESSION['pme_id']):'0';
}

// ----check if pme exists//----
$nbr=$db->prepare('SELECT COUNT(*) FROM pme WHERE pme_id=:id');
$nbr->bindValue(':id', $pme, PDO::PARAM_INT);
$nbr->execute();
$nbr = $nbr->fetchcolumn();

if ($nbr == 0){ //no pme found in data base with id $pme
    include('pages/404.html');
}
else{ //pme exists
    // -----get pme details------
    $query=$db->prepare('SELECT * FROM pme WHERE pme_id=:id LIMIT 1');
    $query->bindValue(':id', $pme, PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    // -----//get pme details------
    ?>

    <!-- change page title and desc in head -->
    <script>
        document.querySelector('title').textContent = "<?php echo $data['pme_nom'] ?>";
        document.querySelector('#pageDesc').textContent = "<?php echo $data['pme_description'] ?>";
    </script>
    <!-- //change page title and desc in head -->


    <div class="pmeHeader">
        <div class="pmeBanner" style="background:url('img/pme/<?php echo $data['pme_banner'] ?>'); background-size:cover; background-position:center">
            <?php if (isset($_SESSION['pme_id'])){ //display this only if the pme is for visitor ?>
                <span class="fa fa-camera"> <a href="?q=pme-actions&action=editBanner&pme=<?php echo $pme ?>">Editer</a></span>
            <?php } ?>
        </div>
        <div class="pmeLogoBlock">
            <div class="pmeLogo" style="background:url('img/pme/<?php echo $data['pme_logo'] ?>'); background-size:cover; background-position:center">
                <?php if (isset($_SESSION['pme_id'])){ //display this only if the pme is for visitor ?>
                    <span class="fa fa-camera"> <a href="?q=pme-actions&action=editLogo&pme=<?php echo $pme ?>">Editer</a></span>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- buttons group -->
    <?php if (isset($_SESSION['pme_id'])){ //display this only if the pme is for visitor ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="textInLines">
                    <div class="line"></div>
                    <div class="text">ACTIONS</div>
                    <div class="line"></div>
                </div>
            </div>
            <div class="col-12">
                <?php
                //---count commands non livrées dont les produits appartiennet to this pme//----
                $commandes=$db->prepare('SELECT cm_articles FROM commandes WHERE cm_livree=0 ORDER BY cm_id DESC');
                $commandes->execute();
                $nbr3=0; //compte les commandes non livrées de la pme
                while ($commande = $commandes->fetch()){
                    //---pour chaque produit de la commande, vérifier s'il appartient à la pme//-----
                    $articles = explode(';', $commande['cm_articles']);
                    foreach ($articles as $art){
                        $test=$db->prepare('SELECT COUNT(*) FROM products WHERE pme_id=:id AND pr_id=:pr');
                        $test->bindValue(':id', $pme, PDO::PARAM_INT);
                        $test->bindValue(':pr', $art, PDO::PARAM_INT);
                        $test->execute();
                        $test = $test->fetchcolumn();
                        
                        if ($test > 0){
                            $nbr3++;
                            break; //si on trouve un article dans la commande, on break, car on compte les commandes non livrées de la pme et non les articles
                        }
                    }
                }

                //---count les produits appartiennet to this pme//----
                $produits=$db->prepare('SELECT COUNT(*) FROM products WHERE pme_id=:id');
                $produits->bindValue(':id', $pme, PDO::PARAM_INT);
                $produits->execute();
                $nbr2=$produits->fetchcolumn();
                ?>
                <center>
                    <div class="btn-group btn-group-lg">
                        <button type="button" class="btn btn-dark" onclick="action('new-product')">Ajouter produit</button>
                        <button type="button" class="btn btn-warning" onclick="action('produits')"><span class="badge badge-success"><?php echo $nbr2 ?></span> Voir les produits</button>
                        <button type="button" class="btn btn-dark" onclick="action('commandes')"><span class="badge badge-success"><?php echo $nbr3 ?></span> Voir les commandes</button>
                        <button type="button" class="btn btn-warning" onclick="action('deco')">Se déconnecter</button>
                    </div>
                </center>
                <script>
                    function action(action){
                        window.location.href = "?q=pme-actions&action=" + action + "&pme=<?php echo $pme ?>";
                    }
                </script>
                <hr>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- //buttons group -->

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-secondary">
                    <span class="fa fa-building"></span> <?php echo $data['pme_nom'] ?><br>
                    <span class="fa fa-envelope"></span> <?php echo $data['pme_email'] ?><br>
                    <span class="fa fa-phone"></span> <?php echo $data['pme_phone'] ?><br>
                    <?php if (isset($_SESSION['pme_id'])){ //display this only if the pme is for visitor ?>
                        <span class="fa fa-pencil"></span> <a href="?q=pme-actions&action=editInfos&pme=<?php echo $pme ?>">Modifier les infos</a><br>
                        <span class="fa fa-key"></span> <a href="?q=pme-actions&action=editMdp&pme=<?php echo $pme ?>">Modifier le mot de passe</a>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-secondary">
                    <span class="fa fa-text"></span> <?php echo $data['pme_description'] ?><br>
                    <?php if (isset($_SESSION['pme_id'])){ //display this only if the pme is for visitor ?>
                        <span class="fa fa-pencil"></span> <a href="?q=pme-actions&action=editInfos&pme=<?php echo $pme ?>">Modifier</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- les dernier produits de la pme -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="textInLines">
                    <div class="line"></div>
                    <div class="text">NOS DERNIERS PRODUITS</div>
                    <div class="line"></div>
                </div>
            </div>
            <?php
            $query=$db->prepare('SELECT * FROM products WHERE pme_id=:id ORDER BY pr_id DESC LIMIT 12');
            $query->bindValue(':id', $data['pme_id'], PDO::PARAM_STR);
            $query->execute();
            
            while ($data=$query->fetch()){
                ?><div class="col-6 col-md-3"><?php
                display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                ?></div><?php
            }
            ?>
            <div class="col-12">
                <p><br><br></p>
                <center><a href="?q=produits&type=pme&pme=<?php echo $pme ?>"><button type="button" class="btn btn-dark">VOIR TOUS NOS PRODUITS</button></a></center>
            </div>
        </div>
    </div>
    <!-- //les dernier produits de la pme -->

    <?php
} //end of else (pme exisits)
?>