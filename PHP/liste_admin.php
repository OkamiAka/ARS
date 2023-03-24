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
$info_page='Liste admin';
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
<?php if (isset($_POST['val'])) {
	$Ntype=$_POST['type'];
	$Nlogin=$_POST['login'];
	$Nmdp=md5($_POST['mdp']);
	if(mysqli_query($mysqli,"INSERT INTO administrateur SET Type='$Ntype', Login='$Nlogin', MDP='$Nmdp' ")){
		echo('<h1>nouveau '.$Ntype.' bien enregistré</h1>');
		header("Refresh: 3; espace-super_admin.php");//reactulise la page automatiquement(temp indiquer est en seconde)
	}else{
		echo('🛑Erreur');
	}
}?>
<center><table title="Technicien&Gestionnaire" id="tr">
<tr>
	<td colspan="6" style="background-color: black; color: white;">Liste Technicien&Gestionnaire</td>
</tr>
<td style="background-color: #acacac;">
	<?php echo('ID'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Type'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Login'); ?>
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
				if(mysqli_query($mysqli,"DELETE FROM administrateur WHERE ID='$id'")){
					echo "Votre compte vient d'être supprimé définitivement.";
				} else {
					echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
					//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
				}
			}
		}

$sql="SELECT * FROM administrateur order by Type desc ";
$result = $mysqli-> query($sql);
$result->num_rows>0;
while($row = $result-> fetch_assoc()){
?>
<tr>
<td style="background-color: #acacac;">
	<?php echo($row['ID']);$did=$row['ID']; ?>
</td>

<td style="background-color: #acacac;">
	<?php echo($row['Type']); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo($row['Login']); ?>
</td>
<td style="background-color: #acacac;">
	<a href="?modifier=Login&id=<?php echo($row['ID']) ?>">Modifier le Login</a>
				<br>
				<a href="?modifier=mdp&id=<?php echo($row['ID']) ?>">Modifier le mot de passe</a>
			<?php
			if (isset($_GET['modifier'])) {
				$id=$_GET['id'];
					$req=mysqli_query($mysqli,"SELECT * FROM administrateur WHERE ID='$id' ");
					$info=mysqli_fetch_assoc($req);
			if($_GET['modifier']=="Login"&&$_GET['id']==$row['ID']){
				echo "<p>Renseignez le formulaire ci-dessous pour modifier les informations:</p>";
				if(isset($_POST['valider'])){
					if(!isset($_POST['Login'])){
						echo "Le champ Login n'est pas reconnu.";
					} else  {
							//tout est OK, on met à jours son compte dans la base de données:
							if(mysqli_query($mysqli,"UPDATE administrateur SET Login='".htmlentities($_POST['Login'],ENT_QUOTES,"UTF-8")."' WHERE ID='$id'")){
								echo "le Login {$info['Login']} modifiée en {$_POST['Login']} avec succès!"."<br/>";
								$TraitementFini=true;//pour cacher le formulaire
								header("Refresh: 3; espace-super_admin.php");//reactulise la page automatiquement(temp indiquer est en seconde)
							} else {
								echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
								//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
							}
						}
					}
				if(!isset($TraitementFini)){ ?>
					<br>
					<form method="post" action="?modifier=Login&id=<?php echo($row['ID']) ?>">
						<input type="text" name="Login" value="<?php echo $info['Login']; ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<input type="submit" name="valider" value="Valider la modification">
					</form>
					<?php
				}
			} elseif($_GET['modifier']=="mdp"&&$_GET['id']==$row['ID']){
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
								if(mysqli_query($mysqli,"UPDATE administrateur SET MDP='$NouveauMdp' WHERE ID='$id'")){
									echo "Mot de passe modifié avec succès!";
									$TraitementFini=true;//pour cacher le formulaire
									header("Refresh: 3; espace-super_admin.php");//reactulise la page automatiquement(temp indiquer est en seconde)
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
					<form method="post" action="?modifier=mdp&id=<?php echo($row['ID']) ?>">
						<input type="password" name="nouveau_mdp" placeholder="Nouveau mot de passe..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<input type="password" name="confirmer_mdp" placeholder="Confirmer nouveau passe..." required>
						<input type="submit" name="valider" value="Valider la modification">
					</form>
					<?php
				}
			}}?>
</td>
<td style="background-color: #acacac;">
	<?php echo("<a href='?supprimer&id=".$row['ID']."' style='color: red;'>cliquer ici pour Supprimer</a>"); ?>
</td>
</tr>
<?php } ?>
<tr>
	<td colspan="6" style="background-color: black; color: white;"><a href="?new" style="color: white;">→ Ajoute un Technicien ou un Gestionnaire ←</a></td>
</tr>
<?php if (isset($_GET['new'])) { ?>
<td style="background-color: #acacac;">
	<?php echo('ID'); ?>
</td>

<td style="background-color: #acacac;">
	<?php echo('Type'); ?>
</td>

<td colspan="2" style="background-color: #acacac;">
	<?php echo('Login'); ?>
</td>

<td colspan="2" style="background-color: #acacac;">
	<?php echo('mot de passe'); ?>
</td>
</tr>
<td style="background-color: #acacac;">
	↓
</td>

<td style="background-color: #acacac;">
	↓
</td>

<td colspan="2" style="background-color: #acacac;">
	↓
</td>

<td colspan="2" style="background-color: #acacac;">
	↓
</td>
</tr>
<form method="post" action="?new&val">
	<tr>
<td style="background-color: #acacac;">
	<?php echo($did+1); ?>
</td>

<td style="background-color: #acacac;">
	<select name="type" required>
		<option value="Technicien">Technicien</option>
		<option value="Gestionnaire">Gestionnaire</option>
	</select>
</td>

<td colspan="2" style="background-color: #acacac;">
	<input type="text" name="login" placeholder="obligatoire" required>
</td>

<td colspan="2" style="background-color: #acacac;">
	<input type="password" name="mdp" placeholder="obligatoire" required>
</td>
</tr>
<tr>
	<td colspan="6" style="background-color: black; color: white;"><input type="submit" name="val" value="Valider"></td>
</tr>
</form>
<?php }?>
</table></center>
</body>
</html>