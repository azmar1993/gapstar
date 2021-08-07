<?php
session_start();

session_unset('user');
session_unset('loggedIn');

header("Location: login.php");//redirect to login page
?>