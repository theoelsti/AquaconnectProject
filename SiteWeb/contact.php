<?php
session_start();

include('header.html');
include('contact.html');
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    echo("<script>document.getElementById('connect_box').innerHTML = '<i data-feather=\"user\"></i> Mon compte'</script>") ;     echo("<script>feather.replace() </script>");

}

?>
