<?php header("Content-type: text/html; charset=utf-8");
$mysqli=mysqli_connect('localhost','root','root','ars');
if(isset($_GET['mail'])){
$Gid_materiel=$_GET['id'];
$req=mysqli_query($mysqli,"SELECT * FROM emprunter WHERE id_materiel='$Gid_materiel' ");
$info_emprunter=mysqli_fetch_assoc($req);
$id_emprunteur=$info_emprunter['id_emprunteur'];
$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE id_materiel='$Gid_materiel' ");
$info_materiel=mysqli_fetch_assoc($req);
$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_emprunteur' ");
$info_emprunteur=mysqli_fetch_assoc($req);
$mail_emprunteur=$info_emprunteur['Mail_emprunteur'];
$materiel=$info_materiel['type_materiel']." de la marque ".$info_materiel['Marque_materiel']." et du modele ".$info_materiel['Modele_materiel']." emprunter le ".$info_emprunter['date_empreint'];
$text=file_get_contents('../Doc/mail.txt');

    $from = "ars.gestionstock@gmail.com";
    $to = "$mail_emprunteur";
    $subject ="ARS gestion de stock";
    $message = '<meta charset="utf-8"/>
        De: ARS gestion de stock<br>
        Sujet: retar de rendu du materiel suivant: '.$materiel.'<br>
        Message: Bonjour '.$info_emprunteur["nom_emprunteur"].' '.$info_emprunteur["prenom_emprunteur"].'<br/> '.$text;

    $email = $from;
    $headers = "" .
        "From:" . $from . "\r\n" .
        "Reply-To:" . $email . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        $file_success = "Votre email a été envoyé avec succès.";
    } else {
        $file_error = "Votre email n'a pas pu être envoyé.";
}
}
if(isset($_GET['allmail'])){

$req="SELECT * FROM emprunter WHERE date_fin<'$datetime' ";
$result = $mysqli-> query($req);
$result->num_rows>0;
while($info_emprunter = $result-> fetch_assoc()){
$id_emprunteur=$info_emprunter['id_emprunteur'];
$id_materiel=$info_emprunter['id_materiel'];
$date_amp=$info_emprunter['date_empreint'];
$req=mysqli_query($mysqli,"SELECT * FROM materiel WHERE id_materiel='$id_materiel' ");
$info_materiel=mysqli_fetch_assoc($req);
$req=mysqli_query($mysqli,"SELECT * FROM emprunteur WHERE id_emprunteur='$id_emprunteur' ");
$info_emprunter=mysqli_fetch_assoc($req);
$mail_emprunteur=$info_emprunter['Mail_emprunteur'];
$materiel=$info_materiel['type_materiel']." de la marque ".$info_materiel['Marque_materiel']." et du modele ".$info_materiel['Modele_materiel']." emprunter le ".$date_amp;
$text=file_get_contents('../Doc/mail.txt');

    $from = "ars.gestionstock@gmail.com";
    $to = "$mail_emprunteur";
    $subject ="ARS gestion de stock";
    $message = '
        De: ARS gestion de stock<br>
        Sujet: retar de rendu du materiel suivant: '.$materiel.'<br>
        Message: '.$text;

    $email = $from;
    $headers = "" .
        "From:" . $from . "\r\n" .
        "Reply-To:" . $email . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        $file_success = "Votre email a été envoyé avec succès.";
    } else {
        $file_error = "Votre email n'a pas pu être envoyé.";
}}
}?>