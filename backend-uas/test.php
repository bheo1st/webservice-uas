<?php
$path = 'assets/test.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$encode = base64_encode($data);
echo $encode;
?>