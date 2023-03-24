<?php include('favicon.php');
	/*******************************
	*  Page: menu.php
	*  Page encodée en UTF-8
	*******************************/
	if ($info_page!='Espace Super-admin') {
		if ($info_page!='Liste emprunteur') {
			if ($info_page!='Liste admin') {
				$type=$info["Type"];
			}
		}
	}
	?>
<link rel="stylesheet" href="../CSS/bmenu.css"/>
<ul>
		<li class="dropdown" style="float: right"><a href="#site" class="dropbtn">
			<span id="date"></span><br/><span id="heure"></span> 
		<?php
		if ($type=='Super-admin') {echo("<hr/>"."Login: ".$Login."<br/>Super-admin");}
		else{echo("<hr/>"."Login: ".$info["Login"]."<br/>".$info["Type"]);}
		?></a>
		<div class="dropdown-content">
			<?php if ($type!='Super-admin') { ?>
		<a href="?modifier">Modifier vos informations</a>
		<a style="color: red" href="?supprimer">Supprimer votre compte</a>
			<?php } ?>
		<a href="deconnexion_admin.php">Déconexion</a>
		</div></li>
		<li style="float: right"><div id="CenterDiv"><h1><?php echo($info_page) ?></h1></div></li>
		<li><a href="creat.php" target="_blank"><img style="background-color: white;" height="8%" src="../images/ars_def.png"/></a></li>
		<?php
		if ($type=='Super-admin') {?>
			<li><a href="espace-super_admin.php">Espace <?php echo($type);?></a><br/></li>
			<li><a href="liste_admin.php">liste admin</a><br/></li>
			<li><a href="liste_emp.php">Liste emprunteur</a><br/></li>
		<?php }
			else{?>
		<li><a href="espace-membre_admin.php">Espace <?php echo($info["Type"]);?></a><br/></li>
		<?php
		if ($info["Type"]=='Technicien') { ?>
		<li><a href="entrer.php?new_type=NULL&new_marque=NULL&new_modele=NULL">Entée de stock</a><br/></li>
		<?php }
		if ($info["Type"]=='Technicien'||$info["Type"]=='Gestionnaire') { ?>
		<li><a href="gestion_mat.php?fillter=1">Gestion matériel</a></li>
		<?php } 
		if ($info["Type"]=='Technicien') { ?>
		<li><a href="gestion_emprunt.php">Gestion emprunt</a><br/></li>
		<?php }
		if ($info["Type"]=='Technicien'||$info["Type"]=='Gestionnaire') { ?>
		<li><a href="reserver.php">Demande d'emprunt</a><br/></li>
		<li><a href="emprunter.php">Matériel emprunter</a><br/></li>
		<li><a href="retard.php">Matériel non rendu</a><br/></li>
		<?php }} ?>
		</ul>
		<?php
		if ($type!='Super-admin') {
		//si "?supprimer" est dans l'URL:
		if(isset($_GET['supprimer'])){
			if($_GET['supprimer']!="ok"){
				echo "<p>Êtes-vous sûr de vouloir supprimer votre compte définitivement?</p>
				<br>
				<a href='?supprimer=ok' style='color:red'>OUI</a> - <a href='?' style='color:green'>NON</a>";
			} else {
				//on supprime le membre avec "DELETE"
				if(mysqli_query($mysqli,"DELETE FROM administrateur WHERE Login='$Login'")){
					echo "Votre compte vient d'être supprimé définitivement.";
					unset($_SESSION['Login']);//on tue la session Login avec unset()
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
				<a href="?modifier=Login">Modifier le Login</a>
				<br>
				<a href="?modifier=mdp">Modifier le mot de passe</a>
			</p>
			<hr/>
			<?php
			if($_GET['modifier']=="Login"){
				echo "<p>Renseignez le formulaire ci-dessous pour modifier vos informations:</p>";
				if(isset($_POST['valider'])){
					if(!isset($_POST['Login'])){
						echo "Le champ Login n'est pas reconnu.";
					} else  {
							//tout est OK, on met à jours son compte dans la base de données:
							if(mysqli_query($mysqli,"UPDATE administrateur SET Login='".htmlentities($_POST['Login'],ENT_QUOTES,"UTF-8")."' WHERE Login='$Login'")){
								echo "le Login $Login modifiée en {$_POST['Login']} avec succès!"."<br/>";
								$TraitementFini=true;//pour cacher le formulaire
							} else {
								echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
								//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
							}
						}
					}
				if(!isset($TraitementFini)){
					?>
					<br>
					<form method="post" action="?modifier=Login">
						<input type="text" name="Login" value="<?php echo $_SESSION['Login']; ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
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
							$req=mysqli_query($mysqli,"SELECT * FROM administrateur WHERE Login='$Login' AND mdp='$Mdp'");
							//on regarde si le mot de passe correspond à son compte:
							if(mysqli_num_rows($req)!=1){
								echo "Mot de passe actuel incorrect.";
							} else {
								//tout est OK, on met à jours son compte dans la base de données:
								if(mysqli_query($mysqli,"UPDATE administrateur SET mdp='$NouveauMdp' WHERE Login='$Login'")){
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
					<form method="post" action="?modifier=mdp">
						<input type="password" name="mdp" placeholder="Votre mot de passe actuel..." required>
						<input type="password" name="nouveau_mdp" placeholder="Nouveau mot de passe..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
						<input type="password" name="confirmer_mdp" placeholder="Confirmer nouveau passe..." required>
						<input type="submit" name="valider" value="Valider la modification">
					</form>
					<?php
				}
			}
		}}
		?>