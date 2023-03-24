<?php include('favicon.php');
	/****************************
	*  Page: reserver-sortie.php
	*  Page encod? en UTF-8
	****************************/
?><hr/>
Date de rendu:
<form method="post" action="?id=<?php echo($_GET['id']) ?>&valid">
	Le <input type="date" name="date" required >
	à <input type="time" name="time" required ><br/>
	<input type="submit" name="val" value="Valider" >
</form>
<?php
if(isset($_POST['val'])) {
				$barecode=$_GET['id'];
				$datetime=$_POST['date']." ".$_POST['time'];
				$req=mysqli_query($mysqli,"SELECT * FROM reserver WHERE id_materiel='$barecode' ");
				$reserver=mysqli_fetch_assoc($req);
				$id_empreinteur=$reserver['id_emprunteur'];
				mysqli_query($mysqli,"INSERT INTO emprunter SET id_materiel ='$barecode', id_emprunteur ='$id_empreinteur', date_fin='$datetime' ");
				mysqli_query($mysqli,"DELETE FROM reserver WHERE id_materiel ='$barecode'");
				mysqli_query($mysqli,"UPDATE materiel SET statut_materiel='emprunter' WHERE id_materiel ='$barecode'");
											
				header("Refresh: 0");
			}