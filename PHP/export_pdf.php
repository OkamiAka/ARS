<?php
date_default_timezone_set('Europe/Paris');
use Mpdf\Mpdf;
require_once __DIR__ . '/../vendor/autoload.php';
if(isset($_GET['emprunter'])){
$mysqli=mysqli_connect('localhost','root','root','ars');
$mpdf = new \Mpdf\Mpdf();

$mpdf->SetTitle("Données stocke");
$mpdf->SetAuthor("CAQUEUX Nils");
$mpdf->SetCreator("Code Box");
$mpdf->SetSubject("Demo");
$mpdf->SetKeywords("Demo", "Testing");
$datetime="Export fait le ".date('d-m-Y à H:i:s');
$html = $datetime."<h1>Liste de materiel emprunter</h1>";
	

$sql="SELECT * FROM materiel WHERE statut_materiel='emprunter' ";
$result = $mysqli-> query($sql);
$i='0';
$html=$html.'<table id="tr"><tr id="tr">';
if($result->num_rows>0)
{
	while($row = $result-> fetch_assoc())
	{
		if ($i>='1') {
			$i='1';
			$html=$html.'</tr><tr id="tr"><td id="tr">';
		}
		else{
			$i++;
			$html=$html.'<td id="tr">';
		}
		$id_materiel=$row["id_materiel"];
		$req=mysqli_query($mysqli,"SELECT * FROM emprunter WHERE id_materiel='$id_materiel' ");
		$emprunter=mysqli_fetch_assoc($req);
		$id_empreinteur=$emprunter['id_emprunteur'];
		$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_empreinteur' ");
		$emprunteur=mysqli_fetch_assoc($req);
		$html=$html.'<hr/><table id="stat"><tr><td>';
		$html=$html."Materiel emprunter<hr/>id: ".$row["id_materiel"]."<br/>"." type materiel: ".$row["type_materiel"]."<br/>"." Marque: ".$row["Marque_materiel"]."<br/>"." Modele: ".$row["Modele_materiel"]."<br/>"." Numero de serie: ".$row["Numero_de_serie"];
		$html=$html."</td><td>";
		$numero_telephone_emprunteur=$emprunteur["numero_telephone_emprunteur"];
		$html=$html."Emprunteur<hr/>Nom: ".$emprunteur["nom_emprunteur"]."<br/>"." Prenom: ".$emprunteur["prenom_emprunteur"]."<br/>"." Numero de bureau: N°".$emprunteur["numero_bureau_emprunteur"]."<br/>"." Numero de telephone: ".$numero_telephone_emprunteur;
		$html=$html."</td></tr><tr><td>";
				$html=$html.'emprunter le '.$emprunter['date_empreint'];
		$html=$html."</td><td>";
			$html=$html.'a rendre pour le '.$emprunter['date_fin'];
			$html=$html."</td></tr></table></td>";
	}
		$html=$html."</tr>";
}

$html=$html."</table><hr/>";
$mpdf->WriteHTML($html);

$mpdf->Output();
}




if(isset($_GET['retard'])){
$mysqli=mysqli_connect('localhost','root','root','ars');
$mpdf = new \Mpdf\Mpdf();

$mpdf->SetTitle("Données stocke");
$mpdf->SetAuthor("CAQUEUX Nils");
$mpdf->SetCreator("Code Box");
$mpdf->SetSubject("Demo");
$mpdf->SetKeywords("Demo", "Testing");
$date=date('Y-m-d H:i:s');
$datetime="Export fait le ".date('d-m-Y à H:i:s');
$html = $datetime."<h1>Liste de materiel non rendu</h1>";
	

$sql="SELECT * FROM emprunter WHERE date_fin<'$date' ";
$result = $mysqli-> query($sql);
$i='0';
$html=$html.'<table id="tr"><tr id="tr">';
if($result->num_rows>0)
{
	while($row = $result-> fetch_assoc())
	{
		if ($i>='1') {
			$i='1';
			$html=$html.'</tr><tr id="tr"><td id="tr">';
		}
		else{
			$i++;
			$html=$html.'<td id="tr">';
		}
		$id_materiel=$row["id_materiel"];
		$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE id_materiel='$id_materiel' ");
		$emprunter=mysqli_fetch_assoc($req);
		$id_empreinteur=$row['id_emprunteur'];
		$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_empreinteur' ");
		$emprunteur=mysqli_fetch_assoc($req);
		$html=$html.'<hr/><table id="stat"><tr><td>';
		$html=$html."Materiel emprunter<hr/>id: ".$emprunter["id_materiel"]."<br/>"." type materiel: ".$emprunter["type_materiel"]."<br/>"." Marque: ".$emprunter["Marque_materiel"]."<br/>"." Modele: ".$emprunter["Modele_materiel"]."<br/>"." Numero de serie: ".$emprunter["Numero_de_serie"];
		$html=$html."</td><td>";
		$numero_telephone_emprunteur=$emprunteur["numero_telephone_emprunteur"];
		$html=$html."Emprunteur<hr/>Nom: ".$emprunteur["nom_emprunteur"]."<br/>"." Prenom: ".$emprunteur["prenom_emprunteur"]."<br/>"." Numero de bureau: N°".$emprunteur["numero_bureau_emprunteur"]."<br/>"." Numero de telephone: ".$numero_telephone_emprunteur;
		$html=$html."</td></tr><tr><td>";
				$html=$html.'emprunter le '.$row['date_empreint'];
		$html=$html."</td><td>";
			$html=$html.'a rendre pour le '.$row['date_fin'];
			$html=$html."</td></tr></table></td>";
	}
		$html=$html."</tr>";
}

$html=$html."</table><hr/>";
$mpdf->WriteHTML($html);

$mpdf->Output();
}



if(isset($_GET['reserver'])){
$mysqli=mysqli_connect('localhost','root','root','ars');
$mpdf = new \Mpdf\Mpdf();

$mpdf->SetTitle("Données stocke");
$mpdf->SetAuthor("CAQUEUX Nils");
$mpdf->SetCreator("Code Box");
$mpdf->SetSubject("Demo");
$mpdf->SetKeywords("Demo", "Testing");
$date=date('Y-m-d H:i:s');
$datetime="Export fait le ".date('d-m-Y à H:i:s');
$html = $datetime."<h1>Liste de materiel reserver</h1>";
	

$sql="SELECT * FROM materiel WHERE statut_materiel='reserver' ";
$result = $mysqli-> query($sql);
$i='0';
$html=$html.'<table id="tr"><tr id="tr">';
if($result->num_rows>0)
{
	while($row = $result-> fetch_assoc())
	{
		if ($i>='1') {
			$i='1';
			$html=$html.'</tr><tr id="tr"><td id="tr">';
		}
		else{
			$i++;
			$html=$html.'<td id="tr">';
		}
		$id_materiel=$row["id_materiel"];
		$req=mysqli_query($mysqli,"SELECT * FROM reserver WHERE id_materiel='$id_materiel' ");
		$reserver=mysqli_fetch_assoc($req);
		$id_empreinteur=$reserver['id_emprunteur'];
		$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_empreinteur' ");
		$emprunteur=mysqli_fetch_assoc($req);
		$html=$html.'<hr/><table id="stat"><tr><td>';
		$html=$html."Materiel reserver<hr/>id: ".$row["id_materiel"]."<br/>"." type materiel: ".$row["type_materiel"]."<br/>"." Marque: ".$row["Marque_materiel"]."<br/>"." Modele: ".$row["Modele_materiel"]."<br/>"." Numero de serie: ".$row["Numero_de_serie"];
		$html=$html."</td><td>";
		$numero_telephone_emprunteur=$emprunteur["numero_telephone_emprunteur"];
		$date_reservation=date('d-m-Y à H:i:s',strtotime($reserver["date_reservation"]));
		$html=$html."Demande effectuée le ".$date_reservation." <br/>par:<hr/>Nom: ".$emprunteur["nom_emprunteur"]."<br/>"." Prenom: ".$emprunteur["prenom_emprunteur"]."<br/>"." Numero de bureau: N°".$emprunteur["numero_bureau_emprunteur"]."<br/>"." Numero de telephone: ".$numero_telephone_emprunteur;
		$html=$html."</td></tr><tr><td>";
				$html=$html.'reserver le '.$reserver['date_reservation'];
		$html=$html."</td><td>";
			$html=$html.'a rendre pour le '.$reserver['fin_reservation'];
			$html=$html."</td></tr></table></td>";
	}
		$html=$html."</tr>";
}

$html=$html."</table><hr/>";
$mpdf->WriteHTML($html);

$mpdf->Output();
}



if (isset($_GET['export_pdf'])) {
	$mysqli=mysqli_connect('localhost','root','root','ars');
$mpdf = new \Mpdf\Mpdf();

$mpdf->SetTitle("Données stocke");
$mpdf->SetAuthor("CAQUEUX Nils");
$mpdf->SetCreator("Code Box");
$mpdf->SetSubject("Demo");
$mpdf->SetKeywords("Demo", "Testing");
$date=date('Y-m-d H:i:s');
$fillter=$_GET['fillter'];
 if (isset($_GET['fil'])) {
$fil=$_GET['fil'];
  $res=$fillter."='".$fil."'";
  $deb='fillter='.$_GET['fillter'].'&fil='.$_GET['fil'];
}else{
$res=$fillter;
  $deb='fillter='.$_GET['fillter'];
}
$datetime="Export fait le ".date('d-m-Y à H:i:s');
$html = $datetime."<h1>Liste de materiel ".$res."</h1>";

$html=$html.'<center><table style="background-color: #acacac;"><tr style="background-color: #acacac;"><td style="background-color: black; color: white;">id_materiel</td><td style="background-color: black; color: white;">statut_materiel</td><td style="background-color: black; color: white;">type_materiel</td><td style="background-color: black; color: white;">Marque_materiel</td><td style="background-color: black; color: white;">Modele_materiel</td><td style="background-color: black; color: white;">Numero_de_serie</td><td style="background-color: black; color: white;">date_arrivee</td><td style="background-color: black; color: white;">Date_obsolescence</td></tr>';

$sql="SELECT * FROM materiel WHERE $res ORDER BY id_materiel desc";
$result = $mysqli-> query($sql);
$result->num_rows>0;
while($row = $result-> fetch_assoc())
{
	$number = $row['id_materiel'];
    if($number % 2 == 0){
        $color='#FFFFFF';
    }
    else{
        $color='#acacac';
    }
  $html=$html.'<tr style="background-color: '.$color.'; border: 1px solid black; border-collapse: collapse;">
  				<td style="background-color: '.$color.';">';
  $html=$html.$row['id_materiel'];
  $html=$html.'</td><td style="background-color: '.$color.';">';
  $html=$html.$row['statut_materiel'];
  $html=$html.'</td><td style="background-color: '.$color.';">';
  $html=$html.$row['type_materiel'];
  $html=$html.'</td><td style="background-color: '.$color.';">';
  $html=$html.$row['Marque_materiel'];
  $html=$html.'</td><td style="background-color: '.$color.';">';
  $html=$html.$row['Modele_materiel'];
  $html=$html.'</td><td style="background-color: '.$color.';">';
  if($row['Numero_de_serie']=='NULL'){
      $html=$html.'<font color="red"> non enregistrer </font>';
  }
  else{
  $html=$html.$row['Numero_de_serie'];    
  }
  $html=$html.'</td><td style="background-color: '.$color.';">';
    $date_arrivee=date('d-m-Y à H:i:s',strtotime($row['date_arrivee']));
  $html=$html.$date_arrivee;
  $html=$html.'</td><td style="background-color: '.$color.';">';
  $html=$html.$row['Date_obsolescence'];
  $html=$html.'</td></tr>';
}
$html=$html."</table></center>";
$mpdf->WriteHTML($html);
$mpdf->Output();
}
?>