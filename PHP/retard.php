<link rel="stylesheet" href="../CSS/Style.css"/>
<link rel="stylesheet" href="../CSS/bmenu.css"/>
<script type="text/javascript" src="../js/selecteur.js"></script>
<?php include('favicon.php');
	/*******************************
	*  Page: retard.php
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
$info_page="matériel non rendu";
$datetime=date("Y-m-d H:i:s");
?>
<?php include("menu.php"); ?>
<title>matériel non rendu</title>
<?php if ($info["Type"]=='Technicien') { ?>
	<br/><a id="tr" href="retard.php?allmail"><input type="submit" value="Envoyer mail de retard a tous" id="but"></a><!--l'emprunteur--></center></td>
	<a id="tr" href="export_pdf.php?retard" target="_blank"><input type="submit" value="Export PDF" id="but"></a>
<?php } ?>
<br/><br/>
	matériel non rendu<hr/>
<?php

include "connexion2.php";

$sql="SELECT * FROM emprunter WHERE date_fin<'$datetime' ";
$result = $conn-> query($sql);
$i='0';?>
<center><table id="tr"><tr id="tr">
	<?php
if($result->num_rows>0)
{
	while($row = $result-> fetch_assoc())
	{
		if ($i>='3') {
			$i='1';
			?>
			</tr>
			<tr id="tr">
			<td id="tr">
			<?php
		}
		else{
			$i++;
			?>
			<td id="tr">
			<?php
		}
		$id_materiel=$row["id_materiel"];
		$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE id_materiel='$id_materiel'");
		$emprunter=mysqli_fetch_assoc($req);
		$id_empreinteur=$row['id_emprunteur'];
		$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_empreinteur' ");
		$emprunteur=mysqli_fetch_assoc($req);
		?><center><table id="stat"><tr><td>
		<center><?php
		echo("Materiel emprunter<hr/>id: ".$emprunter["id_materiel"]."<br/>"." type materiel: ".$emprunter["type_materiel"]."<br/>"." Marque: ".$emprunter["Marque_materiel"]."<br/>"." Modele: ".$emprunter["Modele_materiel"]."<br/>"." Numero de serie ".$emprunter["Numero_de_serie"]);
		?></center></td><!--le materiel emprunter--><?php
		?><td><center><?php
		$numero_telephone_emprunteur=$emprunteur["numero_telephone_emprunteur"];
		echo("Emprunteur<hr/>Nom: ".$emprunteur["nom_emprunteur"]."<br/>"." Prenom: ".$emprunteur["prenom_emprunteur"]."<br/>"." Numero de bureau: N°".$emprunteur["numero_bureau_emprunteur"]."<br/>"." Numero de telephone: ".$numero_telephone_emprunteur);
		?></center></td></tr><tr><td>
			<?php
				echo('emprunter le '.$row['date_empreint']);
			?>
		</td>
			<td>
			<?php
				echo('a rendre pour le '.$row['date_fin']);
			?>
		</td></tr></table><!--l'emprunteur-->
		<?php if ($info["Type"]=='Technicien') { ?>
			<a id="tr" href="retard.php?id=<?php echo($row["id_materiel"])?>&mail"><input type="submit" value="Envoyer mail de retard" id="but"></a><!--l'emprunteur--></center></td><?php
		}?>
		</center></td><?php
	}
	?>
		</tr></center>
		<?php
		if (isset($_GET['mail'])||isset($_GET['allmail'])) {
					include"mail.php";
	}
}
if (!$result)
{
	echo("erreur SQL".$conn->error);
}	
?>