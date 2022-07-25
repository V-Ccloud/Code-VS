<?php
// this is main function of the website


// --this function displays an error--
function error($text){
    echo '<div class="alert alert-warning"><center>'.$text.'</center></div>';
}
// --//this function displays an error--

// --this function displays a success massage--
function success($text){
    echo '<div class="alert alert-success"><center>'.$text.'</center></div>';
}
// --//this function displays a success massage--

// --this function displays a product--
function display_product($id, $title, $photo, $prix){
    include('constants.php');
    $usId = (int) (isset($_SESSION['us_id']))?($_SESSION['us_id']):'0';
    ?>
    <div class="product-display" style="background:#e5e5e5; border-radius:15px; overflow:hidden">
        <a href="?q=produit-details&id=<?php echo $id ?>">
            <div class="photo d-none d-md-block" style="background:url('img/products/<?php echo $photo ?>'); background-size:cover; background-position:center; height:220px; margin-bottom:7px"></div>
            <div class="photo d-md-none" style="background:url('img/products/<?php echo $photo ?>'); background-size:cover; background-position:center; height:150px; margin-bottom:7px"></div>
            
            <div class="infos" style="padding:0px">
                <center>
                    <h5 class="d-none d-md-block"><?php echo $title ?></h5>
                    <h6 class="d-md-none"><?php echo $title ?></h6>
                    <h2 class="d-none d-md-block"><?php echo $prix ?><span style="font-size:16px"><?php echo $devise ?></h2>
                    <h4 class="d-md-none"><?php echo $prix ?><span style="font-size:16px"><?php echo $devise ?></h4>
                </center>
            </div>
        </a>
        <div class="infos" style="padding-bottom:15px">
            <center>
                <a class="btn btn-primary" href="?q=commentcommander">Comment Commander ?</a>
                </center>
        </div>
    </div>
    <p></p>
    <?php
}
// --//this function displays a product--


//--------function qui upload une image dans le dossier $dossier du site---------
function move_image($photo, $dossier){
	$extension_upload=strtolower(substr(  strrchr($photo['name'], '.')  ,1));
	$name=time();
	$nomphoto=str_replace('','',$name).".".$extension_upload;
	$name=$dossier."/".str_replace('','',$name).".".$extension_upload;
	move_uploaded_file($photo['tmp_name'],$name);
	return $nomphoto; //retourner le nom de la photo (à sauvegarder dans la base de données)
}
//--------//function qui upload une image dans le dossier $dossier du site---------
?>