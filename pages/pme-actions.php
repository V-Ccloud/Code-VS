<!-- this file performs pme actions like adding new product ... -->

<?php
if ($pme_id <= 0){ //chasser le visiteur si la pme ne l'appartient pas
    ?><script>window.location.href="?q=pme";</script><?php
}

$action = (isset($_GET['action']))?(htmlspecialchars($_GET['action'])):'';
$pme = (int) (isset($_GET['pme']))?(htmlspecialchars($_GET['pme'])):'0'; //id of the pme account

switch ($action){
    case 'deco': //deconnexion
        session_destroy();
        ?><script>window.location.href="?q=home";</script><?php
    break;

    case 'editInfos':
        $user=$db->prepare('SELECT * FROM pme WHERE pme_id=:id LIMIT 1');
        $user->bindValue(':id', $pme, PDO::PARAM_INT);
        $user->execute();
        $user=$user->fetch();
        ?>
        <div class="container">
            <center>
                <p><br></p>
                <h3><span class="fa fa-user"></span> Editer les informations de la pme</h3>
                <hr>
                <form method="post" action="?q=pme-actions&action=saveInfos&pme=<?php echo $pme ?>" class="row">
                    <div class="form-group col-md-6" style="text-align:left">
                        <label>Nom PME<span style="color:red">*</span></label>
                        <input type="text" name="nom" class="form-control" minlength="2" required="" value="<?php echo $user['pme_nom'] ?>">
                    </div>
                    <div class="form-group col-md-6" style="text-align:left">
                        <label>Email PME<span style="color:red">*</span></label>
                        <input type="email" name="email" class="form-control" minlength="10" required="" value="<?php echo $user['pme_email'] ?>">
                    </div>
                    <div class="form-group col-md-6" style="text-align:left">
                        <label>Téléphone PME<span style="color:red">*</span></label>
                        <input type="text" name="phone" class="form-control" minlength="10" required="" value="<?php echo $user['pme_phone'] ?>">
                    </div>
                    <div class="form-group col-md-6" style="text-align:left">
                        <label>Description PME</label>
                        <textarea name="desc" class="form-control" rows="3"><?php echo htmlspecialchars($user['pme_description']) ?></textarea>
                    </div>
                    <div class="form-group col-12" style="text-align:left">
                        <button type="submit" class="btn btn-dark btn-sm"><span class="fa fa-check"></span> Confirmer</button> 
                        <a href="?q=pme"><button type="button" class="btn btn-default btn-sm">Annuler</button></a>
                    </div>
                </form>
            </center>
        </div>
        <?php
    break;
    
    case 'saveInfos':
        $nom = (isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
        $phone = (isset($_POST['phone']))?(htmlspecialchars($_POST['phone'])):'';
        $email = (isset($_POST['email']))?(htmlspecialchars($_POST['email'])):'';
        $desc = (isset($_POST['desc']))?(htmlspecialchars($_POST['desc'])):'';
        $desc = nl2br($desc);
        
        if (strlen($nom)<2 or strlen($email)<10 or strlen($phone)<8){
            error('Informations invalides<br><a href="?q=pme-actions&action=editInfos&pme='.$pme.'">Cliquez ici pour reprendre</a>');
        }else{
            $user=$db->prepare('UPDATE pme SET pme_nom=:n, pme_phone=:ph, pme_email=:m, pme_description=:d WHERE pme_id=:id');
            $user->bindValue(':n', $nom, PDO::PARAM_STR);
            $user->bindValue(':ph', $phone, PDO::PARAM_STR);
            $user->bindValue(':m', $email, PDO::PARAM_STR);
            $user->bindValue(':d', $desc, PDO::PARAM_STR);
            $user->bindValue(':id', $pme, PDO::PARAM_INT);
            $user->execute();
            
            if ($user->rowCount()==0){
                error('Erreur inconnue<br><a href="?q=pme-actions&action=editInfos&pme='.$pme.'">Cliquez ici pour reprendre</a>');
            }else{
                ?><script>window.location.href="?q=pme&pme=<?php echo $pme ?>";</script><?php
            }
        }
    break;
                    
    case 'editMdp':
        ?>
        <div class="container">
            <center>
                <p><br></p>
                <h3><span class="fa fa-key"></span> Editer mon mot de passe</h3>
                <hr>
                <form method="post" action="?q=pme-actions&action=saveMdp&pme=<?php echo $pme ?>" class="row">
                    <div class="form-group col-12" style="text-align:left">
                        <label>Ancien mot de passe</label>
                        <input type="password" name="mdp" class="form-control" required="" placeholder="Votre ancien mot de passe">
                    </div>
                    <div class="form-group col-md-6" style="text-align:left">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="mdp1" minlength="6" class="form-control" id="mdp1" required="" placeholder="Votre ancien mot de passe">
                    </div>
                    <div class="form-group col-md-6" style="text-align:left">
                        <label>Entrez de nouveau</label>
                        <b id="mdpCheck" style="color:red; display:none">Les nouveaux mots de passe ne correspondent pas</b>
                        <input type="password" name="mdp2" minlength="6" class="form-control" id="mdp2" required="" placeholder="Votre ancien mot de passe">
                    </div>
                    <div class="form-group col-12" style="text-align:left">
                        <button type="submit" class="btn btn-dark btn-sm"><span class="fa fa-check"></span> Confirmer</button> 
                        <a href="?q=pme"><button type="button" class="btn btn-default btn-sm">Annuler</button></a>
                    </div>
                </form>
                <script>
                    document.getElementById('mdp2').addEventListener('input', ()=>{
                        const mdp1=document.getElementById('mdp1').value;
                        const mdp2=document.getElementById('mdp2').value;
                        if (mdp1 != mdp2){
                            document.getElementById('mdpCheck').style.display="block";
                        }else{
                            document.getElementById('mdpCheck').style.display="none";
                        }
                    });
                </script>
            </center>
        </div>
        <?php
    break;

    case 'saveMdp':
        $mdp = (isset($_POST['mdp']))?(htmlspecialchars($_POST['mdp'])):'';
        $mdp1 = (isset($_POST['mdp1']))?(htmlspecialchars($_POST['mdp1'])):'';
        $mdp2 = (isset($_POST['mdp2']))?(htmlspecialchars($_POST['mdp2'])):'';
        
        if ($mdp1 != $mdp2 or strlen($mdp1)<6){
            error("Soit les nouveaux mots de passe ne correspondent pas, soit le mot de passe fait moins de 6 caractères<br><a href='?q=pme-actions&action=editMdp'>Cliquez ici pour reprendre</a>");
        }else{
            $myMdp=$db->prepare('SELECT pme_password FROM pme WHERE pme_id='.$pme_id);
            $myMdp->execute();
            $myMdp=$myMdp->fetch();
            
            if (!password_verify($mdp, $myMdp['pme_password'])){
                error("L'ancien mot de passe est invalide<br><a href='?q=pme-actions&action=editMdp&pme=".$pme."'>Cliquez ici pour reprendre</a>");
            }else{
                $myMdp=$db->prepare('UPDATE pme SET pme_password=:mdp WHERE pme_id='.$pme_id);
                $myMdp->bindValue(':mdp', password_hash($mdp1, PASSWORD_DEFAULT), PDO::PARAM_STR);
                $myMdp->execute();
                
                if ($myMdp->rowCount()==0){
                    error("Erreur inconnue<br><a href='?q=pme&action=editMdp&pme=".$pme."'>Cliquez ici pour reprendre</a>");
                }else{
                    ?><script>window.location.href="?q=pme";</script><?php
                }
            }
        }
    break;
                    
    case 'editLogo':
        ?>
        <div class="container">
            <center>
                <p><br></p>
                <h3>Editer mon logo</h3>
                <hr>
                <form method="post" action="?q=pme-actions&action=saveLogo&pme=<?php echo $pme ?>" class="row" enctype="multipart/form-data">
                    <div class="form-group col-12" style="text-align:left">
                        <label>Nouveau logo</label>
                        <input type="file" name="photo" accept="image/*" class="form-control" required="">
                    </div>
                    <div class="form-group col-12" style="text-align:left">
                        <button type="submit" class="btn btn-dark btn-sm"><span class="fa fa-check"></span> Confirmer</button> 
                        <a href="?q=pme"><button type="button" class="btn btn-default btn-sm">Annuler</button></a>
                        <a href="?q=pme-actions&action=delLogo&pme=<?php echo $pme ?>"><button type="button" class="btn btn-danger btn-sm">Supprimer le logo</button></a>
                    </div>
                </form>
            </center>
        </div>
        <?php
    break;

    case 'saveLogo':
        $error = '';
        if ($_FILES['photo']['size'] > 5242880) $error="Image trop lourde, max 5Mo";     //La taille du fichier en octets.
        if ($_FILES['photo']['error'] > 0) $error="Impossible d'uploader votre image";   //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
        $extensions_valides = array( 'jpg' , 'jpeg', 'jfif' , 'png' );
        $extension_upload = strtolower(  substr(  strrchr($_FILES['photo']['name'], '.')  ,1)  );
        if (!in_array($extension_upload,$extensions_valides)) $error="Format image invalide, formats valides : jpg, jpeg, jfif, png";
        
        if ($error == ''){ //no error
            $photo=move_image($_FILES['photo'], 'img/pme'); //voir la fonction dans includes/functions.php

            //----supprimer l'ancienne photo du dossier du site//------
            $oldPhoto=$db->prepare('SELECT pme_logo FROM pme WHERE pme_id='.$pme_id);
            $oldPhoto->execute();
            $oldPhoto=$oldPhoto->fetch();
            if ($oldPhoto['pme_logo'] != 'pme_default.png') unlink('img/pme/'.$oldPhoto['pme_logo']);

            //---save new photo//-----
            $newPhoto=$db->prepare('UPDATE pme SET pme_logo=:logo WHERE pme_id='.$pme_id);
            $newPhoto->bindValue(':logo', $photo, PDO::PARAM_STR);
            $newPhoto->execute();
            $newPhoto->closeCursor();

            ?><script>window.location.href="?q=pme";</script><?php
        }
    break;
    case 'delLogo': //delete logo
            //----supprimer l'ancienne photo du dossier du site//------
            $oldPhoto=$db->prepare('SELECT pme_logo FROM pme WHERE pme_id='.$pme_id);
            $oldPhoto->execute();
            $oldPhoto=$oldPhoto->fetch();
            if ($oldPhoto['pme_logo'] != 'pme_default.png') unlink('img/pme/'.$oldPhoto['pme_logo']);

            //---save new photo//-----
            $newPhoto=$db->prepare('UPDATE pme SET pme_logo=:logo WHERE pme_id='.$pme_id);
            $newPhoto->bindValue(':logo', 'pme_default.png', PDO::PARAM_STR);
            $newPhoto->execute();
            $newPhoto->closeCursor();

            ?><script>window.location.href="?q=pme";</script><?php
    break;
                    
    case 'editBanner':
        ?>
        <div class="container">
            <center>
                <p><br></p>
                <h3>Editer ma photo de couverture</h3>
                <hr>
                <form method="post" action="?q=pme-actions&action=saveBanner&pme=<?php echo $pme ?>" class="row" enctype="multipart/form-data">
                    <div class="form-group col-12" style="text-align:left">
                        <label>Nouvelle couverture</label>
                        <input type="file" name="photo" accept="image/*" class="form-control" required="">
                    </div>
                    <div class="form-group col-12" style="text-align:left">
                        <button type="submit" class="btn btn-dark btn-sm"><span class="fa fa-check"></span> Confirmer</button> 
                        <a href="?q=pme"><button type="button" class="btn btn-default btn-sm">Annuler</button></a>
                        <a href="?q=pme-actions&action=delBanner&pme=<?php echo $pme ?>"><button type="button" class="btn btn-danger btn-sm">Supprimer la couverture</button></a>
                    </div>
                </form>
            </center>
        </div>
        <?php
    break;

    case 'saveBanner':
        $error = '';
        if ($_FILES['photo']['size'] > 10485760) $error="Image trop lourde, max 10Mo";     //La taille du fichier en octets.
        if ($_FILES['photo']['error'] > 0) $error="Impossible d'uploader votre image";   //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
        $extensions_valides = array( 'jpg' , 'jpeg', 'jfif' , 'png' );
        $extension_upload = strtolower(  substr(  strrchr($_FILES['photo']['name'], '.')  ,1)  );
        if (!in_array($extension_upload,$extensions_valides)) $error="Format image invalide, formats valides : jpg, jpeg, jfif, png";
        
        if ($error == ''){ //no error
            $photo=move_image($_FILES['photo'], 'img/pme'); //voir la fonction dans includes/functions.php

            //----supprimer l'ancienne photo du dossier du site//------
            $oldPhoto=$db->prepare('SELECT pme_banner FROM pme WHERE pme_id='.$pme_id);
            $oldPhoto->execute();
            $oldPhoto=$oldPhoto->fetch();
            if ($oldPhoto['pme_banner'] != 'banner_default.png') unlink('img/pme/'.$oldPhoto['pme_banner']);

            //---save new photo//-----
            $newPhoto=$db->prepare('UPDATE pme SET pme_banner=:banner WHERE pme_id='.$pme_id);
            $newPhoto->bindValue(':banner', $photo, PDO::PARAM_STR);
            $newPhoto->execute();
            $newPhoto->closeCursor();

            ?><script>window.location.href="?q=pme";</script><?php
        }
    break;
    case 'delBanner': //delete banner
            //----supprimer l'ancienne photo du dossier du site//------
            $oldPhoto=$db->prepare('SELECT pme_banner FROM pme WHERE pme_id='.$pme_id);
            $oldPhoto->execute();
            $oldPhoto=$oldPhoto->fetch();
            if ($oldPhoto['pme_banner'] != 'banner_default.png') unlink('img/pme/'.$oldPhoto['pme_banner']);

            //---save new photo//-----
            $newPhoto=$db->prepare('UPDATE pme SET pme_banner=:banner WHERE pme_id='.$pme_id);
            $newPhoto->bindValue(':banner', 'banner_default.png', PDO::PARAM_STR);
            $newPhoto->execute();
            $newPhoto->closeCursor();

            ?><script>window.location.href="?q=pme";</script><?php
    break;

    case 'new-product': //add new product
        ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="textInLines">
                        <div class="line"></div>
                        <div class="text"><span class="fa fa-plus-circle"></span> Nouveau produit</div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="col-12">
                    <form method="post" action="?q=pme-actions&action=saveNewProduct" class='row' enctype="multipart/form-data">
                        <div class="form-group col-md-6">
                            <label>Nom produit <b style="color:red">*</b></label>
                            <input type="text" required maxlength="60" class="form-control myInput" name="nom" placeholder="Nom du produit">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Type produit <b style="color:red">*</b></label>
                            <select class="form-control myInput" name="type">
                                <?php
                                foreach ($types_produit as $type_produit){ //$types_produit est dans includes/constants.php
                                    ?><option value="<?php echo $type_produit ?>">Type <?php echo $type_produit ?></option><?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Prix produit (en DH) <b style="color:red">*</b></label>
                            <input type="number" min="1" step="0.01" required class="form-control myInput" name="prix" placeholder="Ex : 49.99">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Photo 1 (principale) <b style="color:red">*</b></label>
                            <input type="file" required class="form-control myInput" name="photo1">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Photo 2</label>
                            <input type="file" class="form-control myInput" name="photo2">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Photo 3</label>
                            <input type="file" class="form-control myInput" name="photo3">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Description du produit <b style="color:red">*</b></label>
                            <textarea class="form-control myInput" required name="desc" rows="4"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <center><button type="submit" class="btn btn-success btn-lg">Terminer</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    break;

    case 'saveNewProduct': //save the new product
        $nom = (isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
        $type = (isset($_POST['type']))?(htmlspecialchars($_POST['type'])):'';
        $prix = (isset($_POST['prix']))?(htmlspecialchars($_POST['prix'])):'';
        $desc = (isset($_POST['desc']))?(htmlspecialchars($_POST['desc'])):'';
        $nom = strip_tags($nom);
        $type = strip_tags($type);
        $desc = strip_tags($desc);
        $desc = nl2br($desc);

        $error = '';

        if (strlen($nom)<1 or strlen($nom)>60) $error="Nom du produit invalide (max 60 caractères). <a href='?q=pme-actions&action=new-product'>Reprendre</a>";
        else if (strlen($type)<1 or strlen($type)>60) $error="Type du produit invalide. Merci de selectionner une valeur. <a href='?q=pme-actions&action=new-product'>Reprendre</a>";
        else if ($prix<1) $error="Prix du produit invalide. (min 1dh, ex: 1). <a href='?q=pme-actions&action=new-product'>Reprendre</a>";
        
        if ($error != '') echo error($error);
        else{
            //-----verify and upload photos------
            $photo1 = '';
            $photo2 = '';
            $photo3 = '';

            for ($i=1; $i<=3; $i++){ //don't remove word erreur bellow, we use it after to check if $photo1, 2 or 3 is valide or not
                if ($_FILES['photo'.$i]['size'] > 10485760) $error="Erreur, image ".$i." trop lourde, max 10Mo. <a href='?q=pme-actions&action=new-product'>Reprendre</a>";     //La taille du fichier en octets.
                if ($_FILES['photo'.$i]['error'] > 0) $error="Erreur, impossible d'uploader l'image ".$i.". <a href='?q=pme-actions&action=new-product'>Reprendre</a>";   //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
                $extensions_valides = array( 'jpg' , 'jpeg', 'jfif' , 'png' );
                $extension_upload = strtolower(  substr(  strrchr($_FILES['photo'.$i]['name'], '.')  ,1)  );
                if (!in_array($extension_upload,$extensions_valides)) $error="Erreur, format image ".$i." invalide, formats valides : jpg, jpeg, jfif, png. <a href='?q=pme-actions&action=new-product'>Reprendre</a>";
                
                if ($error == ''){ //no error
                    if ($i==1) $photo1 = move_image($_FILES['photo1'], 'img/products'); //voir la fonction dans includes/functions.php
                    else if ($i==2) $photo2 = move_image($_FILES['photo2'], 'img/products'); //voir la fonction dans includes/functions.php
                    else if ($i==3) $photo3 = move_image($_FILES['photo3'], 'img/products'); //voir la fonction dans includes/functions.php
                }
                else{
                    if ($i==1) $photo1 = $error;
                    else if ($i==2) $photo2 = $error;
                    else if ($i==3) $photo3 = $error;
                }
                sleep(1); //if you don't sleep, all photos got same name
            }
            //echo $photo1.' - '.$photo2.' - '.$photo3;
            //-----//verify and upload photos------

            //----check if $photo1, 2 or 3 is valide or not//--------
            if (strpos(strtolower($photo1), 'erreur') !== false){ //photo 1 is invalide
                echo error($photo1);
            }
            else{ //at least photo 1 est valide
                //-------save new product infos and photo 1//----------
                $query=$db->prepare('INSERT INTO products(pme_id, pr_title, pr_description, pr_prix, pr_photo_1, pr_type) VALUES(:pme, :title, :desc, :prix, :photo1, :type)');
                $query->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                $query->bindValue(':title', $nom, PDO::PARAM_STR);
                $query->bindValue(':desc', $desc, PDO::PARAM_STR);
                $query->bindValue(':prix', $prix, PDO::PARAM_STR);
                $query->bindValue(':photo1', $photo1, PDO::PARAM_STR);
                $query->bindValue(':type', $type, PDO::PARAM_STR);
                $query->execute();

                $prId=$db->prepare('SELECT pr_id FROM products WHERE pme_id=:pme ORDER BY pr_id DESC LIMIT 1'); //select just added product id
                $prId->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                $prId->execute();
                $prId=$prId->fetch();
                $prId=$prId['pr_id'];

                if ($query->rowCount() == 0) echo error("Erreur inconnue, impossible d'ajouter votre produit. <a href='?q=pme-actions&action=new-product'>Reprendre</a>");
                else{ //---new product is added with photo1
                    $query->closeCursor();
                    //--------check if photo2 is valide//------
                    if (strpos(strtolower($photo2), 'erreur') === false){
                        $query2=$db->prepare('UPDATE products SET pr_photo_2=:p WHERE pme_id=:pme AND pr_id=:pr LIMIT 1'); //we edit last pme added product
                        $query2->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                        $query2->bindValue(':pr', $prId, PDO::PARAM_INT);
                        $query2->bindValue(':p', $photo2, PDO::PARAM_STR);
                        $query2->execute();
                        $query2->closeCursor();
                    }

                    //--------check if photo3 is valide//------
                    if (strpos(strtolower($photo3), 'erreur') === false){
                        $query2=$db->prepare('UPDATE products SET pr_photo_3=:p WHERE pme_id=:pme AND pr_id=:pr LIMIT 1'); //we edit last pme added product
                        $query2->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                        $query2->bindValue(':pr', $prId, PDO::PARAM_INT);
                        $query2->bindValue(':p', $photo3, PDO::PARAM_STR);
                        $query2->execute();
                        $query2->closeCursor();
                    }

                    // echo $photo1.' - '.$photo2.' - '.$photo3;
                    echo success("Votre produit a été ajouté avec succès ! <br> <a href='?q=pme-actions&action=produits'><button class='btn btn-dark'>Voir mes produits</button></a> <a href='?q=pme-actions&action=new-product'><button class='btn btn-dark'>Ajouter un autre produit</button></a>");
                }
            }
        }
    break;

    case 'produits': //voir les produits
        ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="textInLines">
                        <div class="line"></div>
                        <div class="text">Mes Produits</div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="col-12">
                    <center><h5>Choisir un produit ci-dessous pour plus d'option - <a href="?q=pme-actions&action=new-product"><button type="button" class="btn btn-dark btn-sm"><span class="fa fa-plus-circle"></span> Ajouter produit</button></a></h5></center>
                    <?php
                    $query=$db->prepare('SELECT pr_id, pr_title, pr_photo_1 FROM products WHERE pme_id=:pme ORDER BY pr_id DESC');
                    $query->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                    $query->execute();
                    ?>
                    <select class="form-control myInput" onchange="showPmeProduct(event)">
                        <option value="">Choisir un produit</option>
                        <?php
                        while ($data=$query->fetch()){
                            ?><option value="<?php echo $data['pr_id'] ?>"><?php echo $data['pr_title'] ?></option><?php
                        }
                        ?>
                    </select>
                    <hr><hr>

                    <!-- this div is filed in whith selected product details (see function showPmeProduct() in js/main.js)// -->
                    <div id="pmeProductShow"></div>
                    
                </div>
            </div>
        </div>
        <?php
    break;

    case 'saveProduitEdit': //save modifications d'un produit
        $productId = (int) (isset($_POST['productId']))?(htmlspecialchars($_POST['productId'])):'';
        $nom = (isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
        $type = (isset($_POST['type']))?(htmlspecialchars($_POST['type'])):'';
        $prix = (isset($_POST['prix']))?(htmlspecialchars($_POST['prix'])):'';
        $desc = (isset($_POST['desc']))?(htmlspecialchars($_POST['desc'])):'';
        $nom = strip_tags($nom);
        $type = strip_tags($type);
        $desc = strip_tags($desc);
        $desc = nl2br($desc);

        $error = '';

        if (strlen($nom)<1 or strlen($nom)>60) $error="Nom du produit invalide (max 60 caractères). <a href='?q=pme-actions&action=produits'>Reprendre</a>";
        else if (strlen($type)<1 or strlen($type)>60) $error="Type du produit invalide. Merci de selectionner une valeur. <a href='?q=pme-actions&action=produits'>Reprendre</a>";
        else if ($prix<1) $error="Prix du produit invalide. (min 1dh, ex: 1). <a href='?q=pme-actions&action=produits'>Reprendre</a>";
        
        if ($error != '') echo error($error);
        else{
            $finaleMessage = ""; //message final à afficher à la fin
            //-----verify and upload photos------
            $photo1 = '';
            $photo1Error = false;
            $photo2 = '';
            $photo2Error = false;
            $photo3 = '';
            $photo3Error = false;

            for ($i=1; $i<=3; $i++){ //don't remove word erreur bellow, we use it after to check if $photo1, 2 or 3 is valide or not
                $err = 0; //count photo errors
                
                if ($_FILES['photo'.$i]['size'] > 10485760) $err++;     //La taille du fichier en octets.
                if ($_FILES['photo'.$i]['error'] > 0) $err++;   //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
                $extensions_valides = array( 'jpg' , 'jpeg', 'jfif' , 'png' );
                $extension_upload = strtolower(  substr(  strrchr($_FILES['photo'.$i]['name'], '.')  ,1)  );
                if (!in_array($extension_upload,$extensions_valides)) $err++;
                
                if ($err == 0){ //no error
                    if ($i==1) $photo1 = move_image($_FILES['photo'.$i], 'img/products'); //voir la fonction dans includes/functions.php
                    else if ($i==2) $photo2 = move_image($_FILES['photo'.$i], 'img/products'); //voir la fonction dans includes/functions.php
                    else if ($i==3) $photo3 = move_image($_FILES['photo'.$i], 'img/products'); //voir la fonction dans includes/functions.php
                }
                else{
                    if ($i==1){
                        $finaleMessage = $finaleMessage."Image 1 non modifiée<br>";
                        $photo1Error = true; //donc on ne modifiera pas cette photo dans la base de données
                    }
                    else if ($i==2){
                        $finaleMessage = $finaleMessage."Image 2 non modifiée<br>";
                        $photo2Error = true; //donc on ne modifiera pas cette photo dans la base de données
                    }
                    else if ($i==3){
                        $finaleMessage = $finaleMessage."Image 3 non modifiée<br>";
                        $photo3Error = true; //donc on ne modifiera pas cette photo dans la base de données
                    }
                }
            }
            //echo $photo1.' - '.$photo2.' - '.$photo3;
            //-----//verify and upload photos------

            //-------save new product infos (not photos yet)//----------
            $query=$db->prepare('UPDATE products SET pme_id=:pme, pr_title=:title, pr_description=:desc, pr_prix=:prix, pr_type=:type WHERE pr_id=:pr');
            $query->bindValue(':pme', $pme_id, PDO::PARAM_INT);
            $query->bindValue(':title', $nom, PDO::PARAM_STR);
            $query->bindValue(':desc', $desc, PDO::PARAM_STR);
            $query->bindValue(':prix', $prix, PDO::PARAM_STR);
            $query->bindValue(':pr', $productId, PDO::PARAM_INT);
            $query->bindValue(':type', $type, PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() == 0) echo error("Erreur inconnue, impossible de modifier votre produit. <a href='?q=pme-actions&action=produits'>Reprendre</a>");
            else{ //new product is edited
                $query->closeCursor();
                //--------check if photo1 is valide, if so, edit it in data base//------
                if (!$photo1Error){
                    //----delete old photo//------
                    $query2=$db->prepare('SELECT pr_photo_1 FROM products WHERE pr_id=:pr LIMIT 1');
                    $query2->bindValue(':pr', $productId, PDO::PARAM_INT);
                    $query2->execute();
                    $oldPhoto = $query2->fetch();
                    if (strlen($oldPhoto['pr_photo_1']) >= 8) unlink('img/products/'.$oldPhoto['pr_photo_1']);
                    $query2->closeCursor();

                    //---save the new one//------
                    $query2=$db->prepare('UPDATE products SET pr_photo_1=:p WHERE pr_id=:pr LIMIT 1');
                    $query2->bindValue(':pr', $productId, PDO::PARAM_INT);
                    $query2->bindValue(':p', $photo1, PDO::PARAM_STR);
                    $query2->execute();
                    $query2->closeCursor();
                }

                //--------check if photo2 is valide, if so, edit it in data base//------
                if (!$photo2Error){
                    //----delete old photo//------
                    $query2=$db->prepare('SELECT pr_photo_2 FROM products WHERE pr_id=:pr LIMIT 1');
                    $query2->bindValue(':pr', $productId, PDO::PARAM_INT);
                    $query2->execute();
                    $oldPhoto = $query2->fetch();
                    if (strlen($oldPhoto['pr_photo_2']) >= 8) unlink('img/products/'.$oldPhoto['pr_photo_2']);
                    $query2->closeCursor();

                    //---save the new one//------
                    $query2=$db->prepare('UPDATE products SET pr_photo_2=:p WHERE pr_id=:pr LIMIT 1');
                    $query2->bindValue(':pr', $productId, PDO::PARAM_INT);
                    $query2->bindValue(':p', $photo2, PDO::PARAM_STR);
                    $query2->execute();
                    $query2->closeCursor();
                }
                
                //--------check if photo3 is valide, if so, edit it in data base//------
                if (!$photo3Error){
                    //----delete old photo//------
                    $query2=$db->prepare('SELECT pr_photo_3 FROM products WHERE pr_id=:pr LIMIT 1');
                    $query2->bindValue(':pr', $productId, PDO::PARAM_INT);
                    $query2->execute();
                    $oldPhoto = $query2->fetch();
                    if (strlen($oldPhoto['pr_photo_3']) >= 8) unlink('img/products/'.$oldPhoto['pr_photo_3']);
                    $query2->closeCursor();

                    //---save the new one//------
                    $query2=$db->prepare('UPDATE products SET pr_photo_3=:p WHERE pr_id=:pr LIMIT 1');
                    $query2->bindValue(':pr', $productId, PDO::PARAM_INT);
                    $query2->bindValue(':p', $photo3, PDO::PARAM_STR);
                    $query2->execute();
                    $query2->closeCursor();
                }

                $finaleMessage = $finaleMessage."<br>Votre produit a été modifiée avec succès ! <br> <a href='?q=pme-actions&action=produits'><button class='btn btn-dark'>Continuer</button></a>";

                echo success($finaleMessage);
            }
        }
    break;

    case 'deleteProduit': //delete a product
        if ($pme_id <= 0){ //kick out user, it's not his pme
            error("Vous n'avez pas accès à cette page ! <a href='?q=pme-actions&action=produits'>Retour</a>");
        }
        else{ //delete the product
            $productId = (int) (isset($_GET['pr']))?(htmlspecialchars($_GET['pr'])):'';

            $product = $db->prepare('SELECT pr_photo_1, pr_photo_2, pr_photo_3 FROM products WHERE pr_id=:pr LIMIT 1');
            $product->bindValue(':pr', $productId, PDO::PARAM_INT);
            $product->execute();
            $product=$product->fetch();
            
            if (!isset($product['pr_photo_1'])){
                error("Erreur inconnue, peut être que ce produit n'existe pas. <a href='?q=pme-actions&action=produits'>Retour</a>");
            }
            else{
                //---delete photos in website folder//----
                if (strlen($product['pr_photo_1']) >= 8) unlink('img/products/'.$product['pr_photo_1']);
                if (strlen($product['pr_photo_2']) >= 8) unlink('img/products/'.$product['pr_photo_2']);
                if (strlen($product['pr_photo_3']) >= 8) unlink('img/products/'.$product['pr_photo_3']);

                //----delete the product from data base//-----
                $product=$db->prepare('DELETE FROM products WHERE pr_id=:pr LIMIT 1');
                $product->bindValue(':pr', $productId, PDO::PARAM_INT);
                $product->execute();
                $product->closeCursor();

                echo success("Votre produit à été supprimé avec succès. <a href='?q=pme-actions&action=produits'>Retour</a>");
            }
        }
    break;

    case 'commandes': //voir les commandes
        ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="textInLines">
                        <div class="line"></div>
                        <div class="text">Commandes Reçues</div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="col-12">
                    <!-- commande non encore livrées -->
                    <center><h5><b>Non Livrées :</b> Choisir une commande ci-dessous pour plus d'option</h5></center>
                    <?php
                    $query=$db->prepare('SELECT cm_id, cm_commandeId, cm_articles FROM commandes WHERE cm_livree=0 ORDER BY cm_id DESC');
                    $query->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                    $query->execute();
                    ?>
                    <select class="form-control myInput" onchange="showPmeCommandes(event)">
                        <option value="">Choisir une commande</option>
                        <?php
                        $num = 0;
                        while ($data=$query->fetch()){
                            //------checker si la commande contient un article de la pme//--------
                            $articles = explode(';', $data['cm_articles']); //liste des articles (ids) de la commande
                            foreach ($articles as $article){
                                //------checker si l'article appartient à la pme//--------
                                $test=$db->prepare('SELECT COUNT(*) FROM products WHERE pr_id=:pr AND pme_id=:pme');
                                $test->bindValue(':pr', $article, PDO::PARAM_INT);
                                $test->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                                $test->execute();
                                $test=$test->fetchcolumn();

                                if ($test > 0){ //l'article en question appartient à la pme
                                    $num++;
                                    ?><option value="<?php echo $data['cm_id'] ?>"><?php echo "Commande ".$num." : ".$data['cm_commandeId'] ?></option><?php
                                    break; //car on veut juste savoir si la commande contient un article de la pme
                                }
                            }
                        }
                        if ($num == 0){
                            ?><option value="">Aucune commande reçue</option><?php
                        }
                        ?>
                    </select>

                    <!-- //commande non encore livrées -->
                    <hr><hr>
                    <!-- commande déjà livrées -->
                    <center><h5><b>Déjà Livrées :</b> Choisir une commande ci-dessous pour plus d'option</h5></center>
                    <?php
                    $query=$db->prepare('SELECT cm_id, cm_commandeId, cm_articles FROM commandes WHERE cm_livree=1 ORDER BY cm_id DESC');
                    $query->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                    $query->execute();
                    ?>
                    <select class="form-control myInput" onchange="showPmeCommandes(event)">
                        <option value="">Choisir une commande</option>
                        <?php
                        $num = 0;
                        while ($data=$query->fetch()){
                            $articles = explode(';', $data['cm_articles']); //liste des articles (ids) de la commande
                            foreach ($articles as $article){
                                //------checker si la commande contient un article de la pme//--------
                                $test=$db->prepare('SELECT COUNT(*) FROM products WHERE pr_id=:pr AND pme_id=:pme');
                                $test->bindValue(':pr', $article, PDO::PARAM_INT);
                                $test->bindValue(':pme', $pme_id, PDO::PARAM_INT);
                                $test->execute();
                                $test=$test->fetchcolumn();

                                if ($test > 0){ //l'article en question appartient à la pme
                                    $num++;
                                    ?><option value="<?php echo $data['cm_id'] ?>"><?php echo "Commande ".$num." : ".$data['cm_commandeId'] ?></option><?php
                                    break; //car on veut juste savoir si la commande contient un article de la pme
                                }
                            }
                        }
                        if ($num == 0){
                            ?><option value="">Aucune commande livrée</option><?php
                        }
                        ?>
                    </select>
                    <hr><hr>

                    <!-- this div is filed in whith selected product details (see function showPmeProduct() in js/main.js)// -->
                    <div id="pmeCommandeShow" class="row"></div>
                    
                    <!-- //commande déjà livrées -->
                </div>
            </div>
        </div>
        <?php
    break;

    case 'setCl': //set a commande as déjà livrée
        $commandeId = (int) (isset($_GET['pq']))?(htmlspecialchars($_GET['pq'])):''; //commande id
        $query=$db->prepare('UPDATE commandes SET cm_livree=1 WHERE cm_id=:cm');
        $query->bindValue(':cm', $commandeId, PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();
        
        echo success("Commande marquée livrée. <a href='?q=pme-actions&action=commandes'>Retour</a>");
    break;

    case 'setCnl': //set a commande as pas encore livrée
        $commandeId = (int) (isset($_GET['pq']))?(htmlspecialchars($_GET['pq'])):''; //commande id
        $query=$db->prepare('UPDATE commandes SET cm_livree=0 WHERE cm_id=:cm');
        $query->bindValue(':cm', $commandeId, PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();
        
        echo success("Commande marquée comme pas encore livrée. <a href='?q=pme-actions&action=commandes'>Retour</a>");
    break;

    default:
        include('pages/404.html');
    break;
}
?>