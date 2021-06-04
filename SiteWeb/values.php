<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$regex = "/[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]/";

function writeJsonLast(){    
    include('config.php');
    checkAuth();
    $sql="select * from results where hwid=".$_GET['id']." ORDER BY datetag DESC limit 20"; 
    $response = array();
    $posts = array();
    $result=mysqli_query($link,$sql);
    while($row=mysqli_fetch_array($result)) { 
        $temp=$row['temp']; 
        $ph=$row['ph']; 
        $lum=$row['lum'];
        $datetag=$row['datetag'];
        $posts[] = array('datetag'=>$datetag,'temp'=> $temp, 'ph'=> $ph, 'lum'=>$lum);
    } 

    $response['results'] = $posts;
    $fp = fopen('results.json', 'w');
    fwrite($fp, json_encode($posts));
    fclose($fp);
    addValues();

}
function writeJson(){
    include('config.php');
    checkAuth();
    $sql = "select * from results where datetag >='" . $_GET['dates']. "' and datetag <='".$_GET['datee']."' and hwid=".$_GET['id']." ORDER BY datetag DESC;" ;
    $response = array();
    $posts = array();
    $result=mysqli_query($link,$sql);
    while($row=mysqli_fetch_array($result)) { 
        $temp=$row['temp']; 
        $ph=$row['ph']; 
        $lum=$row['lum'];
        $datetag=$row['datetag'];
        $posts[] = array('datetag'=>$datetag,'temp'=> $temp, 'ph'=> $ph, 'lum'=>$lum);
    } 
    $response['results'] = $posts;
    $fp = fopen('results.json', 'w');
    fwrite($fp, json_encode($posts));
    fclose($fp);
    addValues();
}
if(isset($_GET['dates']) && $_GET['datee']){
    $datee;
    $dates;
    if($_GET['dates'][6] == '-'){
        $dates = substr_replace($_GET['dates'], '0',5, 0 );
    }
    if($_GET['datee'][6] == '-'){
        $datee = substr_replace($_GET['datee'], '0',5, 0 );
    }
    if(preg_match($regex, $datee) && preg_match($regex, $dates)){
        writeJson();
        
    }
    else{
    writeJsonLast();
    
    }
   
}
else{writeJsonLast();}
function checkAuth(){
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    $sql = "select hwid from current_contracts where client_id=" . $_SESSION['id'] .";" ;
    $response = array();
    $posts = array();
    $result=mysqli_query($link,$sql);
    $authorized = FALSE;
    while($row=mysqli_fetch_array($result)) {
        if($_GET['id'] == $row['hwid']){
            $authorized = TRUE;
            return;
        } 
    }
    if(!$authorized){
        header('location: welcome.php');
        exit;
    }
}
function addValues(){
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    if(isset($_GET["temp"]) && isset($_GET["lum"])){
        if(is_numeric($_GET["temp"]) && is_numeric($_GET["lum"])){
            $sql = "INSERT INTO `changeValues` (`id`, `hwid`, `lum`, `temp`) VALUES (NULL,{$_GET['id']},{$_GET["temp"]},{$_GET["lum"]});" ;
            mysqli_query($link,$sql);
        }
        else if(is_numeric($_GET['temp'])){
            $sql = "INSERT INTO `changeValues` (`id`, `hwid`, `lum`, `temp`) VALUES (NULL,{$_GET['id']},0,{$_GET['temp']});" ;
            mysqli_query($link,$sql);
        }
        else if(is_numeric($_GET['lum'])){
            $sql = "INSERT INTO `changeValues` (`id`, `hwid`, `lum`, `temp`) VALUES (NULL,{$_GET['id']},{$_GET['lum']},0);" ;
            mysqli_query($link,$sql);
        }
    }
}
function lastValues(){
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $sql="select * from results where hwid=".$_GET['id']." ORDER BY datetag DESC limit 1"; 
    $response = array();
    $posts = array();
    $result=mysqli_query($link,$sql);
    while($row=mysqli_fetch_array($result)) { 
        $temp=$row['temp']; 
        $ph=$row['ph']; 
        $lum=$row['lum'];
        $datetag=$row['datetag'];
        $posts[] = array('datetag'=>$datetag,'temp'=> $temp, 'ph'=> $ph, 'lum'=>$lum);
    } 
    $response['results'] = $posts;
    $fp = fopen('lastResults.json', 'w');
    fwrite($fp, json_encode($posts));
    fclose($fp);
}
lastValues();
include('header.html');
include('values.html');
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){echo("<script>document.getElementById('connect_box').innerHTML = '<i data-feather=\"user\"></i> Mon compte'</script>");echo("<script>feather.replace() </script>");}

?>
<script type="text/javascript" src="results.json"></script>
<script type="text/javascript" src="lastResults.json"></script>

<script src='./assets/scripts/chart.js'></script>