<!-- this is product file -->

<div class="container-fluid myContainer">
    <div class="row">
        <div class="col-12">
            <select id="filterSelect" onchange="filterProducts(event, '<?php echo '?'.$_SERVER['QUERY_STRING'] ?>')" style="border-radius:5px">
                <option value="none">Ne pas filtrer</option>
                <option value="recent">Tri du plen récent au plus ancien</option>
                <option value="croissant">Tri par prix croissant</option>
                <option value="decroissant">Tri par prix décroissant</option>
                <option value="laitier">Afficher que les laitiers</option>
                <option value="legume">Afficher que les légumes</option>
                <option value="argume">Afficher que les argûmes</option>
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

        if ($filter != "none"){ //set current filter value
            ?>
            <script>
                document.getElementById('filterSelect').value = "<?php echo $filter ?>";
            </script>
            <?php
        }
        else if ($type != "all"){ //set current filter value
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
                        ?>
                    </div>
                </div>
                <?php
            break;

            case 'laitier':
                ?>
                <div class="col-12">
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

            case 'legume':
                ?>
                <div class="col-12">
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

            case 'argume':
                ?>
                <div class="col-12">
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
        }
        ?>
    </div>
    <div class="row">
        <div class="col-12">
            <hr>
            <div style="display:flex; justify-content:center; flex-wrap: wrap; max-width: 100%; overflow:auto">
                <?php
                // ----pagination (prend en compte le filtre)-----
                if ($type != "all"){
                    $maxProducts = $db->prepare('SELECT COUNT(*) FROM products WHERE pr_type=:type');
                    $maxProducts->bindValue(':type', $type, PDO::PARAM_STR);
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