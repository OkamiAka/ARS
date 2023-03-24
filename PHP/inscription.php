<link rel="stylesheet" href="../CSS/Style.css"/>
<?php include('favicon.php');

	/*************************
	*  Page: inscription.php
	*  Page encodée en UTF-8
	**************************/

?><!DOCTYPE HTML>
<html>
	<head>
		<title>Script espace membre</title>
	</head>
	<body>
		<h1>Inscription</h1>
		<a href="../index.php">Retour à l'accueil</a>
		<br>
		<?php
		//si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
		if(isset($_POST['valider'])){
			//vérifie si tous les champs sont bien  pris en compte:
			//on peut combiner isset() pour valider plusieurs champs à la fois
			if(!isset($_POST['nom_emprunteur'],$_POST['prenom_emprunteur'],$_POST['mdp_emprunteur'],$_POST['Mail_emprunteur'],$_POST['numero_bureau_emprunteur'],$_POST['numero_telephone_emprunteur'])){
				echo "Un des champs n'est pas reconnu.";
			} else {
				//on vérifie le contenu de tous les champs, savoir si ils sont correctement remplis avec les types de valeurs qu'on souhaitent qu'ils aient
				if(!preg_match("#^[0-9]{1,15}$#",$_POST['numero_bureau_emprunteur'])){
					//la preg_match définie: ^ et $ pour dire commence et termine par notre masque;
					//notre masque défini a-z pour toutes les lettres en minuscules et 0-9 pour tous les chiffres;
					//d'une longueur de 1 min et 15 max
					echo "Le numero bureau est incorrect, doit contenir seulement des chiffres.";
					//Il est préférable que le nom_emprunteur soit en lettres minuscules ceci afin d'être unique, par exemple si le choix peut être avec majuscule, deux utilisateur pourrait avoir le même nom_emprunteur, par exemple Admin et admin et ce n'est pas ce que l'on veut.
				}else {
				//on vérifie le contenu de tous les champs, savoir si ils sont correctement remplis avec les types de valeurs qu'on souhaitent qu'ils aient
				if(!preg_match("#^[0-9]{1,15}$#",$_POST['numero_telephone_emprunteur'])){
					//la preg_match définie: ^ et $ pour dire commence et termine par notre masque;
					//notre masque défini a-z pour toutes les lettres en minuscules et 0-9 pour tous les chiffres;
					//d'une longueur de 1 min et 15 max
					echo "Le numero telephone est incorrect, doit contenir seulement des chiffres.";
					//Il est préférable que le nom_emprunteur soit en lettres minuscules ceci afin d'être unique, par exemple si le choix peut être avec majuscule, deux utilisateur pourrait avoir le même nom_emprunteur, par exemple Admin et admin et ce n'est pas ce que l'on veut.
				} else {
					//on vérifie le mot de passe:
					if(strlen($_POST['mdp_emprunteur'])<5 or strlen($_POST['mdp_emprunteur'])>15){
						echo "Le mot de passe doit être d'une longueur minimum de 5 caractères et de 15 maximum.";
					} else {
						//on vérifie que l'adresse est correcte:
						if(!preg_match("#^[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?@[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?\.[a-z]{2,30}$#i",$_POST['Mail_emprunteur'])){
							//cette preg_match est un petit peu complexe, je vous invite à regarder l'explication détaillée sur mon site c2script.com
							echo "L'adresse Mail_emprunteur est incorrecte.";
							//normalement l'input type="eMail_emprunteur" vérifie que l'adresse Mail_emprunteur soit correcte avant d'envoyer le formulaire mais il faut toujours être prudent et vérifier côté serveur (ici) avant de valider définitivement
						} else {
							if(strlen($_POST['Mail_emprunteur'])<7 or strlen($_POST['Mail_emprunteur'])>50){
								echo "Le Mail_emprunteur doit être d'une longueur minimum de 7 caractères et de 50 maximum.";
							} else {
								//tout est précisés correctement, on inscrit le membre dans la base de données si le nom n'est pas déjà utilisé par un autre utilisateur
								//d'abord il faut créer une connexion à la base de données dans laquelle on souhaite l'insérer:
								$mysqli=mysqli_connect('localhost','root','root','ars');//'serveur','nom d'utilisateur','pass','nom de la table'
								if(!$mysqli) {
									echo "Erreur connexion BDD";
									//Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
									//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
								} else {
									$nom_emprunteur=htmlentities($_POST['nom_emprunteur'],ENT_QUOTES,"UTF-8");//htmlentities avec ENT_QUOTES permet de sécuriser la requête pour éviter les injections SQL, UTF-8 pour dire de convertir en ce format
									$prenom_emprunteur=htmlentities($_POST['prenom_emprunteur'],ENT_QUOTES,"UTF-8");
									$mdp_emprunteur=md5($_POST['mdp_emprunteur']);// la fonction md5() convertie une chaine de caractères en chaine de 32 caractères d'après un algorithme PHP, cf doc
									$Mail_emprunteur=htmlentities($_POST['Mail_emprunteur'],ENT_QUOTES,"UTF-8");
									$numero_bureau_emprunteur=htmlentities($_POST['numero_bureau_emprunteur'],ENT_QUOTES,"UTF-8");
									$numero_telephone_emprunteur=htmlentities($_POST['numero_telephone_emprunteur'],ENT_QUOTES,"UTF-8");
									if(mysqli_num_rows(mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE Mail_emprunteur='$Mail_emprunteur'"))!=0){//si mysqli_num_rows retourne pas 0
										echo "Ce mail est déjà utilisé par un autre membre, veuillez en choisir un autre svp.";
									} else {
										//insertion du membre dans la base de données:
										if(mysqli_query($mysqli,"INSERT INTO emprunteur SET nom_emprunteur='$nom_emprunteur', prenom_emprunteur='$prenom_emprunteur', mdp_emprunteur='$mdp_emprunteur', Mail_emprunteur='$Mail_emprunteur', numero_bureau_emprunteur='$numero_bureau_emprunteur', numero_telephone_emprunteur='$numero_telephone_emprunteur' ")){
											echo "Inscrit avec succès!".header("Refresh: 3;connexion.php");
											$TraitementFini=true;//pour cacher le formulaire
										} else {
											echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
											//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
										}
									}
								}
							}
						}
					}
				}
			}
		}
}
		if(!isset($TraitementFini)){//quand le membre sera inscrit, on définira cette variable afin de cacher le formulaire
			?>
			<br>
			<p>Remplissez le formulaire ci-dessous pour vous inscrire:</p>
			<form method="post" action="inscription.php">
				<input type="text" name="nom_emprunteur" placeholder="Votre nom..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
				<input type="text" name="prenom_emprunteur" placeholder="Votre prenom..." required>
				<input type="password" name="mdp_emprunteur" placeholder="Votre mot de passe..." required><br/>
				<input type="email" name="Mail_emprunteur" placeholder="Votre mail..." required>
				<input type="number" name="numero_bureau_emprunteur" placeholder="Votre numero de bureau..." required>
				<input type="text" name="numero_telephone_emprunteur" placeholder="Votre numero telephone..." required><br/><br/>
				<input type="submit" name="valider" value="Cliquez ici pour envoyer le formulaire">
			</form>
			<?php
		}
		?>
	</body>
</html>