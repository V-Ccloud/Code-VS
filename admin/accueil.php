<?php
session_start();

include('includes/constants.php');
include('includes/db.php');
include('includes/functions.php');
?>

<!--
Author: Josué
Author URL: http://mailto:jose.init.dev@gmail.com
Website Owner : VS-CLOTHS
License: All Rights Reserved TJN Shekina
License URL: 
First version date : 2021-06-21
Version: 1.0  -  date: 2021-06-21
-->

<!doctype html>
<html lang="fr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Admin <?php echo $nom_site ?></title>

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- google fonts -->
  <link href="//fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900&display=swap" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="assets/images/icon-logo.png" type="text/css" media="all" />
	<?php
	//-----------permet de reconnaitre le client--------------
	if (isset($_SESSION['ad_nom']) and isset($_SESSION['ad_id'])){
		$ad_nom=$_SESSION['ad_nom'];
		$ad_id=$_SESSION['ad_id'];
	}
	else{
		$ad_nom = $nom_site;
		$ad_id = 0;
	}
	//-----------//permet de reconnaitre le client--------------
	//$ad_id=1;
	if ($ad_id<=0){ //chassez le
		?><script>window.location.href="index.php";</script><?php
	}
	?>
</head>

<body class="sidebar-menu-collapsed">
  <div class="se-pre-con"></div>
<section>
  <!-- sidebar menu start -->
  <div class="sidebar-menu sticky-sidebar-menu">
	  
    <!-- image logo -->
    <div class="logo">
      <a href="index.php">
        <?php echo $nom_site ?>
      </a>
    </div>
    <!-- //image logo -->

    <div class="logo-icon text-center">
		<span style="color:#fff"><?php echo $acronyme_site ?></span>
    </div>
    <!-- //logo end -->

    <div class="sidebar-menu-inner">

      <!-- sidebar nav start -->
      <ul class="nav nav-pills nav-stacked custom-nav">
        <li class="active"><a href="index.php"><i class="fa fa-tachometer"></i><span> Tableau de Bord</span></a>
        </li>
        <li><a href="accueil.php?type=confirmations"><i class="fa fa-table"></i> <span>Confirmations Comptes</span></a></li>
        <li><a href="accueil.php?type=commandes"><i class="fa fa-table"></i> <span>Commandes</span></a></li>
        <li><a href="accueil.php?type=articles"><i class="fa fa-table"></i> <span>Produits</span></a></li>
        <li><a href="accueil.php?type=pme"><i class="fa fa-table"></i> <span>PME</span></a></li>
        <li><a href="accueil.php?type=users"><i class="fa fa-table"></i> <span>Clients</span></a></li>
        <li><a href="accueil.php?type=admins"><i class="fa fa-table"></i> <span title="Profiles des administrateurs">Profiles Admins</span></a></li>
      </ul>
      <!-- //sidebar nav end -->
      <!-- toggle button start -->
      <a class="toggle-btn">
        <i class="fa fa-angle-double-left menu-collapsed__left"><span>Réduire</span></i>
        <i class="fa fa-angle-double-right menu-collapsed__right"></i>
      </a>
      <!-- //toggle button end -->
    </div>
  </div>
  <!-- //sidebar menu end -->
  <!-- header-starts -->
  <div class="header sticky-header">

    <!-- notification menu start -->
    <div class="menu-right">
      <div class="navbar user-panel-top">
        <div class="search-box">
          <h5>Bienvenue sur la page administrateur de <?php echo $nom_site ?></h5>
        </div>
        <div class="user-dropdown-details d-flex">
          <div class="profile_details_left">
            <ul class="nofitications-dropdown">
              <li class="dropdown">
				<?php
				$query=$db->prepare('SELECT COUNT(*) FROM commandes WHERE cm_livree=0');
				$query->execute();
				$nbr=$query->fetchcolumn();
				?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                    class="fa fa-bell-o"></i><span class="badge blue"><?php echo $nbr ?></span></a>
                <ul class="dropdown-menu">
                  <li>
                    <div class="notification_header">
                      <?php if ($nbr<=1){ ?><h3><?php echo $nbr ?> commande non livrée</h3><?php }
						else { ?><h3><?php echo $nbr ?> commandes non livrées</h3><?php } ?>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="dropdown">
				<?php
				$query=$db->prepare('SELECT COUNT(*) FROM carts GROUP BY us_id');
				$query->execute();
				$nbr=$query->fetchcolumn();
				?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-comment-o"></i><span class="badge blue"><?php echo $nbr ?></span></a>
                <ul class="dropdown-menu">
                  <li>
                    <div class="notification_header">
                      <?php if ($nbr<=1) { ?><h3><?php echo $nbr ?> panier non validé par le client</h3><?php }
					else { ?><h3><?php echo $nbr ?> paniers non validés par le client</h3><?php } ?>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
          <div class="profile_details">
            <ul>
              <li class="dropdown profile_details_drop">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenu3" aria-haspopup="true" aria-expanded="false">
                  <div class="profile_img">
                    <span class="fa fa-user" style="font-size:30px"></span>
                    <div class="user-active">
                      <span></span>
                    </div>
                  </div>
                </a>
                <ul class="dropdown-menu drp-mnu" aria-labelledby="dropdownMenu3">
					<?php
					$query=$db->prepare('SELECT * FROM admins ORDER BY ad_id DESC');
					$query->execute();
					while ($data=$query->fetch()){
						?>
						  <li class="user-info">
							  <a href="accueil.php?type=admins">
								<h5 class="user-name"><?php echo $data['ad_nom'] ?></h5>
								<?php
								if ($data['ad_co']){ ?>
									<span class="status ml-2 fa fa-dot-circle-o" style="color:lime"></span>
									<?php
								}else{ ?>
									<span class="status ml-2 fa fa-dot-circle-o" style="color:red"></span>
									<?php

								} ?>
							  </a>
							  <?php
							if ($data['ad_id']==$ad_id){
								?>
							    <div style="background:#fff; padding:7px">
							  	<a href="accueil.php?type=admins"><i class="lnr lnr-user"></i>Mon Profile</a><br>
							  	<a href="accueil.php?type=admins"><i class="lnr lnr-cog"></i>Paramètres</a><br>
								<span href="deco.php" class="logout"> <a href="deco.php"><i class="fa fa-power-off"></i> Deconnexion</a></span>
								</div>
							  	<?php
							}
							?>
						  </li>
						<?php
					}
					?>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--notification menu end -->
  </div>
  <!-- //header-ends -->
  <!-- main content start -->
<div class="main-content">

  <!-- content -->
  <div class="container-fluid content-top-gap">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb my-breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Connecté(e) en tant que <span class="text-primary"><?php echo $ad_nom ?></span>, Bienvenue !</li>
      </ol>
    </nav>

	  <?php
	$type=(isset($_GET['type']))?(htmlspecialchars($_GET['type'])):'';
			
	switch($type){
		default:
			?>
			<!-- statistics data -->
			<div class="statistics">
			  <div class="row">
				<div class="col-xl-6 pr-xl-2">
				  <div class="row">
					<div class="col-sm-6 pr-sm-2 statistics-grid">
					  <div class="card card_border border-primary-top p-4">
						  <?php
						  $query=$db->prepare('SELECT COUNT(*) FROM users');
						  $query->execute();
						  $nbr=$query->fetchcolumn();
						  ?>
						<i class="lnr lnr-users"> </i>
						<h3 class="text-primary number"><?php echo $nbr ?></h3>
						<p class="stat-text">Clients au total</p>
					  </div>
					</div>
					<div class="col-sm-6 pl-sm-2 statistics-grid">
					  <div class="card card_border border-primary-top p-4">
						  <?php
						  $query=$db->prepare('SELECT COUNT(*) FROM commandes WHERE cm_livree=0');
						  $query->execute();
						  $nbr=$query->fetchcolumn();
						  ?>
						<i class="lnr lnr-eye"> </i>
						<h3 class="text-secondary number"><?php echo $nbr ?></h3>
						<p class="stat-text">Livraisons en attente</p>
					  </div>
					</div>
				  </div>
				</div>
				<div class="col-xl-6 pl-xl-2">
				  <div class="row">
					<div class="col-sm-6 pr-sm-2 statistics-grid">
					  <div class="card card_border border-primary-top p-4">
						  <?php
						  $query=$db->prepare('SELECT COUNT(*) FROM pme');
						  $query->execute();
						  $nbr=$query->fetchcolumn();
						  ?>
						<i class="lnr lnr-cloud-download"> </i>
						<h3 class="text-success number"><?php echo $nbr ?></h3>
						<p class="stat-text">PME au total</p>
					  </div>
					</div>
					<div class="col-sm-6 pl-sm-2 statistics-grid">
					  <div class="card card_border border-primary-top p-4">
						  <?php
						  $query=$db->prepare('SELECT COUNT(*) FROM commandes WHERE cm_livree=1');
						  $query->execute();
						  $nbr=$query->fetchcolumn();
						  ?>
						<i class="lnr lnr-cloud-download"> </i>
						<h3 class="text-danger number"><?php echo $nbr ?></h3>
						<p class="stat-text">Livraisons éffectuées</p>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			</div>
			<!-- //statistics data -->

			
			<!-- charts -->
			<div class="chart">
			  <div class="row">
				<div class="col-lg-6 pl-lg-2 chart-grid">
				  <div class="card text-center card_border">
					<div class="card-header chart-grid__header">
					  Flux de visite pour <b>ce mois</b>
					</div>
					<div class="card-body">
					  <!-- line chart -->
					  <div id="container">
						<canvas id="linechart"></canvas>
					  </div>
					  <!-- //line chart -->
					</div>
					<div class="card-footer text-muted chart-grid__footer">
					  Seul le flux de ce mois est tracé
					</div>
				  </div>
				</div>

				<div class="col-lg-6 pr-lg-2 chart-grid">
				  <div class="card text-center card_border">
					<div class="card-header chart-grid__header">
					  Flux de visite pour <b>cette année</b>
					</div>
					<div class="card-body">
					  <!-- bar chart -->
					  <div id="container">
						<canvas id="barchart"></canvas>
					  </div>
					  <!-- //bar chart -->
					</div>
					<div class="card-footer text-muted chart-grid__footer">
					  Seul le flux des mois de cette année est tracé
					</div>
				  </div>
				</div>
			  </div>
			</div>
			<!-- //charts -->

	  		<?php
		break;
			
		case 'users':
			include('users.php');
		break;
			
		case 'commandes':
			include('commandes.php');
		break;
			
		case 'articles':
			include('articles.php');
		break;
			
		case 'pme':
			include('pme.php');
		break;
		
		case 'pme-actions':
			include('pme-actions.php');
		break;
			
		case 'confirmations':
			include('confirmations.php');
		break;
            
		case 'admins':
			include('admins.php');
		break;
	} //end of switch($type)
	  ?>
	  
	  
  </div>
  <!-- //content -->
</div>
<!-- main content end-->
</section>
	
  <!--footer section start-->
<footer class="dashboard">
  <p>&copy 2021 <?php echo $nom_site ?>. All Rights Reserved | <a href="mailto:jose.init.dev@gmail.com" target="_blank" style="font-size:9px">By Josué</a></p>
</footer>
<!--footer section end-->
<!-- move top -->
<button onclick="topFunction()" id="movetop" class="bg-primary" title="Go to top">
  <span class="fa fa-angle-up"></span>
</button>
<script>
  // When the user scrolls down 20px from the top of the document, show the button
  window.onscroll = function () {
    scrollFunction()
  };

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      document.getElementById("movetop").style.display = "block";
    } else {
      document.getElementById("movetop").style.display = "none";
    }
  }

  // When the user clicks on the button, scroll to the top of the document
  function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
</script>
<!-- /move top -->


<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/jquery-1.10.2.min.js"></script>

<script src="assets/js/Chart.min.js"></script>
<script>
	var donnees1=[];
	var donnees2=[];
</script>

<?php
$donnees1=array(); //contient le nbr de personnes à avoir visité le site à un jour donné (pour tous les jours du mois)
$donnees2=array(); //contient le nbr de personnes à avoir visité le site à un jour donné (pour tous les jours du mois)

$k=1; //le jour

while ($k < 31){
	$query1=$db->prepare('SELECT COUNT(*) FROM visiteurs WHERE mois=:m AND jour=:j');
	$query1->bindValue(':m', date('m'), PDO::PARAM_INT);
	$query1->bindValue(':j', $k, PDO::PARAM_INT);
	$query1->execute();
	$nbr=$query1->fetchcolumn();
  	
  	array_push($donnees1, $nbr); //ajouter le nombre de visite d ce jour
  	$k++;
}

$k2=1; //le jour

while ($k2 < 12){
	$query2=$db->prepare('SELECT COUNT(*) FROM visiteurs WHERE mois=:m AND annees=:dat');
	$query2->bindValue(':m', $k2, PDO::PARAM_INT);
	$query2->bindValue(':dat', date('Y'), PDO::PARAM_INT);
	$query2->execute();
	$nbr2=$query2->fetchcolumn();
  
  	array_push($donnees2, $nbr2); //ajouter le nombre de visite d ce jour
  	$k2++;
}
?>

<!------total web site visites----------->
<script>
new Chart(document.getElementById("linechart"), {
  type: 'line',
  data: {
	  labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
	  datasets: [{
		  label: '<?php echo date('M') ?>',
		  backgroundColor: "#4755AB",
		  borderColor: "#4755AB",
		  data: <?php echo json_encode($donnees1); ?>,
		  fill: false
	  }]
  },
  options: {
	  responsive: true,
	  // title: {
	  // 	display: true,
	  // 	text: 'Chart.js Line Chart'
	  // },
	  tooltips: {
		  mode: 'index',
		  intersect: false,
	  },
	  hover: {
		  mode: 'nearest',
		  intersect: true
	  },
	  scales: {
		  xAxes: [{
			  display: true,
			  scaleLabel: {
				  display: true,
				  labelString: 'Jours'
			  }
		  }],
		  yAxes: [{
			  display: true,
			  scaleLabel: {
				  display: true,
				  labelString: 'Visites'
			  }
		  }]
	  }
  }
});
</script>
  
<script>
new Chart(document.getElementById("barchart"), {
  type: 'bar',
  data: {
	  labels: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
	  datasets: [{
		  data: <?php echo json_encode($donnees2) ?>,
		  label: '<?php echo date('Y') ?>',
		  backgroundColor: "#4755AB",
		  borderWidth: 1,
	  }]
  },
  options: {
	  responsive: true,
	  legend: {
		  position: 'top',
	  },
  }
});
</script>
<!------//total web site visites----------->







<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/scripts.js"></script>

<!-- close script -->
<script>
  var closebtns = document.getElementsByClassName("close-grid");
  var i;

  for (i = 0; i < closebtns.length; i++) {
    closebtns[i].addEventListener("click", function () {
      this.parentElement.style.display = 'none';
    });
  }
</script>
<!-- //close script -->

<!-- disable body scroll when navbar is in active -->
<script>
  $(function () {
    $('.sidebar-menu-collapsed').click(function () {
      $('body').toggleClass('noscroll');
    })
  });
</script>
<!-- disable body scroll when navbar is in active -->

 <!-- loading-gif Js -->
 <script src="assets/js/modernizr.js"></script>
 <script>
     $(window).load(function () {
         // Animate loader off screen
         $(".se-pre-con").fadeOut("slow");;
     });
 </script>
 <!--// loading-gif Js -->
	
<!-- Bootstrap Core JavaScript -->
<script src="assets/js/bootstrap.min.js"></script>

<script>
	// document.querySelector('.toggle-btn').click();
	document.querySelector('.menu-collapsed__left').click();
</script>

</body>

</html>