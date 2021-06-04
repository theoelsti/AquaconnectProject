<?php
include('header.html');
require_once "config.php";
session_start();
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Merci d'entrer un nom d'utilisateur.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if(mysqli_stmt_execute($stmt)){
                
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Ce nom d'utilisateur n'est pas disponible !";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Uh oh...! Quelque chose s'est mal passé ! Merci de réésayer.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Merci d'entrer un mot de passe.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Le mot de passe doit au moins contenir 6 caractères.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Merci de confirmer votre mot de passe.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Les mots de passe ne corréspondent pas.";
        }
    }
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            
            if(mysqli_stmt_execute($stmt)){
                header("location: login.php");
            } else{
                echo "Oh oh...Quelque chose ne s'est pas passé comme prévu ! Merci de réésayer.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($link);
}
include('register.html')
?>
 