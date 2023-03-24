<link rel="stylesheet" href="../CSS/Style.css"/>
<?php include('favicon.php');
	/****************************
	*  Page: gestion_emprunt.php
	*  Page encodée en UTF-8
	****************************/
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
$info_page='Gestion emprunt';
?>
<title>Gestion emprunt</title>
<?php include("menu.php"); ?>
<br/><br/>
			<center><div style="width: 600px;">
Scanner le code barre ici:
<form method="post" action="gestion_emprunt.php">
<input type="text" name="barecode" placeholder="Le code barre..." autofocus required/>
<input type="submit" name="verifier" value="Verifier"/><br/>
</form>
<!--<input type="submit" name="sortie" value="Soitie de stock"/>
<input type="submit" name="enter" value="Enter de stock"/>-->
<?php header("Content-type: text/html; charset=utf_8"); 
//si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
		if(isset($_POST['verifier'])||isset($_GET['id'])){
			if (isset($_GET['id'])) {
				$barecode=$_GET['id'];
			}
			else{
				$barecode=$_POST['barecode'];
			}
			$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE id_materiel='$barecode' ");
			$materiel=mysqli_fetch_assoc($req);
			switch($materiel['statut_materiel']){
				case "libre":
				?><h1>Le matériel est en stock</h1><?php
				?><center><table id="stat"><tr><td>
				<center><?php
				echo("Matériel en stock<hr/>id: ".$materiel["id_materiel"]."<br/>"." type materiel: ".$materiel["type_materiel"]."<br/>"." Marque: ".$materiel["Marque_materiel"]."<br/>"." Modele: ".$materiel["Modele_materiel"]."<br/>"." Numero de serie ".$materiel["Numero_de_serie"]);
				?></center></td><!--le materiel reserver--><?php
				?></tr></table></center><br/>
				<!-- <a id="but" href="stock-reserver.php?id=<?php echo($barecode) ?>" target="_blank">reserver</a> -->
				<a id="tr" href="gestion_emprunt.php?id=<?php echo($barecode) ?>&emprunter"><input id="but" type="submit" value="emprunter"></a>
				<?php
				if (isset($_GET['emprunter'])) {
					include"stock-sortie.php";
				}
					break;
				case "reserver":
				?><h1>Le matériel est en demande d'emprunt</h1><?php
				$req=mysqli_query($mysqli,"SELECT * FROM reserver WHERE id_materiel='$barecode' ");
				$reserver=mysqli_fetch_assoc($req);
				$id_empreinteur=$reserver['id_emprunteur'];
				$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_empreinteur' ");
				$emprunteur=mysqli_fetch_assoc($req);
				?><center><table id="stat"><tr><td>
				<center><?php
				echo("Matériel demander<hr/>id: ".$materiel["id_materiel"]."<br/>"." type matériel: ".$materiel["type_materiel"]."<br/>"." Marque: ".$materiel["Marque_materiel"]."<br/>"." Modele: ".$materiel["Modele_materiel"]."<br/>"." Numero de serie ".$materiel["Numero_de_serie"]);
				?></center></td><!--le matériel reserver--><?php
				?><td><center><?php
				$numero_telephone_emprunteur=$emprunteur["numero_telephone_emprunteur"];
				echo("Demander par:<hr/>Nom: ".$emprunteur["nom_emprunteur"]."<br/>"." Prenom: ".$emprunteur["prenom_emprunteur"]."<br/>"." Numero de bureau: N°".$emprunteur["numero_bureau_emprunteur"]."<br/>"." Numero de telephone: ".$numero_telephone_emprunteur);
				?></center></td></tr><tr><td>
			<?php
				echo('demande fais le '.$reserver['date_reservation']);
			?>
		</td><td>
			<?php
				echo('refuser automatiquement le '.$reserver['fin_reservation']);
			?>
		</td></tr></table></center><!--le materiel reserver-->
		</table><!--l'emprunteur--></center>
				<a id="tr" href="gestion_emprunt.php?id=<?php echo($barecode)?>&refu"><input id="but" type="submit" value="refuser l'emprunt"></a>
				<a id="tr" href="gestion_emprunt.php?id=<?php echo($barecode)?>&valid"><input id="but" type="submit" value="autoriser l'emprunt"></a>
				<?php
				if (isset($_GET['refu'])) {
					include"reserver-stock.php";
				}if (isset($_GET['valid'])) {
					include"reserver-sortie.php";
				}
					break;
				case "emprunter":
				?><h1>Le matériel est emprunter</h1><?php
				$req=mysqli_query($mysqli,"SELECT * FROM emprunter WHERE id_materiel ='$barecode' ");
				$emprunter=mysqli_fetch_assoc($req);
				$id_empreinteur=$emprunter['id_emprunteur'];
				$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_empreinteur' ");
				$emprunteur=mysqli_fetch_assoc($req);
				?><center><table id="stat"><tr><td>
				<center>
				<?php
				echo("Matériel emprunter<hr/>id: ".$materiel["id_materiel"]."<br/>"." type matériel: ".$materiel["type_materiel"]."<br/>"." Marque: ".$materiel["Marque_materiel"]."<br/>"." Modele: ".$materiel["Modele_materiel"]."<br/>"." Numero de serie ".$materiel["Numero_de_serie"]);
				?></center></td><!--le matériel emprunter--><?php
				?><td><center><?php
				$numero_telephone_emprunteur=$emprunteur["numero_telephone_emprunteur"];
				echo("Emprunteur<hr/>Nom: ".$emprunteur["nom_emprunteur"]."<br/>"." Prenom: ".$emprunteur["prenom_emprunteur"]."<br/>"." Numero de bureau: N°".$emprunteur["numero_bureau_emprunteur"]."<br/>"." Numero de telephone: ".$numero_telephone_emprunteur);
				?></center></td></tr><tr><td>
			<?php
				echo('emprunter le '.$emprunter['date_empreint']);
			?>
		</td>
			<td>
			<?php
				echo('a rendre pour le '.$emprunter['date_fin']);
			?>
		</td></tr></table><!--l'emprunteur--></center>
				<a id="tr" href="gestion_emprunt.php?id=<?php echo($barecode)?>&retoure"><input type="submit" value="retour en stock" id="but"></a>
				<a id="tr" href="gestion_emprunt.php?id=<?php echo($barecode)?>&vetuste"><input type="submit" value="materiel abimer" id="but"></a>
				<a id="tr" href="gestion_emprunt.php?id=<?php echo($barecode)?>&casser"><input type="submit" value="materiel casser" id="but"></a>
				<?php
				if (isset($_GET['retoure'])) {
					include"sortie-stock.php";
				}elseif (isset($_GET['vetuste'])) {
					include"sortie-vetuste.php";
				}elseif(isset($_GET['casser'])){
					include"sortie-casser.php";
				}
					break;
				case "obsolete":
				?><h1>Le matériel est obsolete</h1><?php
				?><center><table id="stat"><tr><td>
				<center><?php
				echo("Matériel obsolete<hr/>id: ".$materiel["id_materiel"]."<br/>"." type matériel: ".$materiel["type_materiel"]."<br/>"." Marque: ".$materiel["Marque_materiel"]."<br/>"." Modele: ".$materiel["Modele_materiel"]."<br/>"." Numero de serie ".$materiel["Numero_de_serie"]);
				?></center></td><!--le matériel reserver--><?php
				?></tr></table></center><?php
					break;
				case "vetuste":
				?><h1>Le matériel est vétuste</h1><?php
				?><center><table id="stat"><tr><td>
				<center><?php
				echo("Matériel vétuste<hr/>id: ".$materiel["id_materiel"]."<br/>"." type matériel: ".$materiel["type_materiel"]."<br/>"." Marque: ".$materiel["Marque_materiel"]."<br/>"." Modele: ".$materiel["Modele_materiel"]."<br/>"." Numero de serie ".$materiel["Numero_de_serie"]);
				?></center></td><!--le matériel vétuste--><?php
				?></tr></table></center>
				<a id="tr" href="gestion_emprunt.php?id=<?php echo($barecode)?>&retoure"><input type="submit" value="retour en stock" id="but"></a>
				<?php
				if (isset($_GET['retoure'])) {
					include"sortie-stock.php";
				}
					break;
				case "casser":
				?><h1>Le matériel est casser</h1><?php
				?><center><table id="stat"><tr><td>
				<center><?php
				echo("Matériel casser<hr/>id: ".$materiel["id_materiel"]."<br/>"." type matériel: ".$materiel["type_materiel"]."<br/>"." Marque: ".$materiel["Marque_materiel"]."<br/>"." Modele: ".$materiel["Modele_materiel"]."<br/>"." Numero de serie ".$materiel["Numero_de_serie"]);
				?></center></td><!--le matériel reserver--><?php
				?></tr></table></center><?php
					break;
				default:
				?><h1>Le matériel est introuvable</h1><?php
					break;
			}
}
?>
		</div></center>