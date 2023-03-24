<?php include('favicon.php');
	/****************************
	*  Page: sortie-stock
	*  Page encodée en UTF-8
	****************************/
?>
				<?php
				$barecode=$_GET['id'];
				mysqli_query($mysqli,"UPDATE materiel SET statut_materiel='libre' WHERE id_materiel ='$barecode'");
				mysqli_query($mysqli,"DELETE FROM reserver WHERE id_materiel ='$barecode'");
				header("Refresh: 0; ?id=$barecode");
				?>