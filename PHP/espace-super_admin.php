<script type="text/javascript" src="../js/selecteur.js"></script>
<?php include('favicon.php');
	/*******************************
	*  Page: espace-super_admin.php
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
$type="Super-admin";
$info_page='Espace '.$type;
$date=date("Y-m-d");
$datetime=date("Y-m-d H:i:s");
$did='0';
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
<center><table id="tr"><tr id="tr"><td width="33.33%" id="tr"><center>
<div id="emprunter">
<?php
$req=mysqli_query($mysqli,"SELECT count('ID') as 'ID' FROM administrateur ");
$result_nombre_emprunter=mysqli_fetch_assoc($req);
echo("Nombre d'administrateur: <h1>".$result_nombre_emprunter['ID']."</h1><br/>");
?>
</div><!--liste de materielle emprunter-->
</center></td><td width="33.33%" id="tr"><center>
<div id="retard" >
<?php
$req=mysqli_query($mysqli,"SELECT count('id_emprunteur') as 'id_emprunteur' FROM emprunteur");
$result_nombre_emprunter=mysqli_fetch_assoc($req);
echo("Nombre d'emprunteur: <h1>".$result_nombre_emprunter['id_emprunteur']."</h1><br/>");
?>
</div><!--liste de materielle emprunter non rendu-->
</center></td></tr></table></center>
</body>
</html>