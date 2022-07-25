<!-- this is product file -->


<script>
    document.querySelector('title').textContent="Nos produits : <?php echo $site_name ?>"; //page title
</script>


<div class="container-fluid myContainer">
    <div class="row">
        <div class="col-12">
            <select id="filterSelect" onchange="filterProducts(event, '<?php echo '?'.$_SERVER['QUERY_STRING'] ?>')" style="border-radius:5px">
                <option value="none">Ne pas filtrer</option>
                <option value="recent">Tri du plen récent au plus ancien</option>
                <option value="croissant">Tri par prix croissant</option>
                <option value="decroissant">Tri par prix décroissant</option>
                <?php
                foreach ($types_produit as $type_produit){ //$types_produit est dans includes/constants.php
                    ?><option value="<?php echo $type_produit ?>">Afficher que le type <?php echo $type_produit ?></option><?php
                }
                ?>
            <select>
            <hr>
        </div>
    </div>
    <div class="row">
        <?php
        // --page (for pagination)//--
        $page = (int) (isset($_GET['page']))?(htmlspecialchars($_GET['page'])):'1'; //pagination
        $type = (isset($_GET['type']))?(htmlspecialchars($_GET['type'])):'all'; //type de produit
        $filter = (isset($_GET['filter']))?(htmlspecialchars($_GET['filter'])):'none';
        $search = (isset($_GET['search']))?(htmlspecialchars($_GET['search'])):'none'; //permet de savoir (ci-dessous) que le user a fait une recherche ou pas
        $pme = (isset($_GET['pme']))?(htmlspecialchars($_GET['pme'])):''; //pme id (if user wanna see only products for a specific pme)

        if ($filter != "none"){ //set current filter value
            ?>
            <script>
                document.getElementById('filterSelect').value = "<?php echo $filter ?>";
            </script>
            <?php
        }
        else if ($type != "all" and !isset($_GET['pme'])){ //set current filter value
            ?>
            <script>
                document.getElementById('filterSelect').value = "<?php echo $type ?>";
            </script>
            <?php
        }

        $max=16; //display $max produits par page
        $min=0; //display à partir de min

        if ($page <= 1) $min = 0;
        else $min = ($page-1) * $max;
        
        switch($type){
            default: //all products
                ?>
                <div class="col-12">
                    <div class="row">
                        <?php
                        //--select all product depuis la base de données--
                        if ($search == 'none'){ //pas de recherche
                            if ($filter == "recent"){
                                $query=$db->prepare('SELECT * FROM products ORDER BY pr_id DESC LIMIT :min, :max');
                                $query->bindValue(':min', $min, PDO::PARAM_INT);
                                $query->bindValue(':max', $max, PDO::PARAM_INT);
                                $query->execute();
                            }
                            else if ($filter == "croissant"){
                                $query=$db->prepare('SELECT * FROM products ORDER BY pr_prix ASC LIMIT :min, :max');
                                $query->bindValue(':min', $min, PDO::PARAM_INT);
                                $query->bindValue(':max', $max, PDO::PARAM_INT);
                                $query->execute();
                            }
                            else if ($filter == "decroissant"){
                                $query=$db->prepare('SELECT * FROM products ORDER BY pr_prix DESC LIMIT :min, :max');
                                $query->bindValue(':min', $min, PDO::PARAM_INT);
                                $query->bindValue(':max', $max, PDO::PARAM_INT);
                                $query->execute();
                            }
                            else{ //no filter
                                $query=$db->prepare('SELECT * FROM products ORDER BY pr_id DESC LIMIT :min, :max');
                                $query->bindValue(':min', $min, PDO::PARAM_INT);
                                $query->bindValue(':max', $max, PDO::PARAM_INT);
                                $query->execute();
                            }
                            //--//select all product depuis la base de données--

                            //----affichage--
                            while ($data=$query->fetch()){
                                ?><div class="col-6 col-md-3"><?php
                                display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                                ?></div><?php
                            }
                            //----//affichage--

                            $query->closeCursor();
                        }
                        else{ //c'est une recherche
                            $searchQuery = (isset($_POST['searchQuery']))?(htmlspecialchars($_POST['searchQuery'])):'';

                            echo "<div class='col-12'><h6><b>RECHERCHE :</b> ".$searchQuery."</h6></div>";

                            if ($filter == "recent"){
                                $query=$db->prepare('SELECT * FROM products WHERE pr_title LIKE :searchQ OR pr_description LIKE :searchQ ORDER BY pr_id DESC LIMIT :min, :max');
                                $query->bindValue(':min', $min, PDO::PARAM_INT);
                                $query->bindValue(':max', $max, PDO::PARAM_INT);
                                $query->bindValue(':searchQ', '%'.$searchQuery.'%', PDO::PARAM_STR);
                                $query->execute();
                            }
                            else if ($filter == "croissant"){
                                $query=$db->prepare('SELECT * FROM products WHERE pr_title LIKE :searchQ OR pr_description LIKE :searchQ ORDER BY pr_prix ASC LIMIT :min, :max');
                                $query->bindValue(':min', $min, PDO::PARAM_INT);
                                $query->bindValue(':max', $max, PDO::PARAM_INT);
                                $query->bindValue(':searchQ', '%'.$searchQuery.'%', PDO::PARAM_STR);
                                $query->execute();
                            }
                            else if ($filter == "decroissant"){
                                $query=$db->prepare('SELECT * FROM products WHERE pr_title LIKE :searchQ OR pr_description LIKE :searchQ ORDER BY pr_prix DESC LIMIT :min, :max');
                                $query->bindValue(':min', $min, PDO::PARAM_INT);
                                $query->bindValue(':max', $max, PDO::PARAM_INT);
                                $query->bindValue(':searchQ', '%'.$searchQuery.'%', PDO::PARAM_STR);
                                $query->execute();
                            }
                            else{ //no filter
                                $query=$db->prepare('SELECT * FROM products WHERE pr_title LIKE :searchQ OR pr_description LIKE :searchQ ORDER BY pr_id DESC LIMIT :min, :max');
                                $query->bindValue(':min', $min, PDO::PARAM_INT);
                                $query->bindValue(':max', $max, PDO::PARAM_INT);
                                $query->bindValue(':searchQ', '%'.$searchQuery.'%', PDO::PARAM_STR);
                                $query->execute();
                            }
                            //--//select all product depuis la base de données--

                            //----affichage--
                            while ($data=$query->fetch()){
                                ?><div class="col-6 col-md-3"><?php
                                display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                                ?></div><?php
                            }
                            //----//affichage--

                            $query->closeCursor();
                        }
                        ?>
                    </div>
                </div>
                <?php
            break;

            case 'Kit Complet':
                ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h6><b>CATEGORIE :</b> Kit Complet</h6>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        //--select laitiers depuis la base de données--
                        if ($filter == "recent"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_id DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else if ($filter == "croissant"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_prix ASC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else if ($filter == "decroissant"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_prix DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else{ //no filter
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_id DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        //--//select laitiers depuis la base de données--

                        //----affichage--
                        while ($data=$query->fetch()){
                            ?><div class="col-6 col-md-3"><?php
                            display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                            ?></div><?php
                        }
                        //----//affichage--

                        $query->closeCursor();
                        ?>
                    </div>
                </div>
                <?php
            break;

            case 'Kit Moyen':
                ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h6><b>CATEGORIE :</b> Kit Moyen</h6>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        //--select laitiers depuis la base de données--
                        if ($filter == "recent"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_id DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else if ($filter == "croissant"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_prix ASC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else if ($filter == "decroissant"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_prix DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else{ //no filter
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_id DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        //--//select laitiers depuis la base de données--

                        //----affichage--
                        while ($data=$query->fetch()){
                            ?><div class="col-6 col-md-3"><?php
                            display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                            ?></div><?php
                        }
                        //----//affichage--

                        $query->closeCursor();
                        ?>
                    </div>
                </div>
                <?php
            break;

            case 'Kit Simple':
                ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h6><b>CATEGORIE :</b> Kit Simple</h6>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        //--select Kit Simple depuis la base de données--
                        if ($filter == "recent"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_id DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else if ($filter == "croissant"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_prix ASC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else if ($filter == "decroissant"){
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_prix DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        else{ //no filter
                            $query=$db->prepare('SELECT * FROM products WHERE pr_type=:type ORDER BY pr_id DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':type', $type, PDO::PARAM_STR);
                            $query->execute();
                        }
                        //--//select Kit Simple depuis la base de données--

                        //----affichage--
                        while ($data=$query->fetch()){
                            ?><div class="col-6 col-md-3"><?php
                            display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                            ?></div><?php
                        }
                        //----//affichage--

                        $query->closeCursor();
                        ?>
                    </div>
                </div>
                <?php
            break;

            case 'pme': //display products for a specific pme
                $pmeInfos=$db->prepare('SELECT pme_nom FROM pme WHERE pme_id=:id LIMIT 1');
                $pmeInfos->bindValue(':id', $pme, PDO::PARAM_INT);
                $pmeInfos->execute();
                $pmeInfos=$pmeInfos->fetch();
                ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="textInLines">
                                <div class="line"></div>
                                <div class="text">PME <?php echo $pmeInfos['pme_nom'] ?></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        //--select pme products depuis la base de données--
                        if ($filter == "recent"){
                            $query=$db->prepare('SELECT * FROM products WHERE pme_id=:id ORDER BY pr_id DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':id', $pme, PDO::PARAM_INT);
                            $query->execute();
                        }
                        else if ($filter == "croissant"){
                            $query=$db->prepare('SELECT * FROM products WHERE pme_id=:id ORDER BY pr_prix ASC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':id', $pme, PDO::PARAM_INT);
                            $query->execute();
                        }
                        else if ($filter == "decroissant"){
                            $query=$db->prepare('SELECT * FROM products WHERE pme_id=:id ORDER BY pr_prix DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':id', $pme, PDO::PARAM_INT);
                            $query->execute();
                        }
                        else{ //no filter
                            $query=$db->prepare('SELECT * FROM products WHERE pme_id=:id ORDER BY pr_id DESC LIMIT :min, :max');
                            $query->bindValue(':min', $min, PDO::PARAM_INT);
                            $query->bindValue(':max', $max, PDO::PARAM_INT);
                            $query->bindValue(':id', $pme, PDO::PARAM_INT);
                            $query->execute();
                        }
                        //--//select pme products depuis la base de données--

                        //----affichage--
                        while ($data=$query->fetch()){
                            ?><div class="col-6 col-md-3"><?php
                            display_product($data['pr_id'], $data['pr_title'], $data['pr_photo_1'], $data['pr_prix']);
                            ?></div><?php
                        }
                        //----//affichage--

                        $query->closeCursor();
                        ?>
                    </div>
                </div>
                <?php
            break;
        }
        ?>
    </div>
    <div class="row">
        <div class="col-12">
            <hr>
            <div style="display:flex; justify-content:center; flex-wrap: wrap; max-width: 100%; overflow:auto">
                <?php
                // ----pagination (prend en compte le filtre)-----
                if ($type != "all" and !isset($_GET['pme'])){
                    $maxProducts = $db->prepare('SELECT COUNT(*) FROM products WHERE pr_type=:type');
                    $maxProducts->bindValue(':type', $type, PDO::PARAM_STR);
                    $maxProducts->execute();
                    $maxProducts = $maxProducts->fetchcolumn();
                }
                else if ($type == "pme" and isset($_GET['pme'])){
                    $maxProducts = $db->prepare('SELECT COUNT(*) FROM products WHERE pme_id=:id');
                    $maxProducts->bindValue(':id', $pme, PDO::PARAM_INT);
                    $maxProducts->execute();
                    $maxProducts = $maxProducts->fetchcolumn();
                }
                else{
                    $maxProducts = $db->prepare('SELECT COUNT(*) FROM products');
                    $maxProducts->execute();
                    $maxProducts = $maxProducts->fetchcolumn();
                }

                $pageUrl = $_SERVER['QUERY_STRING']; //page url starting after ?
                $pageUrl = str_replace("&page=".$page, "", $pageUrl); //remove all &page=somthing in $pageUrl (because we gonna add it again)
                //echo $pageUrl;

                echo '<ul class="pagination">';
                if ($page <= 1){
                    echo '<li class="page-item disabled"><a class="page-link" href="#">Precedent</a></li>';
                }
                else{
                    echo '<li class="page-item"><a class="page-link" href="?'.$pageUrl.'&page='.($page-1).'">Precedent</a></li>';
                }

                $i=0;
                $p=1;
                while ($i < $maxProducts){
                    if ($p == $page){
                        echo '<li class="page-item active"><a class="page-link" href="?'.$pageUrl.'&page='.$p.'">'.$p.'</a></li>';
                    }
                    else{
                        echo '<li class="page-item"><a class="page-link" href="?'.$pageUrl.'&page='.$p.'">'.$p.'</a></li>';
                    }
                    $i += $max;
                    $p++;
                }

                if ($min+$max >= $maxProducts){
                    echo '<li class="page-item disabled"><a class="page-link" href="#">Suivant</a></li>';
                }
                else{
                    echo '<li class="page-item"><a class="page-link" href="?'.$pageUrl.'&page='.($page+1).'">Suivant</a></li>';
                }
                echo '</ul>';
                // ----//pagination (prend en compte le filtre)-----
                ?>
            </div>
        </div>
    </div>
</div>