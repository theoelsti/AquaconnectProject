<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
include('header.html');

require_once "config.php";
 
$username = $password = "";
$username_err = $password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Entrez votre mot de passe.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password, is_admin FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $is_admin);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                      
                            $_SESSION['admin'] = $is_admin;
                            header("location: welcome.php");
                        } else{
                            $password_err = "Le mot de passe entré n'est pas valide !";
                        }
                    }
                } else{
                    $username_err = "Aucun compte n'a été trouvé avec ce nom d'utilisateur";
                }
            } else{
                echo "Oops! Il y a eu une erreur. Reesayez plus tard.";
            }

            mysqli_stmt_close($stmt);
        }
        // $sql = "SELECT id FROM current_contracts WHERE client_id = ". $_SESSION['id'] . ";";
        
        // if($stmt = mysqli_prepare($link, $sql)){
        //     mysqli_stmt_bind_param($stmt, "s", $param_username);
            
        //     $param_username = $username;
            
        //     if(mysqli_stmt_execute($stmt)){
        //         mysqli_stmt_store_result($stmt);
        //         if(mysqli_stmt_num_rows($stmt) < 0){                    
        //             mysqli_stmt_bind_result($stmt, $id);
        //                 if(mysqli_stmt_fetch($stmt)){
        //                         session_start();
        //                         $_SESSION["loggedin"] = true;
        //                         $_SESSION["id"] = $id;
        //                         $_SESSION["username"] = $username;                      
        //                         $_SESSION['admin'] = $is_admin;
        //                         header("location: welcome.php");
                            
        //                 }
        //         }
        //     }

        //     mysqli_stmt_close($stmt);
        // }








    }
    
    mysqli_close($link);
}
include('login.html');
?>

 