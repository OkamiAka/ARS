<link rel="stylesheet" href="../CSS/Style.css"/>
<?php include('favicon.php');
	/****************************
	*  Page: stock-reserver.php
	*  Page encodée en UTF-8
	****************************/
?>

<form method="post" action="stock-reserver.php?id=<?php echo($_GET['id']) ?>">
						<input type="email" name="mail_emprunteur" placeholder="mail de l'empreinteur..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<br/>
						<input type="submit" name="sotie" value="Valider">
					</form>
				<?php
				$barecode=$_GET['id'];
				if(isset($_POST['sotie'])){
					$mail_emprunteur=$_POST['mail_emprunteur'];
					$req=mysqli_query($mysqli,"SELECT id_emprunteur FROM emprunteur WHERE mail_emprunteur='$mail_emprunteur' ");
					$emprunteur=mysqli_fetch_assoc($req);
					$id_empreinteur=$emprunteur['id_emprunteur'];
					if(mysqli_query($mysqli,"INSERT INTO reserver SET id_materiel='$barecode', id_emprunteur='$id_empreinteur' ")){
											mysqli_query($mysqli,"UPDATE materiel SET statut_materiel='reserver' WHERE id_materiel ='$barecode'");
											echo "Inscrit avec succès!.";
											$TraitementFini=true;//pour cacher le formulaire
											header("Refresh: 5");
										} else {
											echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
											echo "<br>Erreur retournée: ".mysqli_error($mysqli);
										}
										
				}