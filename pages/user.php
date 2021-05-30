<!--this is user account//--->

<script>
    document.querySelector('title').textContent="Mon compte : <?php echo $site_name ?>"; //page title
</script>

<div class="container-fluid myContainer">
    <div class="row contact">
        <div class="col-12">
            <center>
                <p><span class="fa fa-user-o"></span> A partir d'ici, vous pouvez modifier vos informations, voir votre panier et vos commandes.</p>
                <?php if (isset($_GET['mdpEdited'])) success("Mot de passe édité avec succès"); ?>
                <?php if (isset($_GET['infosEdited'])) success("Informations éditées avec succès"); ?>
            </center>
        </div>
        <?php
        if ($us_id<=0){ //user not connected
            ?><script>window.location.href="?q=home";</script><?php
        }
        else{ //user connected
            $action = (isset($_GET['act']))?(htmlspecialchars($_GET['act'])):'';
            switch($action){
                default: //user account
                    ?>
                    <div class="col-12">
                        <div id="accordion" class="row">
                          <div class="card col-md-6">
                            <div class="card-header">
                              <a class="card-link" data-toggle="collapse" href="#collapse1">
                                <span class="fa fa-user"></span> Mes informations | <a href="?q=user&act=deco">Déconnexion</a>
                              </a>
                            </div>
                            <div id="collapse1" class="collapse" data-parent="#accordion">
                              <div class="card-body">
                                <?php
                                $user=$db->prepare('SELECT * FROM users WHERE us_id='.$us_id.' LIMIT 1');
                                $user->execute();
                                $user=$user->fetch();
                                ?>
                                  <p><b>PRENOM : </b><?php echo $user['us_prenom'] ?></p>
                                  <p><b>NOM : </b><?php echo $user['us_nom'] ?></p>
                                  <p><b>EMAIL : </b><?php echo $user['us_email'] ?></p>
                                  <p><b>ADRESSE : </b><?php echo $user['us_adresse'] ?></p>
                                  <p><b>TELEPHONE : </b><?php echo $user['us_phone'] ?></p>
                                  <a href="?q=user&act=editInfos"><button class="btn btn-dark btn-sm"><span class="fa fa-pencil"></span> Editer mes informations</button></a>
                              </div>
                            </div>
                          </div>

                          <div class="card col-md-6">
                            <div class="card-header">
                              <a class="collapsed card-link" data-toggle="collapse" href="#collapse2">
                                <span class="fa fa-key"></span> Mon mot de passe
                              </a>
                            </div>
                            <div id="collapse2" class="collapse" data-parent="#accordion">
                              <div class="card-body">
                                <a href="?q=user&act=editMdp"><button class="btn btn-dark btn-sm"><span class="fa fa-pencil"></span> Editer mon mot de passe</button></a>
                              </div>
                            </div>
                          </div>

                          <div class="card col-md-6">
                            <div class="card-header">
                              <a class="collapsed card-link" data-toggle="collapse" href="#collapse3">
                                <span class="fa fa-shopping-cart"></span> Mon panier
                              </a>
                            </div>
                            <div id="collapse3" class="collapse" data-parent="#accordion">
                              <div class="card-body">
                                <a href="?q=panier"><button class="btn btn-dark btn-sm"><span class="fa fa-eye"></span> Voir mon panier</button></a>
                              </div>
                            </div>
                          </div>

                          <div class="card col-md-6">
                            <div class="card-header">
                              <a class="collapsed card-link" data-toggle="collapse" href="#collapse4">
                                <span class="fa fa-shopping-bag"></span> Mes commandes
                              </a>
                            </div>
                            <div id="collapse4" class="collapse" data-parent="#accordion">
                              <div class="card-body row">
                                <div id="accordion2">
                                    <?php
                                    $commandes=$db->prepare('SELECT * FROM commandes WHERE us_id='.$us_id.' ORDER BY cm_id DESC LIMIT 60');
                                    $commandes->execute();
                                    $nbr=1;
                                    while ($commande=$commandes->fetch()){
                                        ?>
                                        <div class="card">
                                          <div class="card-header">
                                              <a class="collapsed card-link" data-toggle="collapse" href="<?php echo '#commande'.$nbr ?>">
                                              Commande <?php echo $nbr ?>
                                              </a>
                                          </div>
                                          <div id="<?php echo 'commande'.$nbr ?>" class="collapse" data-parent="#accordion2">
                                              <div class="card-body">
                                                <p><b>ID commande : </b><?php echo $commande['cm_commandeId'] ?></p>
                                                <p><b>Mode de livraison : </b><?php echo $commande['cm_mode_livraison'] ?></p>
                                                <p><b>Mode de paiement : </b><?php echo $commande['cm_mode_paiement'] ?></p>
                                                <p><b>Prix total : </b><?php echo $commande['cm_prix_total'].' '.$devise ?></p>
                                                <p><b>Adresse livraison : </b><?php echo $commande['cm_adresse_livraison'] ?></p>
                                                <hr>
                                                <div class="user-commande">
                                                    <?php
                                                    $arts=explode(';;', $commande['cm_articles']); //format $commande['cm_articles]: id;qte;;id;qte;; ...
                                                    foreach ($arts as $art){
                                                        if (isset($art[0])){
                                                            if (((int) $art[0])>0){
                                                                $art = explode(';', $art);
                                                                $article=$db->prepare('SELECT pr_photo_1, pr_title FROM products WHERE pr_id='.$art[0]);
                                                                $article->execute();
                                                                $article=$article->fetch();
                                                                echo "<div class='user-commande-art'><img src='img/products/".$article['pr_photo_1']."'> ".$article['pr_title']."<br>Qte : ".$art[1]."</div>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                              </div>
                                          </div>
                                        </div>
                                        <?php
                                        $nbr++;
                                    }
                                    ?>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                    </div>
                    <?php
                break;
                    
                case 'editMdp':
                    ?>
                    <div class="container">
                        <center>
                            <h3><span class="fa fa-key"></span> Editer mon mot de passe</h3>
                            <hr>
                            <form method="post" action="?q=user&act=editMdpSub" class="row">
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
                                    <a href="?q=user"><button type="button" class="btn btn-default btn-sm">Annuler</button></a>
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
                case 'editMdpSub':
                    $mdp = (isset($_POST['mdp']))?(htmlspecialchars($_POST['mdp'])):'';
                    $mdp1 = (isset($_POST['mdp1']))?(htmlspecialchars($_POST['mdp1'])):'';
                    $mdp2 = (isset($_POST['mdp2']))?(htmlspecialchars($_POST['mdp2'])):'';
                    
                    if ($mdp1 != $mdp2 or strlen($mdp1)<6){
                        error("Soit les nouveaux mots de passe ne correspondent pas, soit le mot de passe fait moins de 6 caractères<br><a href='?q=user&act=editMdp'>Cliquez ici pour reprendre</a>");
                    }else{
                        $myMdp=$db->prepare('SELECT us_password FROM users WHERE us_id='.$us_id);
                        $myMdp->execute();
                        $myMdp=$myMdp->fetch();
                        
                        if (!password_verify($mdp, $myMdp['us_password'])){
                            error("L'ancien mot de passe est invalide<br><a href='?q=user&act=editMdp'>Cliquez ici pour reprendre</a>");
                        }else{
                            $myMdp=$db->prepare('UPDATE users SET us_password=:mdp WHERE us_id='.$us_id);
                            $myMdp->bindValue(':mdp', password_hash($mdp1, PASSWORD_DEFAULT), PDO::PARAM_STR);
                            $myMdp->execute();
                            
                            if ($myMdp->rowCount()==0){
                                error("Erreur inconnue<br><a href='?q=user&act=editMdp'>Cliquez ici pour reprendre</a>");
                            }else{
                                ?><script>window.location.href="?q=user&mdpEdited=1";</script><?php
                            }
                        }
                    }
                break;
                    
                case 'editInfos':
                    $user=$db->prepare('SELECT * FROM users WHERE us_id='.$us_id.' LIMIT 1');
                    $user->execute();
                    $user=$user->fetch();
                    ?>
                    <div class="container">
                        <center>
                            <h3><span class="fa fa-user"></span> Editer mes informations</h3>
                            <hr>
                            <form method="post" action="?q=user&act=editInfosSub" class="row">
                                <div class="form-group col-md-6" style="text-align:left">
                                    <label>Prénom<span style="color:red">*</span></label>
                                    <input type="text" name="prenom" class="form-control" minlength="2" required="" value="<?php echo $user['us_prenom'] ?>">
                                </div>
                                <div class="form-group col-md-6" style="text-align:left">
                                    <label>Nom<span style="color:red">*</span></label>
                                    <input type="text" name="nom" class="form-control" minlength="2" required="" value="<?php echo $user['us_nom'] ?>">
                                </div>
                                <div class="form-group col-md-6" style="text-align:left">
                                    <label>Email<span style="color:red">*</span></label>
                                    <input type="email" name="email" class="form-control" minlength="10" required="" value="<?php echo $user['us_email'] ?>">
                                </div>
                                <div class="form-group col-md-6" style="text-align:left">
                                    <label>Adresse <i>(Est aussi votre adresse de livraison)</i></label>
                                    <input type="text" name="addr" class="form-control" value="<?php echo $user['us_adresse'] ?>">
                                </div>
                                <div class="form-group col-md-6" style="text-align:left">
                                    <label>Téléphone</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo $user['us_phone'] ?>">
                                </div>
                                <div class="form-group col-12" style="text-align:left">
                                    <button type="submit" class="btn btn-dark btn-sm"><span class="fa fa-check"></span> Confirmer</button> 
                                    <a href="?q=user"><button type="button" class="btn btn-default btn-sm">Annuler</button></a>
                                </div>
                            </form>
                        </center>
                    </div>
                    <?php
                break;
                case 'editInfosSub':
                    $nom = (isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
                    $prenom = (isset($_POST['prenom']))?(htmlspecialchars($_POST['prenom'])):'';
                    $email = (isset($_POST['email']))?(htmlspecialchars($_POST['email'])):'';
                    $addr = (isset($_POST['addr']))?(htmlspecialchars($_POST['addr'])):'';
                    $phone = (isset($_POST['phone']))?(htmlspecialchars($_POST['phone'])):'';
                    
                    if (strlen($nom)<2 or strlen($email)<10){
                        error('Informations invalides<br><a href="?q=user&act=editInfos">Cliquez ici pour reprendre</a>');
                    }else{
                        $user=$db->prepare('UPDATE users SET us_nom=:n, us_prenom=:pr, us_email=:m, us_adresse=:ad, us_phone=:p WHERE us_id='.$us_id);
                        $user->bindValue(':n', $nom, PDO::PARAM_STR);
                        $user->bindValue(':pr', $prenom, PDO::PARAM_STR);
                        $user->bindValue(':m', $email, PDO::PARAM_STR);
                        $user->bindValue(':ad', $addr, PDO::PARAM_STR);
                        $user->bindValue(':p', $phone, PDO::PARAM_STR);
                        $user->execute();
                        
                        if ($user->rowCount()==0){
                            error('Erreur inconnue<br><a href="?q=user&act=editInfos">Cliquez ici pour reprendre</a>');
                        }else{
                            ?><script>window.location.href="?q=user&infosEdited=1";</script><?php
                        }
                    }
                break;
                    
                case 'deco': //deconnexion
                    session_destroy();
                    ?><script>window.location.href="?q=home";</script><?php
                break;
            }
        } //end else user est connected
        ?>
    </div>
</div>