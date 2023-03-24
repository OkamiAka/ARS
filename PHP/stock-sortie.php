<link rel="stylesheet" href="../CSS/Style.css"/>
<?php include('favicon.php');
	/****************************
	*  Page: stock-sortie.php
	*  Page encodée en UTF-8
	****************************/
?>

<form method="post" action="gestion_emprunt.php?id=<?php echo($barecode) ?>&emprunter">
						<input type="email" name="mail_emprunteur" placeholder="mail de l'empreinteur..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<br/>
						Date de rendu:<br/>
						Le <input type="date" name="date" required >
						à <input type="time" name="time" required ><br/>
						<input type="submit" name="sotie" value="Valider">
					</form>
				<?php
				$barecode=$_GET['id'];
				if(isset($_POST['sotie'])){
					$mail_emprunteur=$_POST['mail_emprunteur'];
					$datetime=$_POST['date']." ".$_POST['time'];
					$req=mysqli_query($mysqli,"SELECT id_emprunteur FROM emprunteur WHERE mail_emprunteur='$mail_emprunteur' ");
					$emprunteur=mysqli_fetch_assoc($req);
					$id_empreinteur=$emprunteur['id_emprunteur'];
					if(mysqli_query($mysqli,"INSERT INTO emprunter SET id_materiel='$barecode', id_emprunteur='$id_empreinteur', date_fin='$datetime'  ")){
											mysqli_query($mysqli,"UPDATE materiel SET statut_materiel='emprunter' WHERE id_materiel ='$barecode'");
											echo "Emprunt reusi";
											$TraitementFini=true;//pour cacher le formulaire
											header("Refresh: 3; gestion_emprunt.php?id=$barecode");
										} else {
											echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
											echo "<br>Erreur retournée: ".mysqli_error($mysqli);
										}
										
				}