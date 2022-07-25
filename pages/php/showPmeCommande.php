<?php
// this file is responsible of showing pme commande details (see in pages/pme-details.php (action==commandes))

if(session_status() == PHP_SESSION_NONE){ //check if sessions already started or not
    session_start(); //session has not started yet, start
}
$thePmeId = $_SESSION['pme_id'];

include_once('../../includes/db.php'); //data base connexion
include_once('../../includes/constants.php');

$commandeId = (int) (isset($_POST['commandeId']))?(htmlspecialchars($_POST['commandeId'])):''; //commande id

//-----select commande with id==$commandeId//------
$query=$db->prepare('SELECT cm_livree, cm_articles, cm_date, cm_adresse_livraison, cm_prix_livraison, cm_mode_paiement, cm_mode_livraison, cm_commandeId, us_nom, us_prenom, us_email, us_adresse, us_phone, us_ville FROM commandes INNER JOIN users ON commandes.us_id=users.us_id WHERE commandes.cm_id=:cm LIMIT 1');
$query->bindValue(':cm', $commandeId, PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();

$articles = explode(';', $data['cm_articles']); //les ids produits de la commande en question
$commandeTitleShown = false; //certaines choses ne doivent pas s'afficher plusieurs fois avec la boucle
$prix_commande = 0; //calculte prix total de la commande (prix pour la pme et non prix total)

//-----choisir tous les produits de cette commande appartenant à la pme----------
foreach ($articles as $article){
    //------checker si l'article appartient à la pme//--------
    $test=$db->prepare('SELECT COUNT(*) FROM products WHERE pr_id=:pr AND pme_id=:pme');
    $test->bindValue(':pr', $article, PDO::PARAM_INT);
    $test->bindValue(':pme', $thePmeId, PDO::PARAM_INT);
    $test->execute();
    $test=$test->fetchcolumn();

    if ($test > 0){ //l'article en question appartient à la pme
        $produit=$db->prepare('SELECT pr_title, pr_prix, pr_photo_1 FROM products WHERE pr_id=:pr AND pme_id=:pme LIMIT 1');
        $produit->bindValue(':pr', $article, PDO::PARAM_INT);
        $produit->bindValue(':pme', $thePmeId, PDO::PARAM_INT);
        $produit->execute();
        $produit=$produit->fetch();

        if (!$commandeTitleShown){ //section affichée une seule fois
            $commandeTitleShown = true;
            ?>
            <div class="col-md-12">
                <div class="textInLines">
                    <div class="line"></div>
                    <div class="text" style="font-size:18px">Commande numéro : <?php echo $data['cm_commandeId'] ?></div>
                    <div class="line"></div>
                </div>
                <center>
                    <?php if ($data['cm_livree']){ ?>
                        <h1><span class="badge badge-success" style="text-transform:uppercase">Déjà Livrée</span></h1>
                    <?php } else{ ?>
                        <h1><span class="badge badge-danger" style="text-transform:uppercase">Non Livrée</span></h1>
                    <?php } ?>
                </center>
            </div>
            <div class="col-md-6" style="padding:4px">
                <div class="alert alert-secondary">
                    <b style="text-decoration:underline">INFOS CLIENT :</b><br>
                    <p><b>Prénom Nom :</b> <?php echo $data['us_prenom'].' '.$data['us_nom'] ?></p>
                    <p><b>Email :</b> <?php echo $data['us_email'] ?></p>
                    <p><b>Téléphone :</b> <?php echo $data['us_phone'] ?></p>
                    <p><b>Adresse :</b> <?php echo $data['us_adresse'] ?></p>
                    <p><b>Ville :</b> <?php echo $data['us_ville'] ?></p>
                </div>
            </div>
            <div class="col-md-6" style="padding:4px">
                <div class="alert alert-secondary">
                    <b style="text-decoration:underline">INFOS COMMANDE :</b><br>
                    <p><b>Date commande :</b> <?php echo $data['cm_date'] ?></p>
                    <p><b>Adresse livraison commande :</b> <?php echo $data['cm_adresse_livraison'] ?></p>
                    <p><b>Mode de livraison :</b> <?php echo $data['cm_mode_livraison'] ?></p>
                    <p><b>Mode de paiement :</b> <?php echo $data['cm_mode_paiement'] ?></p>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="col-6 col-md-3" style="padding:4px">
            <div class="alert alert-secondary">
                <img src="img/products/<?php echo $produit['pr_photo_1'] ?>" class="img-fluid img-thumbnail"><br>
                <p><?php echo $produit['pr_title'] ?></p>
                <h5><?php echo $produit['pr_prix'].' '.$devise ?></h5>
                <?php $prix_commande += $produit['pr_prix']; ?>
            </div>
        </div>
        <?php
    }
}
//-----//choisir tous les produits de cette commande appartenant à la pme----------
?>
<div class="col-12" style="padding:4px">
    <div class="alert alert-secondary">
        <b style="text-decoration:underline">PRIX COMMANDE :</b><br>
        <h4><b>PRIX TOTAL : <?php echo $prix_commande.' '.$devise ?></b></h4>
    </div>
</div>

<div class="form-group col-md-12">
    <center>
        <?php if ($data['cm_livree']){ ?>
            <a href="?q=pme-actions&action=setCnl&pq=<?php echo $commandeId ?>&ids=5z8dz8"><button type="button" class="btn btn-success">Marquer non livrée</button></a>
        <?php } else{ ?>
            <a href="?q=pme-actions&action=setCl&pq=<?php echo $commandeId ?>&id=12s8z8"><button type="button" class="btn btn-danger">Marquer comme déjà livrée</button></a>
        <?php } ?>
    </center>
</div>