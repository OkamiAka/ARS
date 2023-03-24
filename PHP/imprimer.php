<?php
$id='test';
$barcode="<img id='barcode' alt='testing' src='barcode.php?codetype=code128&size=50&text=".$id."&print=true'/>";
$handle = printer_open();
printer_write($handle, $barcode);
printer_close($handle);
?>