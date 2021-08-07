<?php
require '../headers/header.php';

$user_name = '';
$login_password = '';
$response_message = '';

//submitted to same page to check login, checking whether the form is submitted
if(isset($_POST['login_username'])){
    $user_name = $_POST['login_username'];
    $login_password = $_POST['login_password'];

    require '../query_models/DBQueries.php';

    $queries = new DBQueries();

    $result = $queries->checkLogin($user_name,$login_password);

    if($result['res'] == '0'){
        $response_message = '<div class="alert alert-primary" role="alert">'.$result['desc'].'</div>';
        header("Location: home.php");
    }else{
        $response_message = '<div class="alert alert-danger" role="alert">'.$result['desc'].'</div>';
    }
}

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>
<!--below style only for login page-->
<link href="../assets/styles.css" rel="stylesheet" >

<div class="container h-100">
    <div class="d-flex justify-content-center h-100">
        <div class="user_card">
            <div class="row">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="../images/user-icon.png" class="brand_logo" alt="Logo">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="d-flex justify-content-center form_container">
                    <form action="<?= $actual_link ?>" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="<?= $user_name ?>" placeholder="username" name="login_username" required>
                        </div>
                        <div class="input-group mb-2">
                            <input type="password" class="form-control " value="<?= $login_password ?>" placeholder="password" name="login_password" required>
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <button type="submit" name="button" class="btn login_btn">Login</button>
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <?= $response_message ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>