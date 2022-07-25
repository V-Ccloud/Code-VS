<!--this is contact page//--->

<script>
    document.querySelector('title').textContent="Contactez-nous : <?php echo $site_name ?>"; //page title
</script>


<div class="container myContainer">
    <div class="row contact">
        <div class="col-12">
            <center>
            <h2>INFOS DE CONTACT</h2>
            </center>
            <hr>
        </div>
        <div class="col-6">
            <div class="alert alert-primary">
                <a href="tel:<?php echo $site_phone ?>"><span class="fa fa-phone-square"></span> <?php echo $site_phone ?></a><br><br>
                <a href="mailto:<?php echo $site_email ?>"><span class="fa fa-envelope-square"></span> <?php echo $site_email ?></a><br><br>
                <a href="https://api.whatsapp.com/send?phone=2250798696853"><span class="fa fa-whatsapp"></span> +225 0798696853</a>
            </div>
        </div>
        <div class="col-6">
            <div class="alert alert-primary">
                <a href="<?php echo $site_instagram ?>" target="_blank"><span class="fa fa-instagram"></span> Instagram</a><br><br>
                <a href="<?php echo $site_facebook ?>" target="_blank"><span class="fa fa-facebook-square"></span> Facebook</a><br><br>
                <a href="<?php echo $site_linkedin ?>" target="_blank"><span class="fa fa-linkedin-square"></span> LinkedIN </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 contactForm">
            <hr>
            <h2>Ecrivez-nous</h2>
            <?php
            if (isset($_GET['sub'])){ //submit
                $nom = (isset($_POST['nom']))?(htmlspecialchars($_POST['nom'])):'';
                $email = (isset($_POST['email']))?(htmlspecialchars($_POST['email'])):'';
                $objet = (isset($_POST['objet']))?(htmlspecialchars($_POST['objet'])):'';
                $phone = (isset($_POST['phone']))?(htmlspecialchars($_POST['phone'])):'';
                $mess = (isset($_POST['mess']))?(htmlspecialchars($_POST['mess'])):'';
                $nom = strip_tags($nom);
                $email = strip_tags($email);
                $objet = strip_tags($objet);
                $phone = strip_tags($phone);
                $mess = strip_tags($mess);
                $mess = nl2br($mess);
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    error("Adresse email invalide, merci de reprendre");
                }
                else if(strlen($nom)<2 or strlen($objet)<5 or strlen($phone)<8 or strlen($mess)<10){
                    error("Informtions invalides, merci de reprendre");
                }
                else{
                    //send the email
                    //echo $nom.' - '.$email.' - '.$objet.' - '.$phone.' - '.$mess;
                    $data = file_get_contents('phpmailer/contactSMTP.json');
                    $json = json_decode($data, true);

                    $to=$json['user'];
                    //$to="jose.init.dev@gmail.com"; //only for test
                    $obj=$objet;
                    $mess=$mess.'Prise de contact client.<br><br><b>Infos client :</b><br>Nom : '.$nom.'<br>Email : '.$email.'<br>Téléphone : '.$phone.'<br>Message :<br>'.$mess;
                    $cc='';
                    $cci='';
                    //echo $mess;
                    
                    $a=include('phpmailer/sendMyMail.php');
                    //echo $a;
                    if ($a){
                        //echo "ddddddd";
                        success('Message envoyé avec succes !');
                    }
                    else{
                        //echo "fff";
                        error('Erreur, message non envoyé. Merci de reprendre svp.');
                    }
                }
            }
            ?>
            <form method="post" action="?q=contact&sub=1" class="row">
                <div class="form-group col-md-6">
                    <input type="text" name="nom" placeholder="Nom*" required="" class="form-control myInput">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="email" placeholder="Email*" required="" class="form-control myInput">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="objet" placeholder="Objet de la demande*" required="" class="form-control myInput">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="phone" placeholder="Téléphone*" required="" class="form-control myInput">
                </div>
                <div class="form-group col-md-12">
                    <textarea name="mess" placeholder="Dévrivez votre requette ici*..." required="" class="form-control myInput" rows="5"></textarea>
                </div>
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary">ENVOYER</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p><br><br></p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.167796739469!2d-5.239779184748738!3d6.8704879209370535!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfb8915269b16761%3A0x1079e2768f5ed55d!2sVS-CLOTHS!5e0!3m2!1sfr!2sma!4v1629375386444!5m2!1sfr!2sma" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d106376.7269234759!2d-7.657032750943337!3d33.57226777566251!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd4778aa113b%3A0xb06c1d84f310fd3!2sCasablanca%2C%20Morocco!5e0!3m2!1sen!2snl!4v1621442372013!5m2!1sen!2snl" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
        </div>
    </div>
</div>