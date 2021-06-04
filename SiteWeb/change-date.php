<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(isset($_SESSION['admin'])&& $_SESSION['admin']== 0){
    header("location: welcome.php");
    exit;
}

include('header.html');
include('config.php');
if(isset($_POST['date'])){

    $date = substr($_POST['date'], -4) . '/' . substr($_POST['date'], 0, -5);
    $regex = "/^\d{4}[\/.]\d{1,2}[\/.]\d{1,2}$/";
    if(preg_match($regex,$date )){
        $query = "UPDATE current_contracts SET `contract_end`='". $date . "' WHERE `id`=". $_POST['contract_id'] .";";
        mysqli_query($link,$query); 
        echo '<div class="alert alert-success" role="alert">La date a été changée en '. $date.'</div>';
        echo '<button type="button" onclick="window.location.href=\'welcome.php\'" class="btn btn-info">Retour</button>';
    }
    else{echo "La date doit etre au format YYYY/MM/DD";}
    


if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    echo("<script>document.getElementById('connect_box').innerHTML = '<i data-feather=\"user\"></i> Mon compte'</script>") ;     
    echo("<script>feather.replace() </script>");
}
    
}
?>