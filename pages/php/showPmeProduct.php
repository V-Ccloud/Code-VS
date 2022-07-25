<?php
// this file is responsible of showing pme product details (see in pages/pme-details.php (action==produits))

if(session_status() == PHP_SESSION_NONE){ //check if sessions already started or not
    session_start(); //session has not started yet, start
}
$thePmeId = $_SESSION['pme_id'];

include_once('../../includes/db.php'); //data base connexion
include_once('../../includes/constants.php');

$productId = (int) (isset($_POST['productId']))?(htmlspecialchars($_POST['productId'])):''; //product id

$query=$db->prepare('SELECT * FROM products WHERE pr_id=:pr LIMIT 1');
$query->bindValue(':pr', $productId, PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();
?>

<div class="textInLines">
    <div class="line"></div>
    <div class="text" style="font-size:18px">Produit : <?php echo $data['pr_title'] ?></div>
    <div class="line"></div>
</div>

<form method="post" action="?q=pme-actions&action=saveProduitEdit" enctype="multipart/form-data" class="row">
    <div class="form-group col-md-6">
        <label>Nom produit <b style="color:red">*</b></label>
        <input type="text" required maxlength="60" class="form-control myInput" name="nom" value="<?php echo $data['pr_title'] ?>">
    </div>
    <div class="form-group col-md-6">
        <label>Type produit <b style="color:red">*</b></label>
        <select class="form-control myInput" name="type">
            <option value="<?php echo $data['pr_type'] ?>"><?php echo $data['pr_type'] ?></option>
            <?php
            foreach ($types_produit as $type_produit){ //$types_produit est dans includes/constants.php
                ?><option value="<?php echo $type_produit ?>">Type <?php echo $type_produit ?></option><?php
            }
            ?>
        </select>
    </div>
    <div class="form-group col-md-6">
        <label>Prix produit (en DH) <b style="color:red">*</b></label>
        <input type="number" min="1" step="0.01" required class="form-control myInput" name="prix" value="<?php echo $data['pr_prix'] ?>">
    </div>
    <div class="form-group col-md-6">
        <label>Photo 1 (principale) <b style="color:red">*</b></label>
        <?php if (strlen($data['pr_photo_1']) >= 8){ ?>
            <img src="img/products/<?php echo $data['pr_photo_1'] ?>" style="height:100px">
        <?php } else{ ?>
            | <i>aucune photo</i>
        <?php } ?>
        <input type="file" class="form-control myInput" name="photo1">
    </div>
    <div class="form-group col-md-6">
        <label>Photo 2</label>
        <?php if (strlen($data['pr_photo_2']) >= 8){ ?>
            <img src="img/products/<?php echo $data['pr_photo_2'] ?>" style="height:100px">
        <?php } else{ ?>
            | <i>aucune photo</i>
        <?php } ?>
        <input type="file" class="form-control myInput" name="photo2">
    </div>
    <div class="form-group col-md-6">
        <label>Photo 3</label>
        <?php if (strlen($data['pr_photo_3']) >= 8){ ?>
            <img src="img/products/<?php echo $data['pr_photo_3'] ?>" style="height:100px">
        <?php } else{ ?>
            | <i>aucune photo</i>
        <?php } ?>
        <input type="file" class="form-control myInput" name="photo3">
    </div>
    <div class="form-group col-md-12">
        <label>Description du produit <b style="color:red">*</b></label>
        <textarea class="form-control myInput" required name="desc" rows="4">
            <?php echo strip_tags($data['pr_description']) ?>
        </textarea>
    </div>
    <div class="form-group col-md-12">
        <input type="hidden" name="productId" value="<?php echo $productId ?>">
        <center>
            <button type="submit" class="btn btn-success">Enregistrer modifications</button> 
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#deleteModal">Supprimer</button>
        </center>
    </div>
</form>


<!-- delete product Modal -->
<div class="modal fade" id="deleteModal">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">

      <!-- Modal body -->
      <div class="modal-body">
        <center>
        <h5>Êtes-vous sûre de cela ? La suppression est irréversible !</h5>
        <hr>
        <a href="?q=pme-actions&action=deleteProduit&pr=<?php echo $data['pr_id'] ?>"><button type="button" class="btn btn-danger">Confirmer</button></a> 
        
        <button type="button" class="btn btn-dark" data-dismiss="modal">Oups, annuler</button>
        </center>
      </div>

    </div>
  </div>
</div>