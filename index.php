<?php
session_start();

if(isset($_SESSION['loggedIn'])){
    if($_SESSION['loggedIn'] == '1'){
        header("Location: page/home.php");//if logged in, redirect to home page
    }else{
        header("Location: page/login.php");//redirect to login page
    }
}else{
    header("Location: page/login.php");//redirect to login page
}

?>