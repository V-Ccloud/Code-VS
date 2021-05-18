<?php
// this files displays cart products
//we give the product id and quantity in cart, then this file retrieves the infos of the product if data base and displays it

include_once('../../includes/db.php'); //data base connexion

$id = (int) (isset($_POST['id']))?(htmlspecialchars($_POST['id'])):''; //product id
$quantity = (int) (isset($_POST['quantity']))?(htmlspecialchars($_POST['quantity'])):'';

$query=$db->prepare('SELECT pr_photo_1, pr_title FROM products WHERE pr_id=:id LIMIT 1');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();

if (isset($data['pr_title'])){ //if we got data
    ?>
    <tr>
        <td style="max-width:200px">
            <?php echo $data['pr_title'] ?><br>
            <img src="img/products/<?php echo $data['pr_photo_1'] ?>" class='img-thumbail img-fluid' style='max-width:60px'>
        </td>
        <td>
           <a href="?q=produit-details&id=<?php echo $id ?>"><button type="button" class="btn btn-success btn-sm">Détails</button></a>
        </td>
        <td>
            <?php if ($quantity > 1){ ?>
                <button type="button" onclick="actionOnCart('qteMoins', '<?php echo $id ?>')" class="btn btn-default btn-sm"><</button>
            <?php } ?>
            <?php echo $quantity ?>
            <button type="button" onclick="actionOnCart('qtePlus', '<?php echo $id ?>')" class="btn btn-default btn-sm">></button>
        </td>
        <td>
            <button type="button" onclick="actionOnCart('remove', '<?php echo $id ?>')" class="btn btn-danger btn-sm">Retirer</button>
        </td>
    </tr>
    <?php
}
?>