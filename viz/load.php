<?php


$filename = "http://dod.setup.nl/feed/votes.php";
$handle = fopen($filename, "r");
$contents = fread($handle, 10000000);
fclose($handle);

?>

