<?php
// this file does an action on user cart in data base

include_once('../../includes/db.php'); //data base connexion

if(session_status() == PHP_SESSION_NONE){ //check if sessions already started or not
    session_start(); //session has not started yet, start
}
if (isset($_SESSION['us_id'])) $userId = $_SESSION['us_id'];
else $userId = 0;

$id = (int) (isset($_POST['id']))?(htmlspecialchars($_POST['id'])):''; //product id
$action = (isset($_POST['action']))?(htmlspecialchars($_POST['action'])):'';

switch ($action){
    case 'qteMoins': //reduce quantity
        $query=$db->prepare('UPDATE carts SET ca_quantity=ca_quantity-1 WHERE pr_id=:id AND us_id=:us AND ca_quantity>1'); //we update only if quantity is greater than 1
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':us', $userId, PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();
    break;

    case 'qtePlus': //augmenter la quantity
        $query=$db->prepare('UPDATE carts SET ca_quantity=ca_quantity+1 WHERE pr_id=:id AND us_id=:us');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':us', $userId, PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();
    break;
    
    case 'remove': //remove product from cart
        $query=$db->prepare('DELETE FROM carts WHERE pr_id=:id AND us_id=:us');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':us', $userId, PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();
        
        $query=$db->prepare('SELECT COUNT(*) FROM carts WHERE us_id=:us');
        $query->bindValue(':us', $userId, PDO::PARAM_INT);
        $query->execute();
        $nbr=$query->fetchcolumn();
        echo "cartCounter_".$nbr; //cartCounter_ helps js/main.js (saveCartInDataBase()) to know that it must update cart counter in index.php
        $query->closeCursor();
    break;

    case 'addToCart':
        if ($userId > 0){ //only do this is user is connected
            $quantity = (int) (isset($_POST['quantity']))?(htmlspecialchars($_POST['quantity'])):'';

            $query=$db->prepare('SELECT ca_quantity FROM carts WHERE pr_id=:id'); //get product quantity in carts (if exist)
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $oldQuantity=$query->fetchcolumn();
            $query->closeCursor();

            $delete=$db->prepare('DELETE FROM carts WHERE us_id=:us AND pr_id=:id'); //remove the product id cart if exist before adding the new one
            $delete->bindValue(':us', $userId, PDO::PARAM_INT);
            $delete->bindValue(':id', $id, PDO::PARAM_INT);
            $delete->execute();
            $delete->closeCursor();
            
            $query=$db->prepare('INSERT INTO carts(us_id, pr_id, ca_quantity) VALUES(:us, :id, :qte)');
            $query->bindValue(':us', $userId, PDO::PARAM_INT);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':qte', $quantity+$oldQuantity, PDO::PARAM_INT);
            $query->execute();
            $query->closeCursor();

            $query=$db->prepare('SELECT COUNT(*) FROM carts WHERE us_id=:us');
            $query->bindValue(':us', $userId, PDO::PARAM_INT);
            $query->execute();
            $nbr=$query->fetchcolumn();
            echo "cartCounter_".$nbr; //cartCounter_ helps js/main.js (saveCartInDataBase()) to know that it must update cart counter in index.php
            $query->closeCursor();
        }
    break;

    case 'getCart':
        $cart = $db->prepare('SELECT pr_id, ca_quantity FROM carts WHERE us_id=:us ORDER BY ca_id DESC');
        $cart->bindValue(':us', $userId, PDO::PARAM_INT);
        $cart->execute();
        
        $cart_products = array();

        while ($elt = $cart->fetch()){
            $object = new stdClass();
            $object->id = $elt['pr_id'];
            $object->quantity = $elt['pr_id'];
            array_push($cart_products, $object);
        }

        echo json_encode($cart_products);

        $cart->closeCursor();
    break;
}
?>