<script type="text/javascript" src="../js/selecteur.js"></script>
<?php include('favicon.php');
	/*******************************
	*  Page: liste_admin.php
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
$info_page='Liste emprunteur';
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
<center><table title="Emprunteur" id="tr">
<tr>
	<td colspan="8" style="background-color: black; color: white;">Liste Emprunteur</td>
</tr>
<td style="background-color: #acacac;">
	<?php echo('id_emprunteur'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Nom'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Prenom'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Mail'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Numero de bureau'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Numero de telephone'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Modification du compte'); ?>
</td>
<td style="background-color: #acacac;">
	<?php echo('Supprimer le compte'); ?>
</td>
</tr>
<?php
//si "?supprimer" est dans l'URL:
		if(isset($_GET['supprimer'])){
			$id=$_GET['id'];
			if($_GET['supprimer']!="ok"){
				echo "<p>Êtes-vous sûr de vouloir supprimer votre compte définitivement?</p>
				<a href='?supprimer=ok&id=".$id."' style='color:red'>OUI</a> - <a href='?' style='color:green'>NON</a><br/><br/>";
			} else {
				//on supprime le membre avec "DELETE"
				if(mysqli_query($mysqli,"DELETE FROM emprunteur WHERE id_emprunteur='$id'")){
					echo "Votre compte vient d'être supprimé définitivement.";
				} else {
					echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
					//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
				}
			}
		}

$sql="SELECT * FROM emprunteur";
$result = $mysqli-> query($sql);
$result->num_rows>0;
while($row = $result-> fetch_assoc()){
?>
<tr>
<td style="background-color: #acacac;">
	<?php echo($row['id_emprunteur']);$did=$row['id_emprunteur']; ?>
</td>

<td style="background-color: #acacac;">
	<?php echo($row['nom_emprunteur']); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo($row['prenom_emprunteur']); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo($row['Mail_emprunteur']); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('N°'.$row['numero_bureau_emprunteur']); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo($row['numero_telephone_emprunteur']); ?>
</td>
<td style="background-color: #acacac;">
	<a href="?modifier=nom_emprunteur&id=<?php echo($row['id_emprunteur']) ?>">Modifier le nom</a><br/>
	<a href="?modifier=prenom_emprunteur&id=<?php echo($row['id_emprunteur']) ?>">Modifier le prenom</a>
				<br>
				<a href="?modifier=mdp&id=<?php echo($row['id_emprunteur']) ?>">Modifier le mot de passe</a>
			<?php
			if (isset($_GET['modifier'])) {
				$id=$_GET['id'];
					$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id' ");
					$info=mysqli_fetch_assoc($req);
			if($_GET['modifier']=="nom_emprunteur"&&$_GET['id']==$row['id_emprunteur']){
				echo "<p>Renseignez le formulaire ci-dessous pour modifier les informations:</p>";
				if(isset($_POST['valider'])){
					if(!isset($_POST['nom_emprunteur'])){
						echo "Le champ nom_emprunteur n'est pas reconnu.";
					} else  {
							//tout est OK, on met à jours son compte dans la base de données:
							if(mysqli_query($mysqli,"UPDATE emprunteur SET nom_emprunteur='".htmlentities($_POST['nom_emprunteur'],ENT_QUOTES,"UTF-8")."' WHERE id_emprunteur='$id'")){
								echo "le nom {$info['nom_emprunteur']} modifiée en {$_POST['nom_emprunteur']} avec succès!"."<br/>";
								$TraitementFini=true;//pour cacher le formulaire
								header("Refresh: 3; liste_emp.php");//reactulise la page automatiquement(temp indiquer est en seconde)
							} else {
								echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
								//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
							}
						}
					}
				if(!isset($TraitementFini)){ ?>
					<br>
					<form method="post" action="?modifier=nom_emprunteur&id=<?php echo($row['id_emprunteur']) ?>">
						<input type="text" name="nom_emprunteur" value="<?php echo $info['nom_emprunteur']; ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<input type="submit" name="valider" value="Valider la modification">
					</form>
					<?php
				}
			}if($_GET['modifier']=="prenom_emprunteur"&&$_GET['id']==$row['id_emprunteur']){
				echo "<p>Renseignez le formulaire ci-dessous pour modifier les informations:</p>";
				if(isset($_POST['vali'])){
					if(!isset($_POST['prenom_emprunteur'])){
						echo "Le champ prenom_emprunteur n'est pas reconnu.";
					} else  {
							//tout est OK, on met à jours son compte dans la base de données:
							if(mysqli_query($mysqli,"UPDATE emprunteur SET prenom_emprunteur='".htmlentities($_POST['prenom_emprunteur'],ENT_QUOTES,"UTF-8")."' WHERE id_emprunteur='$id'")){
								echo "le prenom {$info['prenom_emprunteur']} modifiée en {$_POST['prenom_emprunteur']} avec succès!"."<br/>";
								$TraitementFini=true;//pour cacher le formulaire
								header("Refresh: 3; liste_emp.php");//reactulise la page automatiquement(temp indiquer est en seconde)
							} else {
								echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
								//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
							}
						}
					}
				if(!isset($TraitementFini)){ ?>
					<br>
					<form method="post" action="?modifier=prenom_emprunteur&id=<?php echo($row['id_emprunteur']) ?>">
						<input type="text" name="prenom_emprunteur" value="<?php echo $info['prenom_emprunteur']; ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<input type="submit" name="vali" value="Valider la modification">
					</form>
					<?php
				}
			} elseif($_GET['modifier']=="mdp"&&$_GET['id']==$row['id_emprunteur']){
				echo "<p>Renseignez le formulaire ci-dessous pour modifier vos informations:</p>";
				//si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
				if(isset($_POST['valider'])){
					//vérifie si tous les champs sont bien pris en compte:
					if(!isset($_POST['nouveau_mdp'],$_POST['confirmer_mdp'])){
						echo "Un des champs n'est pas reconnu.";
					} else {
						if($_POST['nouveau_mdp']!=$_POST['confirmer_mdp']){
							echo "Les mots de passe ne correspondent pas.";
						} else {
							$NouveauMdp=md5($_POST['nouveau_mdp']);
								//tout est OK, on met à jours son compte dans la base de données:
								if(mysqli_query($mysqli,"UPDATE emprunteur SET mdp_emprunteur='$NouveauMdp' WHERE id_emprunteur='$id'")){
									echo "Mot de passe modifié avec succès!";
									$TraitementFini=true;//pour cacher le formulaire
									header("Refresh: 3; liste_emp.php");//reactulise la page automatiquement(temp indiquer est en seconde)
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
					<form method="post" action="?modifier=mdp&id=<?php echo($row['id_emprunteur']) ?>">
						<input type="password" name="nouveau_mdp" placeholder="Nouveau mot de passe..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<input type="password" name="confirmer_mdp" placeholder="Confirmer nouveau passe..." required>
						<input type="submit" name="valider" value="Valider la modification">
					</form>
					<?php
				}
			}}?>
</td>
<td style="background-color: #acacac;">
	<?php echo("<a href='?supprimer&id=".$row['id_emprunteur']."' style='color: red;'>cliquer ici pour Supprimer</a>"); ?>
</td>
</tr>
<?php } ?>
</table></center>