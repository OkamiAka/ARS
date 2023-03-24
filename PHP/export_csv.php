<?php  
$fillter=$_GET['fillter'];
 if (isset($_GET['fil'])) {
$fil=$_GET['fil'];
  $res=$fillter."='".$fil."'";
  $deb='fillter='.$_GET['fillter'].'&fil='.$_GET['fil'];
}else{
$res=$fillter;
  $deb='fillter='.$_GET['fillter'];
}      //exportcsv.php  
 if(isset($_GET["export_csv"]))  
 {  
      $connect = mysqli_connect("localhost", "root", "root", "ars");  
      header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=materiel.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array("id materiel, statut materiel, type materiel, Marque materiel, Numero de serie, date d'arrivée, Data obsolescence"));  
      $query = "SELECT * FROM materiel WHERE $res ORDER by date_arrivee DESC";  
      $result = mysqli_query($connect, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  
 }?> 