

<li class="nav-item dropdown dropleft">
    <?php if ($us_id <= 0 and $pme_id <= 0){ //user not connected ?>
        <a class="nav-link" href="#" data-toggle="dropdown">
        <span class="btn">Telecharger</span>
       </a>
   <div class="dropdown-menu">
        <span class="dropdown-item-text">Connexion</span>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalConnexion">
            <button type="button" class="btn btn-dark btn-sm btn-block">Connexion client </button>
        </a>
                                   
        <span class="dropdown-item-text">Inscription</span>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalInscription">
            <button type="button" class="btn btn-dark btn-sm btn-block">Inscription client </button>
        </a>
                                   
     </div>
   <?php }else if ($pme_id > 0){ ?>
      <a class="nav-link" href="?q=pme"><span class="fa fa-user-circle icon"></span></a>
     <?php }else{ ?>
     <a class="nav-link" href="?q=user"><span class="fa fa-user-circle icon"></span></a>
     <?php } ?>
</li>
                    