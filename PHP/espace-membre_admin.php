<link rel="stylesheet" href="../CSS/Style.css"/>
<script type="text/javascript" src="../js/selecteur.js"></script>
<?php include('favicon.php');
header("Refresh: 5");//reactulise la page automatiquement(temp indiquer est en seconde)
	/*******************************
	*  Page: espace-membre_admin.php
	*  Page encodée en UTF-8
	*******************************/

session_start();//session_start() combiné à $_SESSION (voir en fin de traitement du formulaire) nous permettra de garder le nom en sauvegarde pendant qu'il est connecté, si vous voulez que sur une page, le nom soit (ou tout autre variable sauvegardée avec $_SESSION) soit retransmis, mettez session_start() au début de votre fichier PHP, comme ici
if(!isset($_SESSION['Login'])){
	header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
	echo "Vous devez vous connecter pour accéder à l'espace membre.<br><br><i>Redirection en cours, vers la page de connexion...</i>";
	exit(0);//on arrête l'éxécution du reste de la page avec exit, si le membre n'est pas connecté
}
$Login=$_SESSION['Login'];//on défini la variable $Login (Plus simple à écrire que $_SESSION['Login']) pour pouvoir l'utiliser plus bas dans la page

//on se connecte une fois pour toutes les actions possible de cette page:
$mysqli=mysqli_connect('localhost','root','root','ars');
if(!$mysqli) {
	echo "Erreur connexion BDD";
	//Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
	//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
	exit(0);
}
//on récupère les infos du membre si on souhaite les afficher dans la page:
$req=mysqli_query($mysqli,"SELECT * FROM administrateur WHERE Login='$Login' ");
$info=mysqli_fetch_assoc($req);
$info_page='Espace '.$info["Type"];
$date=date("Y-m-d");
$datetime=date("Y-m-d H:i:s");

$auto="SELECT * FROM materiel WHERE date_arrivee>Date_obsolescence ";
$result = $mysqli-> query($auto);
$result->num_rows>0;
while($row = $result-> fetch_assoc()){
  $barecode=$row['id_materiel'];
  mysqli_query($mysqli,"UPDATE materiel SET statut_materiel='obsolete' WHERE id_materiel ='$barecode'");
}
$auto="SELECT * FROM reserver WHERE fin_reservation<$date ";
$result = $mysqli-> query($auto);
$result->num_rows>0;
while($row = $result-> fetch_assoc()){
  $barecode=$row['id_materiel'];
  mysqli_query($mysqli,"UPDATE materiel SET statut_materiel='libre' WHERE id_materiel ='$barecode'");
        mysqli_query($mysqli,"DELETE FROM reserver WHERE id_materiel ='$barecode'");
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo($info_page);?></title>
		<meta charset="utf_8">
	</head>
	<body>
	<?php include("menu.php"); ?>
	<br/>
	<table width="100%" id="tr"><tr id="tr"><td width="33.33%" id="tr"><center>
<div id="reserver">
<?php
$req=mysqli_query($mysqli,"SELECT count('id_materiel') as 'id_materiel' FROM reserver ");
$result_nombre_reserver=mysqli_fetch_assoc($req);
echo("Nombre de demande d'emprunt: <h1>".$result_nombre_reserver['id_materiel']."</h1><br/>");
?>
</div><!--liste de materielle reserver-->
</center></td><td width="33.33%" id="tr"><center>
<div id="emprunter">
<?php
$req=mysqli_query($mysqli,"SELECT count('id_materiel') as 'id_materiel' FROM emprunter ");
$result_nombre_emprunter=mysqli_fetch_assoc($req);
echo("Nombre d'emprunt: <h1>".$result_nombre_emprunter['id_materiel']."</h1><br/>");
?>
</div><!--liste de materielle emprunter-->
</center></td><td width="33.33%" id="tr"><center>
<div id="retard" >
<?php
$req=mysqli_query($mysqli,"SELECT count('id_materiel') as 'id_materiel' FROM emprunter WHERE date_fin<'$datetime' ");
$result_nombre_emprunter=mysqli_fetch_assoc($req);
echo("Nombre d'emprunt non rendu: <h1>".$result_nombre_emprunter['id_materiel']."</h1><br/>");
?>
</div><!--liste de materielle emprunter non rendu-->
</center></td></tr></table>
<br/>
	<div id="stock">
stock de matériel<hr/>
Il y a actuellement:<br/>
<center><table id="tr"><td style="background-color: #acacac; color: black;">
<h1>Total matériel utilisable</h1>
<?php
include "connexion2.php";
$i1=0;
$i2=0;
$i3=0;
$sql="SELECT DISTINCT(`type_materiel`) FROM materiel WHERE statut_materiel='libre' OR statut_materiel='reserver' OR statut_materiel='emprunter' ";
$result = $conn-> query($sql);
$result->num_rows>0;
while($row = $result-> fetch_assoc())
{
  $type_materiel=$row["type_materiel"];
  $req=mysqli_query($conn,"SELECT count(`type_materiel`) as 'type_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='libre' OR type_materiel='$type_materiel' AND statut_materiel='reserver' OR type_materiel='$type_materiel' AND statut_materiel='emprunter' ");
  $count_all_type=mysqli_fetch_assoc($req);
  $req=mysqli_query($conn,"SELECT count(DISTINCT `Marque_materiel`) as 'Marque_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='libre' OR type_materiel='$type_materiel' AND statut_materiel='reserver' OR type_materiel='$type_materiel' AND statut_materiel='emprunter' ");
  $count_all_marque=mysqli_fetch_assoc($req);
  $req=mysqli_query($conn,"SELECT count(DISTINCT `Modele_materiel`) as 'Modele_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='libre' OR type_materiel='$type_materiel' AND statut_materiel='reserver' OR type_materiel='$type_materiel' AND statut_materiel='emprunter' ");
  $count_all_model=mysqli_fetch_assoc($req);
  echo($count_all_type['type_materiel']." ".$row['type_materiel']." de ".$count_all_marque['Marque_materiel']." marque et de ".$count_all_model['Modele_materiel']." model");
  ?><br/><?php
  $i1=$i1+1;
}
?><br/></td><td style="background-color: #acacac; color: black;">
<h1>Matériel actuellement libre</h1>
<?php

$sql="SELECT DISTINCT(`type_materiel`) FROM materiel WHERE statut_materiel='libre' ";
$result = $conn-> query($sql);
$result->num_rows>0;
while($row = $result-> fetch_assoc())
{
  $type_materiel=$row["type_materiel"];
  $req=mysqli_query($conn,"SELECT count(`type_materiel`) as 'type_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='libre' ");
  $count_all_type=mysqli_fetch_assoc($req);
  $req=mysqli_query($conn,"SELECT count(DISTINCT `Marque_materiel`) as 'Marque_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='libre' ");
  $count_all_marque=mysqli_fetch_assoc($req);
  $req=mysqli_query($conn,"SELECT count(DISTINCT `Modele_materiel`) as 'Modele_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='libre' ");
  $count_all_model=mysqli_fetch_assoc($req);
  echo($count_all_type['type_materiel']." ".$row['type_materiel']." de ".$count_all_marque['Marque_materiel']." marque et de ".$count_all_model['Modele_materiel']." model");
  ?><br/><?php
  $i2=$i2+1;
}echo('<br/>');
$if=$i1-$i2;
  for($i=0;$i<$if;$i++){
    echo('<br/>');  
  }
?></td><td style="background-color: #acacac; color: black;">
<h1>Matériel actuellement emprunter</h1>
<?php
$sql="SELECT DISTINCT(`type_materiel`) FROM materiel WHERE statut_materiel='emprunter' ";
$result = $conn-> query($sql);
$result->num_rows>0;
while($row = $result-> fetch_assoc())
{
  $type_materiel=$row["type_materiel"];
  $req=mysqli_query($conn,"SELECT count(`type_materiel`) as 'type_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='emprunter' ");
  $count_all_type=mysqli_fetch_assoc($req);
  $req=mysqli_query($conn,"SELECT count(DISTINCT `Marque_materiel`) as 'Marque_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='emprunter' ");
  $count_all_marque=mysqli_fetch_assoc($req);
  $req=mysqli_query($conn,"SELECT count(DISTINCT `Modele_materiel`) as 'Modele_materiel' FROM materiel WHERE type_materiel='$type_materiel' AND statut_materiel='emprunter' ");
  $count_all_model=mysqli_fetch_assoc($req);
  echo($count_all_type['type_materiel']." ".$row['type_materiel']." de ".$count_all_marque['Marque_materiel']." marque et de ".$count_all_model['Modele_materiel']." model");
  ?>
  <br/>
  <?php
  $i3=$i3+1;
}echo('<br/>');
$if=$i1-$i3;
  for($i=0;$i<$if;$i++){
    echo('<br/>');  
  }
?></td></table></center><?php
if (!$result)
{
  echo("erreur SQL".$conn->error);
} 
?>
</div><!--liste de materielle en stock-->
</body>
</html>