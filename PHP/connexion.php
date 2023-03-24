<link rel="stylesheet" href="../CSS/Style.css"/>
<?php include('favicon.php');

	/*************************
	*  Page: connexion.php
	*  Page encodée en UTF-8
	**************************/

session_start();//session_start() combiné à $_SESSION (voir en fin de traitement du formulaire) nous permettra de garder le nom_emprunteur en sauvegarde pendant qu'il est connecté, si vous voulez que sur une page, le nom_emprunteur soit (ou tout autre variable sauvegardée avec $_SESSION) soit retransmis, mettez session_start() au début de votre fichier PHP, comme ici

?><!DOCTYPE HTML>
<html>
	<head>
		<title>Connexion</title>
	</head>
	<body>
		<h1>Connexion</h1>
		<a href="../index.php">Retour à l'accueil</a>
		<br>
		<?php
		//si une session est déjà "isset" avec ce visiteur, on l'informe:
		if(isset($_SESSION['mail_emprunteur'])){
			echo "Vous êtes déjà connecté.".header("Refresh: 3;espace-membre.php");
		} else {
			//si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
			if(isset($_POST['valider'])){
				//vérifie si tous les champs sont bien pris en compte:
				if(!isset($_POST['mail_emprunteur'], $_POST['mdp_emprunteur'])){
					echo "Un des champs n'est pas reconnu.";
				} else {
					//tous les champs sont précisés, on regarde si le membre est inscrit dans la bdd:
					//d'abord il faut créer une connexion à la base de données dans laquelle on souhaite regarder:
					$mysqli=mysqli_connect('localhost','root','root','ars');//'serveur','nom d'utilisateur','pass','nom de la table'
					if(!$mysqli) {
						echo "Erreur connexion BDD";
						//Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
						
					} else {
						//on défini nos variables:
						$mail_emprunteur=htmlentities($_POST['mail_emprunteur'],ENT_QUOTES,"UTF-8");//htmlentities avec ENT_QUOTES permet de sécuriser la requête pour éviter les injections SQL, UTF-8 pour dire de convertir en ce format
						$mdp_emprunteur=md5($_POST['mdp_emprunteur']);// la fonction md5() convertie une chaine de caractères en chaine de 32 caractères d'après un algorithme PHP, cf doc
						$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE mail_emprunteur='$mail_emprunteur' AND mdp_emprunteur='$mdp_emprunteur'");
						//on regarde si le membre est inscrit dans la bdd:
						if(mysqli_num_rows($req)!=1){
							echo "mail ou mot de passe incorrect.";
						} else {
							//mail_emprunteur et mot de passe sont trouvé sur une même colonne, on ouvre une session:
							$_SESSION['mail_emprunteur']=$mail_emprunteur;
							$req=mysqli_query($mysqli,"SELECT nom_emprunteur, prenom_emprunteur FROM emprunteur WHERE mail_emprunteur='$mail_emprunteur' ");
							$info=mysqli_fetch_assoc($req);
							$nom_emprunteur=$info['nom_emprunteur'];
							$prenom_emprunteur=$info["prenom_emprunteur"];
							echo "Vous êtes connecté avec succès $nom_emprunteur $prenom_emprunteur!".header("Refresh: 3;espace-membre.php");
							$TraitementFini=true;//pour cacher le formulaire
						}
					}
				}
			}
			if(!isset($TraitementFini)){//quand le membre sera connecté, on définira cette variable afin de cacher le formulaire
				?>
				<br>
				<p>Remplissez le formulaire ci-dessous pour vous connecter:</p>
				<form method="post" action="connexion.php">
					<input type="email" name="mail_emprunteur" placeholder="Votre mail..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
					<input type="password" name="mdp_emprunteur" placeholder="Votre mot de passe..." required>
					<input type="submit" name="valider" value="Connexion"><br/><br/>
					<a href="connexion_admin.php">Connexion technicien</a>
				</form>
				<?php
			}
		}
		?>
	</body>
</html>