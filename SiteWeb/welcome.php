<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){header("location: login.php");exit;}
include('header.html');
include('config.php');
// Check connection
if (mysqli_connect_errno()){echo "Failed to connect to MySQL: " . mysqli_connect_error();}
if(isset($_SESSION['admin'])&& $_SESSION['admin']== 1){
    include('adminMain.html');

    $query = "SELECT id FROM `current_contracts`;";
    $result = mysqli_query($link,$query);
    echo "
    <div id='admin-menu-wrapper'>
        <div id='admin-menu-offer' class='admin-menu-block'> 
            <p class='admin-menu-block-title'>Changer une offre par numéro de contrat </p>
            <div class='admin-menu-block-content'>
                <form method=\"post\" action=\"change-offer.php\">
                    <select onchange=\"checkName();\" name='contract_id' class=\"form-select form-select-lg mb-3 select-name\" aria-label=\".form-select-lg example\">
                        <option value='blank'>ID</option>";

                        while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" .$row['id']."'> ".$row['id'] . "</option>"; 
                        }
                        echo("
                        </select>
  
                        <select name='offer_type' class=\"form-select select-name\" aria-label='multiple select example'>
                        <option value='0'>Fish Starter</option>
                        <option value='1'>Fish Comfort</option>
                        <option value='2'>Fish Elite</option>
                    
                        <input type=\"submit\"  value=\"Modifier\" class=\"btn btn-primary change-offer\">
                        </form>
                    </div>
                    </div>  
                    <form method=\"post\" action=\"change-date.php\">
                        <div class=\"admin-menu-calendar admin-menu-block\">
                        <p class=\"admin-menu-block-title\">Changer la date de fin d'un contrat </p>
                    
                        <div class=\"admin-menu-block-content\">   
                        <select  name='contract_id' class=\"form-select form-select-lg mb-3 select-name\" aria-label=\".form-select-lg example\">
                        <option value='blank'>ID</option>\";
                        
                        ");
                        $query = "SELECT id FROM `current_contracts`;";
                        $result = mysqli_query($link,$query);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" .$row['id']."'> ".$row['id'] . "</option>"; 
                            }
                        include('admin-users-menu.html');

    echo "
        <form method=\"post\" action=\"change-hwid.php\">
            <div class=\"admin-menu-hwid admin-menu-block\">
                <p class=\"admin-menu-block-title\">Changer le numéro materiel d'un client </p>

                    <div class=\"admin-menu-block-content\">
                        <select onchange=\"checkHwid();\" name='contract_id' class=\"form-select form-select-lg mb-3 select-offer\" aria-label=\".form-select-lg example\" id=\"hwidClient\">
                            <option value='blank'>ID</option>\";
                                ";
                            $query = "SELECT id FROM `current_contracts`;";
                            $result = mysqli_query($link,$query);
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<option value='" .$row['id']."'> ".$row['id'] . "</option>"; 
                            } 
    echo" 
                        </select>
                    <input type=\"number\" class='form-control mb-3 select-offer' style=\"width:unset;\" name=\"hwid\" min=\"0\" max=\"100\">
                    
                    <input class=\"btn btn-primary change-hwid\"  type=\"submit\" id='submit-date' value=\"Modifier\">
        </form>
                    </div>
            </div>
    </div>
    <script src=\"assets/scripts/admin-menu.js\"></script>";

    mysqli_close($link);



} else {
    include('userMain.html');
    $query = "SELECT * FROM `current_contracts` WHERE client_id = " . $_SESSION['id'] . ';';

    $result = mysqli_query($link,$query);
    echo '<div class="contracts-container">
        <p id="contracts-title">Offres en cours</p>';
    while ($row = mysqli_fetch_array($result)) {
        echo "<hr><div class='contract-container'><div class='left-container'>";
        switch($row['contract_id']){
                case 0:
                    echo 'Fish Starter';
                    break;
                case 1: 
                    echo 'Fish Comfort';
                    break;
                case 2:
                    echo 'Fish premium';
                    break;
            }
        echo "<i class='bi bi-water'></i>Expire le : ".substr($row['contract_end'], 0,-9)."</br></div>";
        echo '
        <div class="right-container">
            <div class="aquarium-label">Nom : ' . htmlspecialchars($row['label']).'</div>
        <b>#' .  $row['id'] . '</b>, numéro materiel : ' . $row['hwid'] .'
        </div>
        <div class="offer-buttons">
        <a href="#" class="btn btn-dark btn-renew" title="Prolonger mon contrat"><i class="bi bi-wallet"></i></a>
        <a href="./values.php?id='.  $row['hwid'].'" class="btn btn-light btn-renew" title="Consulter les relevés" ><i class="bi bi-bar-chart"></i></a>
        </div>
        
        <hr>
        ';

        echo "
        </div>
        ";
    }
    echo '</div>';
    mysqli_close($link);

}
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    echo("<script>document.getElementById('connect_box').innerHTML = '<i data-feather=\"user\"></i> Mon compte'</script>") ;     
    echo("<script>feather.replace() </script>");
    
}


?>
 