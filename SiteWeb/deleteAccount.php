<?php
session_start();
include('config.php');
include("header.html");
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
    $query = "DELETE FROM users WHERE id=" . $_SESSION['id'].";";
    mysqli_query($link,$query); 
    $query = "SELECT * FROM current_contracts WHERE client_id=" . $_SESSION['id'].";";
    $result = mysqli_query($link,$query); 
    while ($row = mysqli_fetch_array($result)) {
            $query = "DELETE FROM results WHERE hwid=".$row['hwid'].";";
            mysqli_query($link,$query); 
            $query = "DELETE FROM current_contracts WHERE client_id=".$_SESSION['id'].";";
            mysqli_query($link,$query); 
        }
        session_destroy();
?>
<div class="alert alert-success" role="alert">
 Votre compte a bien été supprimé. 
</div>