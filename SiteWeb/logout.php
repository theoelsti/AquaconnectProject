<?php
session_start(); 
$_SESSION = array();
echo "Vous allez être redirigé...";
session_destroy();
header("location: login.php");
exit;
?>
