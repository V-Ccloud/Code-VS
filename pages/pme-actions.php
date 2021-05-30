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
                    <form method="post" action="?q=pme-actions&ation=saveNewProduct" class='row' enctype="multipart/formdata">
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
                            <textarea class="form-control myInput" required name="photo3" rows="4"></textarea>
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
        $prix = (int) (isset($_POST['prix']))?(htmlspecialchars($_POST['prix'])):'';
        $desc = (isset($_POST['desc']))?(htmlspecialchars($_POST['desc'])):'';
        $nom = strip_tags($nom);
        $type = strip_tags($type);
        $desc = strip_tags($desc);
        $desc = nl2br($desc);

        $error = '';

        if (strlen($nom)<1 or strlen($nom)>60) $error="Nom du produit invalide (max 60 caractères)";
        else if (strlen($type)<1 or strlen($type)>60) $error="Type du produit invalide. Merci de selectionner une valeur";
        else if ($prix<1) $error="Prix du produit invalide. (min 1dh, ex: 1)";
        
        if ($error != '') echo $error;
        else{
            //-----verify and upload photos------
            $photo1 = '';
            $photo2 = '';
            $photo3 = '';

            for ($i=1; $i<=3; $i++){
                if ($_FILES['photo'.$i]['size'] > 10485760) $error="Image ".$i." trop lourde, max 10Mo";     //La taille du fichier en octets.
                if ($_FILES['photo'.$i]['error'] > 0) $error="Impossible d'uploader l'image ".$i;   //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
                $extensions_valides = array( 'jpg' , 'jpeg', 'jfif' , 'png' );
                $extension_upload = strtolower(  substr(  strrchr($_FILES['photo']['name'], '.')  ,1)  );
                if (!in_array($extension_upload,$extensions_valides)) $error="Format image ".$i." invalide, formats valides : jpg, jpeg, jfif, png";
                
                if ($error == ''){ //no error
                    $photo.$i = move_image($_FILES['photo'], 'img/products'); //voir la fonction dans includes/functions.php
          
            //-----//verify and upload photos------
        }
    break;
}
?>