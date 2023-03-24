<link rel="stylesheet" href="../CSS/Style.css"/>
<link rel="stylesheet" href="../CSS/bmenu.css"/>
<script type="text/javascript" src="../js/selecteur.js"></script>
<?php include('favicon.php'); 
	/*************************
	*  Page: espace-membre.php
	*  Page encodée en UTF-8
	**************************/

session_start();//session_start() combiné à $_SESSION (voir en fin de traitement du formulaire) nous permettra de garder le mail_emprunteur en sauvegarde pendant qu'il est connecté, si vous voulez que sur une page, le mail_emprunteur soit (ou tout autre variable sauvegardée avec $_SESSION) soit retransmis, mettez session_start() au début de votre fichier PHP, comme ici
if(!isset($_SESSION['mail_emprunteur'])){
	header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
	echo "Vous devez vous connecter pour accéder à l'espace emprunteur.<br><br><i>Redirection en cours, vers la page de connexion...</i>";
	exit(0);//on arrête l'éxécution du reste de la page avec exit, si le membre n'est pas connecté
}
$mail_emprunteur=$_SESSION['mail_emprunteur'];//on défini la variable $mail_emprunteur (Plus simple à écrire que $_SESSION['mail_emprunteur']) pour pouvoir l'utiliser plus bas dans la page

//on se connecte une fois pour toutes les actions possible de cette page:
$mysqli=mysqli_connect('localhost','root','root','ars');//'serveur','nom d'utilisateur','pass','nom de la table'
if(!$mysqli) {
	echo "Erreur connexion BDD";
	//Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
	//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
	exit(0);
}

//on récupère les infos du membre si on souhaite les afficher dans la page:
$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE mail_emprunteur='$mail_emprunteur' ");
$info=mysqli_fetch_assoc($req);
$id_emprunteur=$info['id_emprunteur'];
$datetime=date("Y-m-d H:i:s");
?><!DOCTYPE HTML>
<html>
	<head>
		<title>Espace emprunteur</title>
	</head>
	<body>
		<ul>
		<li class="dropdown" style="float: right"><a href="#site" class="dropbtn">
			<span id="date"></span><br/><span id="heure"></span><hr/>
		<?php
		echo("Nom: ".$info["nom_emprunteur"]."<br/>");
		echo("Prenom: ".$info["prenom_emprunteur"]."<br/>");
		?></a>
		<div class="dropdown-content">
		<a href="espace-membre.php?modifier">Modifier vos informations</a>
		<a style="color: red" href="espace-membre.php?supprimer">Supprimer votre compte</a>
		<a href="deconnexion.php">Deconexion</a>
		</div></li>
		<li><a href="creat.php" target="_blank"><img style="background-color: white;" height="8%" src="../images/ars_def.png"/></a></li>
		<li style="float: right"><div id="CenterDiv"><h1>Espace emprunteur</h1></div></li>
		</ul>
		<?php
		if(isset($_POST['valide'])){
          $req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE mail_emprunteur='$mail_emprunteur' ");
          $emprunteur=mysqli_fetch_assoc($req);
          $id_empreinteur=$emprunteur['id_emprunteur'];
          $type=$_GET['type'];
    	  $marque=$_GET['marque'];
    	  $modele=$_GET['modele'];
    	  $date=date('Y-m-d', strtotime('+7 day'));
          $qer=mysqli_query($mysqli,"SELECT * FROM materiel WHERE type_materiel='$type' AND Marque_materiel='$marque' AND Modele_materiel='$modele' AND statut_materiel='libre'");
          $stock=mysqli_fetch_assoc($qer);
          $barecode=$stock['id_materiel'];
          if(mysqli_query($mysqli,"INSERT INTO reserver SET id_materiel='$barecode', id_emprunteur='$id_empreinteur' , fin_reservation='$date'")){
                      mysqli_query($mysqli,"UPDATE materiel SET statut_materiel='reserver' WHERE id_materiel ='$barecode'");
                      echo "<br/><h1>Demande effectuée</h1>";
                      header("Refresh: 3; url=espace-membre.php");
                    } else {
                      echo "<br/>Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
                      echo "<br>Erreur retournée: ".mysqli_error($mysqli);
                    }
                    
        }
		//si "?supprime" est dans l'URL:
		if(isset($_GET['supprimer'])){
			if($_GET['supprimer']!="ok"){
				echo "<p>Êtes-vous sûr de vouloir supprimer votre compte définitivement?</p>
				<br>
				<a href='espace-membre.php?supprimer=ok' style='color:red'>OUI</a> - <a href='espace-membre.php' style='color:green'>NON</a>";
			} else {
				//on supprime le membre avec "DELETE"
				if(mysqli_query($mysqli,"DELETE FROM emprunteur WHERE mail_emprunteur='$mail_emprunteur")){
					echo "Votre compte vient d'être supprimé définitivement.";
					unset($_SESSION['mail_emprunteur']);//on tue la session mail_emprunteur avec unset()
				} else {
					echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
					//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
				}
			}
		}
		//si "?modifier" est dans l'URL:
		if(isset($_GET['modifier'])){
			?>
			<h1>Modification du compte</h1>
			Choisissez une option: 
			<p>
				<a href="espace-membre.php?modifier=mail_emprunteur">Modifier l'adresse mail</a>
				<br>
				<a href="espace-membre.php?modifier=mdp">Modifier le mot de passe</a>
			</p>
			<hr/>
			<?php
			if($_GET['modifier']=="mail_emprunteur"){
				echo "<p>Renseignez le formulaire ci-dessous pour modifier vos informations:</p>";
				if(isset($_POST['valider'])){
					if(!isset($_POST['mail_emprunteur'])){
						echo "Le champ mail n'est pas reconnu.";
					} else {
						if(!preg_match("#^[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?@[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?\.[a-z]{2,30}$#i",$_POST['mail_emprunteur'])){
							//cette preg_match est un petit peu complexe, je vous invite à regarder l'explication détaillée sur mon site c2script.com
							echo "L'adresse mail est incorrecte.";
							//normalement l'input type="email_emprunteur" vérifie que l'adresse mail_emprunteur soit correcte avant d'envoyer le formulaire mais il faut toujours être prudent et vérifier côté serveur (ici) avant de valider définitivement
						} else {
							//tout est OK, on met à jours son compte dans la base de données:
							if(mysqli_query($mysqli,"UPDATE emprunteur SET mail_emprunteur='".htmlentities($_POST['mail_emprunteur'],ENT_QUOTES,"UTF-8")."' WHERE nom='$nom'")){
								echo "Adresse $mail_emprunteur a ete modifiée par {$_POST['mail_emprunteur']} avec succès!";
								$TraitementFini=true;//pour cacher le formulaire
							} else {
								echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
								//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
							}
						}
					}
				}
				if(!isset($TraitementFini)){
					?>
					<br>
					<form method="post" action="espace-membre.php?modifier=mail_emprunteur">
						<input type="email" name="mail_emprunteur" value="<?php echo ($mail_emprunteur); ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<input type="submit" name="valider" value="Valider la modification">
					</form>
					<?php
				}
			} elseif($_GET['modifier']=="mdp"){
				echo "<p>Renseignez le formulaire ci-dessous pour modifier vos informations:</p>";
				//si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
				if(isset($_POST['valider'])){
					//vérifie si tous les champs sont bien pris en compte:
					if(!isset($_POST['nouveau_mdp'],$_POST['confirmer_mdp'],$_POST['mdp'])){
						echo "Un des champs n'est pas reconnu.";
					} else {
						if($_POST['nouveau_mdp']!=$_POST['confirmer_mdp']){
							echo "Les mots de passe ne correspondent pas.";
						} else {
							$Mdp=md5($_POST['mdp']);
							$NouveauMdp=md5($_POST['nouveau_mdp']);
							$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE nom='$nom' AND mdp='$Mdp'");
							//on regarde si le mot de passe correspond à son compte:
							if(mysqli_num_rows($req)!=1){
								echo "Mot de passe actuel incorrect.";
							} else {
								//tout est OK, on met à jours son compte dans la base de données:
								if(mysqli_query($mysqli,"UPDATE emprunteur SET mail_emprunteur='$mail_emprunteur' WHERE nom='$nom'")){
									echo "Mot de passe modifié avec succès!";
									$TraitementFini=true;//pour cacher le formulaire
								} else {
									echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
									//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
								}
							}
						}
					}
				}
				if(!isset($TraitementFini)){
					?>
					<br>
					<form method="post" action="espace-membre.php?modifier=mdp">
						<input type="password" name="nouveau_mdp" placeholder="Nouveau mot de passe..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<input type="password" name="confirmer_mdp" placeholder="Confirmer nouveau passe..." required>
						<input type="password" name="mdp" placeholder="Votre mot de passe actuel..." required>
						<input type="submit" name="valider" value="Valider la modification">
					</form>
					<?php
				}
			}
		}
		?>
	</body>
	<head>
		<meta charset="utf_8">
		<title></title>
    </head>
<body>
<br/>
<div id="divR">
	<div id="reserver">
Matériel demander<hr/>
<?php
include "connexion2.php";

$sql="SELECT * FROM reserver WHERE id_emprunteur='$id_emprunteur' ";
$result = $conn-> query($sql);

if($result->num_rows>0)
{
	while($row = $result-> fetch_assoc())
	{
		echo("<hr/>");
		$id_materiel=$row["id_materiel"];
		$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE statut_materiel='reserver' AND id_materiel='$id_materiel' ");
		$reserver=mysqli_fetch_assoc($req);
		$id_empreinteur=$row['id_emprunteur'];
		?><center><table id='stat'><tr><td>
		<center><?php
		echo("Materiel reserver<hr/>type materiel: ".$reserver["type_materiel"]."<br/>"." Marque: ".$reserver["Marque_materiel"]."<br/>"." Modele: ".$reserver["Modele_materiel"]."<br/>"." Numero de serie ".$reserver["Numero_de_serie"]);
		?></center></td></tr><tr><td>
			<?php
				echo('demande fais le '.$row['date_reservation']);
			?>
		</td></tr><tr>
			<td>
			<?php
				echo('refuser automatiquement le '.$row['fin_reservation']);
			?>
		</td></tr></table></center><!--le materiel reserver-->
		<?php
	}
}
if (!$result)
{
	echo("erreur SQL".$conn->error);
}	
?>
</div><!--liste de materielle reserver-->
<br/>
	<div id="emprunter">
Matériel emprunter<hr/>
<?php

include "connexion2.php";

$sql="SELECT * FROM emprunter WHERE id_emprunteur='$id_emprunteur' ";
$result = $conn-> query($sql);

if($result->num_rows>0)
{
	while($row = $result-> fetch_assoc())
	{
		echo("<hr/>");
		$id_materiel=$row["id_materiel"];
		$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE statut_materiel='emprunter' AND id_materiel='$id_materiel' ");
		$emprunter=mysqli_fetch_assoc($req);
		$id_empreinteur=$row['id_emprunteur'];
		$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_empreinteur' ");
		$emprunteur=mysqli_fetch_assoc($req);
		?><center><table id="stat"><tr><td>
		<center><?php
		echo("Materiel emprunter<hr/>type materiel: ".$emprunter["type_materiel"]."<br/>"." Marque: ".$emprunter["Marque_materiel"]."<br/>"." Modele: ".$emprunter["Modele_materiel"]."<br/>"." Numero de serie ".$emprunter["Numero_de_serie"]);
		?></center></td><!--le materiel emprunter--><?php
		?></tr>
		<tr><td>
			<?php
				echo('emprunter le '.$row['date_empreint']);
			?>
		</td></tr><tr>
			<td>
			<?php
				echo('a rendre pour le '.$row['date_fin']);
			?>
		</td></tr>
		</table><!--l'emprunteur--></center><?php
	}
}
if (!$result)
{
	echo("erreur SQL".$conn->error);
}	
?>
</div><!--liste de materielle emprunter--></body></div>
<div id="divL">
	<div id="retard" >
	Matériel à rendre<hr/>
<?php

include "connexion2.php";

$sql="SELECT * FROM emprunter WHERE id_emprunteur='$id_emprunteur' AND date_fin<'$datetime' ";
$result = $conn-> query($sql);

if($result->num_rows>0)
{
	while($row = $result-> fetch_assoc())
	{
		echo("<hr/>");
		$id_materiel=$row["id_materiel"];
		$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE statut_materiel='emprunter' AND id_materiel='$id_materiel' ");
		$emprunter=mysqli_fetch_assoc($req);
		$id_empreinteur=$row['id_emprunteur'];
		$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_empreinteur' ");
		$emprunteur=mysqli_fetch_assoc($req);
		?><center><table id="stat"><tr><td>
		<center><?php
		echo("Materiel emprunter<hr/>type materiel: ".$emprunter["type_materiel"]."<br/>"." Marque: ".$emprunter["Marque_materiel"]."<br/>"." Modele: ".$emprunter["Modele_materiel"]."<br/>"." Numero de serie ".$emprunter["Numero_de_serie"]);
		?></center></td><!--le materiel emprunter--><?php
		?></tr><tr><td>
			<?php
				echo('emprunter le '.$row['date_empreint']);
			?>
		</td></tr><tr><td>
			<?php
				echo('a rendre pour le '.$row['date_fin']);
			?>
		</td></tr></table><!--l'emprunteur--></center><?php
	}
}
if (!$result)
{
	echo("erreur SQL".$conn->error);
}	
?>
</div><!--liste de materielle emprunter non rendu-->
<br/>
	<div id="stock">
	<script type="text/javascript" src="js/selecteur.js"></script>
Demande de materiel<hr/>
<?php
echo("vous voulez demander un(e) ");
if(isset($_GET['type'])){
  echo($_GET['type']);
}
else{
  echo("{select type}");
}
echo(" de la marque ");
if(isset($_GET['marque'])){
  echo($_GET['marque']);
}
else{
  echo("{select marque}");
}
echo(" modele ");
if(isset($_GET['modele'])){
  echo($_GET['modele']);
}
else{
  echo("{select modele}");
}
if (isset($_GET['modele'])) {
    $type=$_GET['type'];
    $marque=$_GET['marque'];
    $modele=$_GET['modele'];
  ?>
  <form method="post" action="espace-membre.php?<?php echo("type=".$type."&marque=".$marque."&modele=".$modele); ?>">
    <input type="submit" name="valide" value="Valide" />
  </form>
  <br/>
  <?php
}
else{echo("<br/>veuillez remplire les 3 champs pour faire un emprunt<br/>");}

?><hr/>
<?php
include "connexion2.php";
$sql="SELECT DISTINCT(`type_materiel`) FROM materiel WHERE statut_materiel='libre' ";
$req=mysqli_query($conn,"SELECT count(DISTINCT `type_materiel`) as 'type_materiel' FROM materiel WHERE statut_materiel='libre' ");
$count_stock=mysqli_fetch_assoc($req);
$result = $conn-> query($sql);
if($result->num_rows>0)
{
  ?><select id="select_type" size="<?php echo($count_stock['type_materiel']+1 ); ?>">
     <option value='#' style="background-color: black; color: white;" >---select type---</option>
    <?php
  while($row = $result-> fetch_assoc())
  {
    $type_materiel=$row["type_materiel"];
    $req=mysqli_query($conn,"SELECT count(`type_materiel`) as 'type_materiel' FROM materiel WHERE statut_materiel='libre' AND type_materiel='$type_materiel' ");
    $count_all_stock=mysqli_fetch_assoc($req);
    $type_req=mysqli_query($conn,"SELECT count(DISTINCT `type_materiel`) as 'type_materiel' FROM materiel WHERE statut_materiel='$type_materiel' ");
    $count_type_stock=mysqli_fetch_assoc($type_req);
    echo("<option value='?type=".$row["type_materiel"]."'>".$row["type_materiel"]." (".$count_all_stock["type_materiel"].")"."</option>");
  }
  if(isset($_GET['type'])){
    $type=$_GET['type'];
  ?></select><?php
  $sql="SELECT DISTINCT(`Marque_materiel`) FROM materiel WHERE statut_materiel='libre' AND type_materiel='$type' ";
  $req=mysqli_query($conn,"SELECT count(DISTINCT `Marque_materiel`) as 'Marque_materiel' FROM materiel WHERE statut_materiel='libre' AND type_materiel='$type' ");
  $count_stock=mysqli_fetch_assoc($req);
  $result = $conn-> query($sql);
  if($result->num_rows>0)
  {
    ?><select id="select_marque" size="<?php echo($count_stock['Marque_materiel']+1 ); ?>">
  <option value='#' style="background-color: black; color: white;" >---select mat---</option><?php
    while($row = $result-> fetch_assoc())
  {
    $Marque_materiel=$row['Marque_materiel'];
    $req=mysqli_query($conn,"SELECT count(`Marque_materiel`) as 'Marque_materiel' FROM materiel WHERE statut_materiel='libre' AND type_materiel='$type' AND Marque_materiel='$Marque_materiel' ");
    $count_all_stock=mysqli_fetch_assoc($req);
    echo("<option value='?type=".$type."&marque=".$row["Marque_materiel"]."'>".$row["Marque_materiel"]." (".$count_all_stock["Marque_materiel"].")"."</option>");
  }
  if(isset($_GET['marque'])){
    $type=$_GET['type'];
    $marque=$_GET['marque'];
  ?></select><?php
  $sql="SELECT DISTINCT(`Modele_materiel`) FROM materiel WHERE statut_materiel='libre' AND type_materiel='$type' AND Marque_materiel='$marque' ";
  $req=mysqli_query($conn,"SELECT count(DISTINCT `Modele_materiel`) as 'Modele_materiel' FROM materiel WHERE statut_materiel='libre' AND type_materiel='$type' AND Marque_materiel='$marque' ");
  $count_stock=mysqli_fetch_assoc($req);
  $result = $conn-> query($sql);
  if($result->num_rows>0)
  {
    ?><select id="select_modele" size="<?php echo($count_stock['Modele_materiel']+1 ); ?>">
  <option value='#' style="background-color: black; color: white;">---select mat---</option><?php
    while($row = $result-> fetch_assoc())
  {
    $Modele_materiel=$row['Modele_materiel'];
    $req=mysqli_query($conn,"SELECT count(`Modele_materiel`) as 'Modele_materiel' FROM materiel WHERE statut_materiel='libre' AND type_materiel='$type' AND Marque_materiel='$marque' AND Modele_materiel='$Modele_materiel' ");
    $count_all_stock=mysqli_fetch_assoc($req);
    echo("<option value='?type=".$type."&marque=".$marque."&modele=".$row["Modele_materiel"]."'>".$row["Modele_materiel"]." (".$count_all_stock["Modele_materiel"].")"."</option>");
  }
  ?></select><?php
}}}}}
if (!$result)
{
  echo("erreur SQL".$conn->error);
} 
?>
</div><!--liste de materielle en stock--></div>
</body>
</html>