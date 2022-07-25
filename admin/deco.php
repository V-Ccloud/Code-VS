<?php
session_start();

//deconnexion
include('includes/db.php');

$query=$db->prepare('UPDATE admins SET ad_co=0 WHERE ad_id=:id');
$query->bindValue(':id', $_SESSION['ad_id'], PDO::PARAM_INT);
$query->execute();
$query->closeCursor();

$_SESSION['ad_id']=0;
?>
<script>window.location.href="index.php";</script>