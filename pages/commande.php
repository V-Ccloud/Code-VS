this file handle a cart confirmation for user (une commande quoi)

<p><br><br></p>


<script>
    document.querySelector('title').textContent="Ma commande : <?php echo $site_name ?>"; //page title
</script>


<?php
if ($us_id<=0){ //user not connected, quick him out to cart
    ?><script>window.location.href="?q=panier";</script><?php
}else{ //user is connected
    $prix_livraison = 30;

    ?>
    <div class="container-fluid valider-panier myContainer">
        <div class="row">
            <?php
            $step = (isset($_GET['step']))?(htmlspecialchars($_GET['step'])):'';

            switch($step){
                default:
                    $_SESSION['prixLivraison'] = $prix_livraison;
                    ?>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width:33.33%">Livraison</div>
                            <div class="progress-bar bg-secondary" style="width:33.33%">Paiement</div>
                            <div class="progress-bar bg-primary" style="width:33.33%">Finalisation</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="container">
                            <hr>
                            <h2>Mode de livraison</h2>
                            <select id="livraison" class="form-control">
                                <option value="domicile" selected>Livraison chez moi à domicile</option>
                            </select>
                            <hr>
                            <div>
                                <?php
                                $addr=$db->prepare('SELECT us_adresse, us_phone FROM users WHERE us_id='.$us_id);
                                $addr->execute();
                                $addr=$addr->fetch();
                                ?>
                                <form method="post" action="?q=commande&step=addr">
                                    <label><b>Adresse de livraison</b></label>
                                    <textarea class="form-control" required="" name="addr" id="addr" rows="3"><?php echo $addr['us_adresse'] ?></textarea>
                                    <label><b>Téléphone</b></label>
                                    <input type="text" name="phone" required="" id="phone" class="form-control" value="<?php echo $addr['us_phone'] ?>">
                                    <button type="submit" class="btn btn-dark btn-sm">Modifier</button>
                                </form>
                                <p><br></p>
                                <a href="?q=panier"><button class="btn btn-default" style="border-radius:25px">Retour</button></a> 
                                <button class="btn btn-success" onclick="checkAddr()">Suivant</button>
                            </div>
                            
                            <script>
                                sessionStorage.setItem('livraison', 'domicile'); //save delivery mode
                                let linkToGo = "?q=commande&step=paiement";
                                
                                function checkAddr(){ //check adresse de livraison
                                    let addr=document.getElementById('addr');
                                    let phone=document.getElementById('phone');
                                    if (addr.value.length<5 || phone.value.length<8){
                                        alert("Veuillez indiquer une adresse de livraison et un numéro de téléphone svp");
                                    }else{
                                        // console.log(linkToGo)
                                        window.location.href = linkToGo;
                                    }
                                }
                            </script>
                        </div>
                    </div>
                    <?php
                break;
                    
                case 'paiement':
                    ?>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width:33.33%">Livraison</div>
                            <div class="progress-bar bg-secondary progress-bar-striped progress-bar-animated" style="width:33.33%">Paiement</div>
                            <div class="progress-bar bg-primary" style="width:33.33%">Finalisation</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="container">
                            <hr>
                            <h2>Mode de paiement</h2>
                            <select id="livraison" class="form-control">
                                <option value="cash">Paiement à la livraison</option>
                            </select>
                            <p>Seul le paiement à la livraison est disponible pour le moment. Vous souhaitez payer autrement ? Contactez-nous pour en savoir plus : <a href="tel:<?php echo $site_phone ?>"><?php echo $site_phone ?></a></p>
                            <hr>
                            <div>
                                <p><br></p>
                                <a href="?q=commande"><button class="btn btn-default" style="border-radius:25px">Retour</button></a> 
                                <a href="?q=commande&step=fina"><button class="btn btn-success">Suivant</button></a>
                            </div>
                            
                            <script>
                                sessionStorage.setItem('paiement', 'cash'); //save paiement mode
                            </script>
                        </div>
                    </div>
                    <?php
                break;                    

                case 'fina': //finalisation
                    ?>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width:33.33%">Livraison</div>
                            <div class="progress-bar bg-success" style="width:33.33%">Paiement</div>
                            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width:33.33%">Finalisation</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="container">
                            <hr>
                            <h2>Finalisation et confirmation</h2>
                            <hr>
                            <h4>Résumé de ma commande</h4>
                            <hr>
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th>Prix</th>
                                            <th>Quantité</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $panier=$db->prepare('SELECT carts.ca_id, carts.pr_id, products.pr_prix, carts.ca_quantity, products.pr_photo_1, products.pr_title FROM carts INNER JOIN products ON carts.pr_id=products.pr_id WHERE carts.us_id='.$us_id);
                                        $panier->execute();
                                        $prixTotal = 0;
                                        while ($article=$panier->fetch()){
                                            $prixTotal += ((int) $article['pr_prix'])*((int) $article['ca_quantity']);
                                            ?>
                                            <tr>
                                                <td>
                                                    <img src="img/products/<?php echo $article['pr_photo_1'] ?>" alt="<?php echo $article['pr_title'] ?>" style="height:50px"> 
                                                    <?php echo $article['pr_title'] ?>
                                                </td>
                                                <td><h5><b><?php echo $article['pr_prix'] ?> <?php echo $devise ?></b></h5></td>
                                                <td><?php echo $article['ca_quantity'] ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <div class="total">
                                    <div>
                                        <h6><b>LIVRAISON :</b> <?php echo $_SESSION['prixLivraison'] ?> <?php echo $devise ?></h6>
                                        <h5><b>TOTAL</b> : <?php echo $prixTotal + ((int) $_SESSION['prixLivraison']) ?> <?php echo $devise ?> TTC</h5>
                                    </div>
                                </div>
                                
                                <p><br></p>
                                <form method="post" action="?q=commande&step=done">
                                    <input type="hidden" id="livr" name="livr"><!--mode livraison//-->
                                    <input type="hidden" id="paie" name="paie"><!--mode paiement//-->
                                    <input type="hidden" id="montant" name="montant"><!--prix total//-->
                                    <a href="?q=commande&step=paiement"><button type="button" class="btn btn-default" style="border-radius:25px">Retour</button></a> 
                                    <button type="submit" class="btn btn-success" onclick="valider()"><span class="fa fa-check"></span> Terminer</button>
                                </form>
                            </div>
                            
                            <script>
                                document.getElementById('livr').value=sessionStorage.getItem('livraison');
                                document.getElementById('paie').value=sessionStorage.getItem('paiement');
                                
                                function valider(){
                                    window.location.href="?q=commande&step=done";
                                }
                            </script>
                        </div>
                    </div>
                    <?php
                break;
                    
                case 'done': //commande confirmée
                    if (!isset($_GET['mess'])){ //save commande first
                        $modeLivraison = (isset($_POST['livr']))?(htmlspecialchars($_POST['livr'])):'';
                        $modePaiement = (isset($_POST['paie']))?(htmlspecialchars($_POST['paie'])):'';
                        
                        //---calculer le montant//---
                        $panier=$db->prepare('SELECT products.pr_prix, carts.ca_quantity FROM carts INNER JOIN products ON carts.pr_id=products.pr_id WHERE carts.us_id='.$us_id);
                        $panier->execute();
                        $prixTotal = $prix_livraison;
                        while ($article=$panier->fetch()){
                            $prixTotal += ((int) $article['pr_prix'])*((int) $article['ca_quantity']);
                        }
                        if ($prixTotal <= $prix_livraison){ //there is erro, take user to previous step
                            ?><script>window.location.href="?q=commande&step=fina";</script><?php
                        }
                        else{
                            // jose.init.dev@gmail.com
                            $commandeId = "lp-".strtolower(time());
                            $modePaiement="Paiement à la livraison";
                            
                            $adresse=$db->prepare('SELECT us_adresse FROM users WHERE us_id='.$us_id);
                            $adresse->execute();
                            $adresse=$adresse->fetch();
                            $adresse=$adresse['us_adresse'];
                            //echo $adresse;

                            //----create array of commande articles//----
                            $panier=$db->prepare('SELECT pr_id, ca_quantity FROM carts WHERE us_id='.$us_id);
                            $panier->execute();
                            $panier=$panier->fetchall();
                            $articles = "";
                            for ($i=0; $i<sizeof($panier); $i++) $articles = $articles.$panier[$i]['pr_id'].";".$panier[$i]['ca_quantity'].";;";
                            
                            //----save commande//--
                            $query=$db->prepare('INSERT INTO commandes(us_id, cm_commandeId, cm_mode_livraison, cm_mode_paiement, cm_prix_total, cm_adresse_livraison, cm_articles) VALUES(:us, :cmId, :livr, :paie, :total, :addr, :arts)');
                            $query->bindValue(':us', $us_id, PDO::PARAM_INT);
                            $query->bindValue(':cmId', $commandeId, PDO::PARAM_STR);
                            $query->bindValue(':livr', $modeLivraison, PDO::PARAM_STR);
                            $query->bindValue(':paie', $modePaiement, PDO::PARAM_STR);
                            $query->bindValue(':total', $prixTotal, PDO::PARAM_INT);
                            $query->bindValue(':addr', $adresse, PDO::PARAM_STR);
                            $query->bindValue(':arts', $articles, PDO::PARAM_STR);
                            $query->execute();
                            $allGood = $query->rowCount(); //commande saved in data base or not (0 is false)
                            $query->closeCursor();
                            
                            if ($allGood == 0){
                                error("Erreur inconnue. Merci de reprendre à l'étape précédente<br><a href='?q=commande&step=fina'>Cliquez ici pour reprendre à l'étape précédente</a>");
                            }else{ //all ok
                                //--send mail to client, company and pme for nouvelle commande//----
                                $data = file_get_contents('phpmailer/contactSMTP.json');
                                $json = json_decode($data, true);

                                //---client//---
                                $to=$db->prepare('SELECT us_email, us_phone FROM users WHERE us_id='.$us_id);
                                $to->execute();
                                $to=$to->fetch();
                                $phone=$to['us_phone'];
                                $to=$to['us_email'];
                                $cc='';

                                //---pme//---
                                $pmeId = 0; //only for test
                                $to2=$db->prepare('SELECT pme_email FROM pmes WHERE pme_id='.$pmeId);
                                $to2->execute();
                                $to2=$to2->fetch();
                                $to2=$to2['pme_email'];

                                //---company//---
                                $to3=$json['user'];

                                $cci=$to2.';'.$to3; 
                                $obj="Nouvelle commande - ".$commandeId. " - ".$site_name;
                                $mess="<div><p>Nouvelle commande client. Ci-dessous les détails de la commande :<p><hr><p><b>ID commande :</b> ".$commandeId."<br><b>Email client :</b> ".$to."<br><b>Téléphone client :</b> ".$phone."</br></p><table>";
                                $mess = $mess.'<tr style="border:1px solid #ccc; padding:5px"><td style="border:1px solid #ccc; padding:5px">Article</td><td style="border:1px solid #ccc; padding:5px">Prix</td><td style="border:1px solid #ccc; padding:5px">Quantité</td></tr>';
                                $articles = explode(";;", $articles); //articles ids and quantities
                                foreach($articles as $article){
                                    $art=explode(";", $article);
                                    //echo $art[0].' - ';
                                    if (((int) $art[0])>0){
                                        $artName=$db->prepare('SELECT pr_title, pr_prix FROM products WHERE pr_id=:id LIMIT 1');
                                        $artName->bindValue(':id', (int) $art[0], PDO::PARAM_INT);
                                        $artName->execute();
                                        $artName=$artName->fetch();
                                        $mess = $mess.'<tr style="border:1px solid #ccc; padding:5px">';
                                        $mess = $mess.'<td style="border:1px solid #ccc; padding:5px">'.$artName['pr_title'].'</td>';
                                        $mess = $mess.'<td style="border:1px solid #ccc; padding:5px">'.$artName['pr_prix'].' '.$devise.'</td>';
                                        $mess = $mess.'<td style="border:1px solid #ccc; padding:5px">'.$art[1].'</td>';
                                        $mess = $mess.'</tr>';
                                    }
                                }
                                $mess = $mess.'</table><br>PRIX TOTAL : '.$prixTotal.' '.$devise.'<hr><hr><i><b>NB :</b> Ne pas répondre à cet email, il s\'agit d\'un email automatique</i><br><i>Rélation clientelle<br>'.$site_name.' - '.$site_email.'</i></div>';
                                // echo $mess;
                                
                                $a=include('phpmailer/sendMyMail.php');
                                
                                //---delete all this commande articles from data base//------
                                $toDeletes=$db->prepare('SELECT pr_id FROM carts WHERE us_id='.$us_id);
                                $toDeletes->execute();
                                while ($toDelete=$toDeletes->fetch()){
                                    // $deleteProduct=$db->prepare('DELETE FROM products WHERE pr_id='.$toDelete['pr_id']);
                                    // $deleteProduct->execute();
                                    // $deleteProduct->closeCursor();
    
                                    //----remove this product from all carts (for of all users)//----
                                    $deleteProduct=$db->prepare('DELETE FROM carts WHERE pr_id='.$toDelete['pr_id']);
                                    $deleteProduct->execute();
                                    $deleteProduct->closeCursor();
                                }
                                $toDeletes->closeCursor();
                                
                                ?><script>window.location.href="?q=commande&step=done&mess=true&cmId=<?php echo $commandeId ?>"</script><?php
                            }
                        }
                    }
                    else{ //commande saved, show success message
                        ?>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width:33.33%">Livraison</div>
                                <div class="progress-bar bg-success" style="width:33.33%">Paiement</div>
                                <div class="progress-bar bg-success" style="width:33.33%">Finalisation</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="container">
                                <hr>
                                <div style="display:flex; align-items:center; justify-content:center">
                                    <div class="commande-validated">
                                        <div class="fa fa-check"></div>
                                        <p>Commande confirmée avec succès ! Nous entrerons en contact pour la livraison de votre commande bientôt.<br>Merci<br><span style="font-size:16px">ID commande : <?php echo $_GET['cmId'] ?></span></p>
                                    </div>
                                </div>
                                <hr>
                                <center>
                                    <a href="?q=produits"><button class="btn btn-success"><span class="fa fa-shopping-cart"></span> Continuer mes achats</button></a> 
                                    <a href="?q=user"><button class="btn btn-success"><span class="fa fa-user"></span> Aller dans mon compte</button></a>
                                </center>
                            </div>
                        </div>
                        <?php
                    }
                break;
                
                case 'addr': //changer adresse de livraison
                    $addr = (isset($_POST['addr']))?(htmlspecialchars($_POST['addr'])):'';
                    $phone = (isset($_POST['phone']))?(htmlspecialchars($_POST['phone'])):'';
                    $addr=strip_tags($addr);
                    $phone=strip_tags($phone);

                    if (strlen($addr)<5 or strlen($phone)<5){
                        error('Erreur, adresse ou téléphone non valide. Merci de reprendre<br><a href="?q=commande">Cliquez ici pour reprendre</a>');
                    }
                    else{
                        $adresse=$db->prepare('UPDATE users SET us_adresse=:addr, us_phone=:p WHERE us_id=:id');
                        $adresse->bindValue(':addr', $addr, PDO::PARAM_STR);
                        $adresse->bindValue(':p', $phone, PDO::PARAM_STR);
                        $adresse->bindValue(':id', $us_id, PDO::PARAM_INT);
                        $adresse->execute();
                        $adresse->closeCursor();
                        
                        ?><script>window.location.href="?q=commande";</script><?php
                    }
                break;
            }
            ?>
        </div>
    </div>
    <?php
} //end of else (user is connected)
?>