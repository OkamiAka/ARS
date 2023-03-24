<link rel="stylesheet" href="../CSS/Style.css"/>
<?php include('favicon.php') ?>
<?php

	/*************************
	*  Page: connexion.php
	*  Page encodée en UTF-8
	**************************/

session_start();//session_start() combiné à $_SESSION (voir en fin de traitement du formulaire) nous permettra de garder le Login en sauvegarde pendant qu'il est connecté, si vous voulez que sur une page, le Login soit (ou tout autre variable sauvegardée avec $_SESSION) soit retransmis, mettez session_start() au début de votre fichier PHP, comme ici

$S_login='super-admin';
$S_mdp='Sadmin';
?><!DOCTYPE HTML>
<html>
	<head>
		<title>Connexion administrateur</title>
	</head>
	<body>
		<h1>Connexion administrateur</h1>
		<a href="../index.php">Retour à l'accueil</a>
		<br>
		<?php
		//si une session est déjà "isset" avec ce visiteur, on l'informe:
		if (isset($_SESSION['Login'])&&$_SESSION['Login']==$S_login) {
			echo "Vous êtes déjà connecté en super-admin".header("Refresh: 3;espace-super_admin.php");
		}
		elseif(isset($_SESSION['Login'])){
			echo "Vous êtes déjà connecté".header("Refresh: 3;espace-membre_admin.php");
		} else {
			//si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
			if(isset($_POST['valider'])){
				//vérifie si tous les champs sont bien pris en compte:
				if(!isset($_POST['Login'],$_POST['mdp'])){
					echo "Un des champs n'est pas reconnu.";
				} else {
					if ($_POST['Login']==$S_login&&$_POST['mdp']==$S_mdp) {
						$_SESSION['Login']=$S_login;
							echo "Vous êtes connecté avec succès $S_login!".header("Refresh: 3;espace-super_admin.php");
							$TraitementFini=true;//pour cacher le formulaire
					}else{
					//tous les champs sont précisés, on regarde si le membre est inscrit dans la bdd:
					//d'abord il faut créer une connexion à la base de données dans laquelle on souhaite regarder:
					$mysqli=mysqli_connect('localhost','root','root','ars');//'serveur','Login d'utilisateur','pass','Login de la table'
					if(!$mysqli) {
						echo "Erreur connexion BDD";
						//Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
						//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
					} else {
						//on défini nos variables:
						$Login=htmlentities($_POST['Login'],ENT_QUOTES,"UTF-8");//htmlentities avec ENT_QUOTES permet de sécuriser la requête pour éviter les injections SQL, UTF-8 pour dire de convertir en ce format
						$Mdp=md5($_POST['mdp']);
						$req=mysqli_query($mysqli,"SELECT * FROM administrateur WHERE Login='$Login' AND MDP='$Mdp'");
						//on regarde si le membre est inscrit dans la bdd:
						if(mysqli_num_rows($req)!=1){
							echo "Login ou mot de passe incorrect.";
						} else {
							//Login et mot de passe sont trouvé sur une même colonne, on ouvre une session:
							$_SESSION['Login']=$Login;
							echo "Vous êtes connecté avec succès $Login!".header("Refresh: 3;espace-membre_admin.php");
							$TraitementFini=true;//pour cacher le formulaire
						}
					}
				}
			}}
			if(!isset($TraitementFini)){//quand le membre sera connecté, on définira cette variable afin de cacher le formulaire
				?>
				<br>
				<p>Remplissez le formulaire ci-dessous pour vous connecter:</p>
				<form method="post" action="connexion_admin.php">
					<input type="text" name="Login" placeholder="Votre Login..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
					<input type="password" name="mdp" placeholder="Votre mot de passe..." required>
					<input type="submit" name="valider" value="Connexion!">
				</form>
				<?php
			}
		}
		?>
	</body>
</html>
