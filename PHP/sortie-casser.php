<?php include('favicon.php');
	/****************************
	*  Page: sortie-casser.php
	*  Page encodée en UTF-8
	****************************/
?>
				<?php
				$barecode=$_GET['id'];
				mysqli_query($mysqli,"UPDATE materiel SET statut_materiel='casser' WHERE id_materiel ='$barecode'");
				mysqli_query($mysqli,"DELETE FROM emprunter WHERE id_materiel ='$barecode'");
				header("Refresh: 0; gestion_emprunt.php?id=$barecode");
				?>