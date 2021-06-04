<?php
   include('header.html');
   include('config.php');
   session_start();
   $option = isset($_POST['offer_type']) ? $_POST['offer_type'] : false;
   if(!(isset($_POST['offer_type'])&& isset($_POST['contract_id']))){
      header("location: login.php");
      exit;
   }
   
   if(isset($_SESSION['admin'])&& $_SESSION['admin']== 0){
      header("location: welcome.php");
      exit;
  }
   if (isset($option)) {
      $query = "UPDATE current_contracts SET `contract_id`='". $_POST['offer_type'] . "' WHERE `id`=" . $_POST['contract_id'] . ";";
      mysqli_query($link,$query);
      
      echo '<div class="alert alert-success" role="alert">Contrat n° '. $_POST['contract_id'] .' modifié avec succès</div>';
      echo '<button type="button" onclick="window.location.href=\'welcome.php\'" class="btn btn-info">Retour à l\'accueil</button>';
      mysqli_close($link);
   }
   if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      echo("<script>document.getElementById('connect_box').innerHTML = '<i data-feather=\"user\"></i> Mon compte'</script>") ;     
      echo("<script>feather.replace() </script>");
   
  }
  
?>