<link rel="stylesheet" href="../CSS/Style.css"/>
<script type="text/javascript" src="../js/selecteur.js"></script>
<?php include('favicon.php');
  /****************************
  *  Page: gestion_mat.php
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
$info_page='Gestion matériel';
?>
<title>Gestion matériel</title>
<?php include("menu.php");
$fillter=$_GET['fillter'];
 if (isset($_GET['fil'])) {
$fil=$_GET['fil'];
  $res=$fillter."='".$fil."'";
  $deb='fillter='.$_GET['fillter'].'&fil='.$_GET['fil'];
}else{
$res=$fillter;
  $deb='fillter='.$_GET['fillter'];
}
 ?> 
<br/>
<a id="but" href="export_csv.php?<?php echo($deb); ?>&export_csv" target="_blank">Export_csv</a>
<a id="but" href="export_pdf.php?<?php echo($deb); ?>&export_pdf" target="_blank">Export_PDF</a>
<br/><br/>
<style type="text/css">
  td{
    text-align: center;
  }
</style>
<select id="select_type">
  <option value="?fillter=1">ALL</option>
  <option value="?fillter=statut_materiel">statut_materiel</option>
  <option value="?fillter=type_materiel">type_materiel</option>
  <option value="?fillter=Marque_materiel">Marque_materiel</option>
  <option value="?fillter=Numero_de_serie&fil=NULL">Numero de serie non enregistrer</option>
</select>
<?php if (isset($_GET['fillter'])&&$_GET['fillter']!='1'&&$_GET['fillter']!='Numero_de_serie') {
  $type=$_GET['fillter'];
    $sel="SELECT DISTINCT(`$type`) FROM materiel";
    $result = $mysqli-> query($sel);
    $result->num_rows>0; ?>
  <select id="select_marque"><option value="NULL">---select---</option> <?php
    while($row = $result-> fetch_assoc())
    {
      echo('<option value="?fillter='.$_GET['fillter'].'&fil='.$row[$type].'">'.$row[$type].'</option>');
    } ?>
</select>
<?php } ?>
<center><table style="background-color: #acacac; color: black;">
  <tr style="background-color: #acacac;">
    <td style="background-color: black; color: white;"> <?php
  echo('id_materiel');
  ?> </td><td style="background-color: black; color: white;"> <?php
  echo('statut_materiel');
  ?> </td><td style="background-color: black; color: white;"> <?php
  echo('type_materiel');
  ?> </td><td style="background-color: black; color: white;"> <?php
  echo('Marque_materiel');
?> </td><td style="background-color: black; color: white;"> <?php
  echo('Modele_materiel');
  ?> </td><td style="background-color: black; color: white;"> <?php
  echo('Numero_de_serie');
  ?> </td><td style="background-color: black; color: white;"> <?php
  echo('date_arrivee');
  ?> </td><td style="background-color: black; color: white;"> <?php
  echo('Date_obsolescence');
  ?></td></tr><?php
include "connexion2.php";
if (isset($_POST['valide'])) {
        $num=$_POST['num'];
        $modif=$_GET['modif'];
        mysqli_query($conn,"UPDATE materiel SET Numero_de_serie='$num' WHERE id_materiel='$modif'");
        echo('<h1>modifié avec succès</h1>');
      }
$sql="SELECT * FROM materiel WHERE $res ORDER BY id_materiel desc";
$result = $conn-> query($sql);
$result->num_rows>0;
while($row = $result-> fetch_assoc())
{
  ?> 
  <?php
$number = $row['id_materiel'];
    if($number % 2 == 0){
        $color='style="background-color: #FFFFFF;"';
    }
    else{
        $color='style="background-color: #acacac;"';
    }
?>

  <tr <?php echo($color); ?> >
    <td <?php echo($color); ?> > <?php
  echo("<img id='barcode' alt='testing' src='barcode.php?codetype=code128&size=20&text=".$row['id_materiel']."&print=true'/>");
  ?> </td><td <?php echo($color); ?> > <?php
  echo($row['statut_materiel']);
  ?></td><td <?php echo($color); ?> ><?php
  echo($row['type_materiel']);
  ?> </td><td <?php echo($color); ?> > <?php
  echo($row['Marque_materiel']);
?> </td><td <?php echo($color); ?> > <?php
  echo($row['Modele_materiel']);
  if (isset($_GET['modif'])) {
    if ($row['id_materiel']==$_GET['modif']&&isset($_POST['valide'])) {
    ?> </td><td style="background-color: #71ac20;"> <?php
  }
  else{
  ?> </td><td <?php echo($color); ?> > <?php
}
  }
  else{
  ?> </td><td <?php echo($color); ?> > <?php
}
  if($row['Numero_de_serie']=='NULL'&&$info['Type']=='Technicien'){
    echo('<a id="a_colorb" href="?'.$deb.'&modif='.$row['id_materiel'].'">'.'Numéro de série non précisé<br/>cliquez-ici pour le mettre'.'</a>');
    if (isset($_GET['modif'])&&$row['id_materiel']==$_GET['modif']) {
      ?>
      <form action="?<?php echo($deb);?>&modif=<?php echo($row['id_materiel']);?>" method="post">
        <input type="text" name="num" autofocus />
        <input type="submit" name="valide">
      </form>
      <?php
      if (isset($_POST['valide'])) {
        $num=$_POST['num'];
        $modif=$_GET['modif'];
        mysqli_query($conn,"UPDATE materiel SET Numero_de_serie='$num' WHERE id_materiel='$modif'");
        header("Refresh: 5 ; ?fillter=".$_GET['fillter']."&fil=".$_GET['fil']."");
      }
    }
  }elseif($row['Numero_de_serie']=='NULL'&&$info['Type']=='Gestionnaire'){
      echo('<font color="red"> non enregistré </font>');
  }
  else{
  echo($row['Numero_de_serie']);    
  }
  ?> </td><td <?php echo($color); ?> > <?php
    $date_arrivee=date('d-m-Y à H:i:s',strtotime($row['date_arrivee']));
  echo($date_arrivee);
  ?> </td><td <?php echo($color); ?> > <?php
  echo($row['Date_obsolescence']);
  ?></td></tr><?php
}
?>
</table></center>