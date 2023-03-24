<link rel="stylesheet" href="../CSS/Style.css"/>
<script type="text/javascript" src="../js/selecteur.js"></script>
<?php include('favicon.php');
	/****************************
	*  Page: entrer.php
	*  Page encodée en UTF-8
	****************************/
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
//on récupère les infos du membre si on souhaite les afficher dans la page:
$req=mysqli_query($mysqli,"SELECT * FROM administrateur WHERE Login='$Login' ");
$info=mysqli_fetch_assoc($req);
$info_page='Entrée de stock';
?>
<?php include("menu.php"); ?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Entrée de stock</title>
	</head>
	<body>
		<br>
			<br>
			<p>Remplissez le formulaire pour rentrée du stock:</p>
			<center><div style="min-width: 60%;">
  				<form method="post" action="?<?php echo('new_type='.$_GET['new_type'].'&new_marque='.$_GET['new_marque'].'&new_modele='.$_GET['new_modele']); ?>">
  					<?php
  					$connmat=mysqli_query($mysqli,"SELECT * FROM materiel ");
					$info_mat=mysqli_fetch_assoc($connmat);

					//le type
  					if (isset($_GET['new_type'])&&$_GET['new_type']=='new'||mysqli_num_rows($connmat)==0) {
  						echo('Type materiel:<input type="text" name="type_materiel" placeholder="Le type..." autofocus required/>');
  					}
  					else{
					$sel="SELECT DISTINCT(`type_materiel`) FROM materiel";
    				$result = $mysqli-> query($sel);
    				$result->num_rows>0; ?>
  					Type materiel:<select name="type_materiel"><option style="background-color: black; color: white;">---select---</option> <?php
    				while($row = $result-> fetch_assoc())
    				{
      					echo('<option value="'.$row['type_materiel'].'">'.$row['type_materiel'].'');
    				} ?>
					</select></option>(<a href="?new_type=new&<?php echo('new_marque='.$_GET['new_marque'].'&new_modele='.$_GET['new_modele']); ?>">Nouveaux type de materiel</a>), 
					<?php
  					}

  					//la marque
  					if (isset($_GET['new_marque'])&&$_GET['new_marque']=='new'||mysqli_num_rows($connmat)==0) {
  						echo('Marque materiel:<input type="text" name="Marque_materiel" placeholder="La Marque..." required/>');
  					}
  					else{
					$sel="SELECT DISTINCT(`Marque_materiel`) FROM materiel";
    				$result = $mysqli-> query($sel);
    				$result->num_rows>0; ?>
  					Marque materiel:<select name="Marque_materiel"><option style="background-color: black; color: white;">---select---</option> <?php
    				while($row = $result-> fetch_assoc())
    				{
      					echo('<option value="'.$row['Marque_materiel'].'">'.$row['Marque_materiel'].'');
    				} ?>
					</select></option>(<a href="?<?php echo('new_type='.$_GET['new_type'].'&new_marque=new&new_modele='.$_GET['new_modele']); ?>">Nouveaux type de materiel</a>), 
					<?php
  					}

  					//le modele
  					if (isset($_GET['new_modele'])&&$_GET['new_modele']=='new'||mysqli_num_rows($connmat)==0) {
  						echo('Modele materiel:<input type="text" name="Modele_materiel" placeholder="Le Modele..." required/>');
  					}
  					else{
					$sel="SELECT DISTINCT(`Modele_materiel`) FROM materiel";
    				$result = $mysqli-> query($sel);
    				$result->num_rows>0; ?>
  					Modele materiel:<select name="Modele_materiel"><option style="background-color: black; color: white;">---select---</option> <?php
    				while($row = $result-> fetch_assoc())
    				{
      					echo('<option value="'.$row['Modele_materiel'].'">'.$row['Modele_materiel'].'');
    				} ?>
					</select></option>(<a href="?<?php echo('new_type='.$_GET['new_type'].'&new_marque='.$_GET['new_marque'].'&new_modele=new'); ?>">Nouveaux type de materiel</a>), 
					<?php
  					}
  					?>
				<br/>
				Entrer une date d'obselescence: <input type="date" name="Date_obsolescence" value="">
				Entrer le numero de serie<input type="radio" name="choix" value="num_serie" checked ><input type="text" name="Numero_de_serie" placeholder="Le numero de serie..." /></input>
				ou le nombre de materiel<input type="radio" name="choix" value="num_stock"><input type="number" name="numero" placeholder="Le nombre..."/></input><br/>
				<input type="submit" name="valider" value="Valider"/>
			</form>
		
		<?php
		//si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
		if(isset($_POST['valider'])){
			//vérifie si tous les champs sont bien  pris en compte:
			//on peut combiner isset() pour valider plusieurs champs à la fois
			if(!isset($_POST['type_materiel'],$_POST['Marque_materiel'],$_POST['Modele_materiel'],$_POST['Date_obsolescence'] )){
				echo "Un des champs n'est pas reconnu.";
			} else {
								//d'abord il faut créer une connexion à la base de données dans laquelle on souhaite l'insérer:
								$mysqli=mysqli_connect('localhost','root','root','ars');//'serveur','nom d'utilisateur','pass','nom de la table'
								if(!$mysqli) {
									echo "Erreur connexion BDD";
									//Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
									//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
								} else {
									$type_materiel=htmlentities($_POST['type_materiel'],ENT_QUOTES,"UTF-8");//htmlentities avec ENT_QUOTES permet de sécuriser la requête pour éviter les injections SQL, UTF-8 pour dire de convertir en ce format
									$Marque_materiel=htmlentities($_POST['Marque_materiel'],ENT_QUOTES,"UTF-8");
									$Modele_materiel=htmlentities($_POST['Modele_materiel'],ENT_QUOTES,"UTF-8");
									$Date_obsolescence=$_POST['Date_obsolescence'];
									$statut_materiel='libre';
									if($_POST['choix']=='num_serie'){
										$Numero_de_serie=htmlentities($_POST['Numero_de_serie'],ENT_QUOTES,"UTF-8");
										$numero=1;
									}elseif($_POST['choix']=='num_stock'){
										$Numero_de_serie='NULL';
										$numero=htmlentities($_POST['numero'],ENT_QUOTES,"UTF-8");
									}
									for($i = 0; $i <$numero ; $i++){
										//insertion du membre dans la base de données:
										if(mysqli_query($mysqli,"INSERT INTO materiel SET type_materiel='$type_materiel', Marque_materiel='$Marque_materiel', Modele_materiel='$Modele_materiel', Numero_de_serie='$Numero_de_serie', Date_obsolescence='$Date_obsolescence', statut_materiel='$statut_materiel' ")){
											$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE id_materiel=(SELECT MAX(id_materiel) FROM materiel) ");
											$info=mysqli_fetch_assoc($req);
											$id=$info["id_materiel"];
											$barcode="<img id='barcode' alt='testing' src='barcode.php?codetype=code128&size=50&text=".$id."&print=true'/>";
											$image="barcode.php?codetype=code128"."&"."size=50&text=".$id."&print=true";
											echo "<hr/>Stock inscrit avec succès!<br/>"."<center>".$barcode."</center>"." type materiel: $type_materiel, Marque materiel: $Marque_materiel, Modele materiel: $Modele_materiel";
										if($_POST['choix']=='num_serie'){echo(", Numero de serie: $Numero_de_serie.");}
										?>
  											<button onclick="myFunction()">imprimer</button>
											<script>
											function myFunction() {
											  print(<?php echo($barcode); ?>);
											}
											</script>
											<?php
											echo("<br/><br/>");
											//$TraitementFini=true;//pour cacher le formulaire
										} else {
											echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste."."<br/>";
											echo"<br>Erreur retournée: ".mysqli_error($mysqli);
										}
									}
								}
							}
						}
		if(!isset($TraitementFini)){//quand le membre sera inscrit, on définira cette variable afin de cacher le formulaire
		}
		?>
	</div></center></body>
</html>