<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(!$_SESSION['admin']){
    header("location: welcome.php");
    exit;
}
include('header.html');
include('config.php');

if(isset($_POST['contract_id']) && isset($_POST['hwid'])){

    $query = "UPDATE current_contracts SET hwid=" . $_POST['hwid'] . " WHERE id=".$_POST['contract_id'].";";
    mysqli_query($link,$query);
    echo '<div class="alert alert-success" role="alert">Contrat n° '. $_POST['contract_id'] .' est désormais relié au numéro materiel '. $_POST['hwid']. '</div>';
    echo '<button type="button" onclick="window.location.href=\'welcome.php\'" class="btn btn-info">Retour à l\'accueil</button>';
}
else{
    header("location: welcome.php");
    exit;
}


if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    echo("<script>document.getElementById('connect_box').innerHTML = '<i data-feather=\"user\"></i> Mon compte'</script>") ;     
    echo("<script>feather.replace() </script>");
}
    

?>