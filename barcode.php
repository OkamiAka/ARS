<?PHP
  $barcodeBase64 =  strip_tags($_POST[' barcodeBase64 ']);
  list($type, $image) = explodes(',', $barcodeBase64);
  file_put_contents(' barcode.png ', base64_decode($image));
  echo ' Save Image to disk!';
?>