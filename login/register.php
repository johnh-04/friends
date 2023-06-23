<?php 

    ob_start();
    session_start();

    include '../forms/config.php';
    
    if (isset($_COOKIE["login"]) or isset($_SESSION["login"])) header("location: ../user.php");
    
?>

<!DOCTYPE html>
<html lang="it">

    <head>

        <title>Friends | Sign Up</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="../assets/img/favicon.png" rel="icon">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="stylesheet" href="style.css">

        <style>

            body {
                background-color: #efefef;
                /*background-image: url("../assets/img/slide/slide-3.jpg");
                overflow: hidden;
                height: 600px;
                width: 100vw;*/
            }

            .login-wrap {
                background-color: #f7f7f7 !important;
                border: 1px solid #f4f0ec;
                margin-top: -20px;
            }

            input[type=submit], .icon {
                background-color: #00ccff !important;
                border-color: #00b7eb;
                color: #fff;
            }

            input[type=submit]:hover {
                color: #f7f7f7 !important;
            }

            a, .checkbox-wrap {
                color: #00ccff !important;
                font-weight: bold;
            }

            .form-control:focus {
                border-color: #00ccff !important;
            }

            #error {
                color: #ff0000;
                font-weight: bold;
            }

            input[type=password], input[type=text] {
                padding-right: 40px;
            } 

            #togglePassword {
                position: relative;
                cursor: pointer;
            }

            #eyeIcon {
                position: absolute;
                right: 15px;
                margin-left: 10px;
                top: 50%;
                transform: translateY(-50%);
            }

            .fa-eye-slash:before {
                content: "\f070";
            }

            .fa-eye:before {
                content: "\f06e";
            }

        </style>

        <script>

            function msg_error(str, n) {
                
                document.getElementById('icon').classList.remove('fa');
                document.getElementById('icon').classList.remove('fa-user-o');
                document.getElementById('icon').classList.add('bi');
                document.getElementById('icon').classList.add('bi-exclamation-circle');

                document.getElementById('error').innerText += str + '\n';

                switch (n) {

                    case 1: 

                        document.getElementById('password').style.borderColor = '#ff0000';
                        document.getElementById('password1').style.borderColor = '#ff0000';

                    break;

                    case 2:

                        document.getElementById('username').style.borderColor = '#ff0000';
                    
                    break;

                    case 3:

                        document.getElementById('email').style.borderColor = '#ff0000';

                    break;

                    case 4:

                        document.getElementById('username').style.borderColor = '#ff0000';
                        document.getElementById('email').style.borderColor = '#ff0000';
                        document.getElementById('password').style.borderColor = '#ff0000';
                        document.getElementById('password1').style.borderColor = '#ff0000';

                    break;

                }

            }

            function togglePasswordVisibility() {

                var password = document.getElementById('password');
                var password1 = document.getElementById('password1');
                var eyeIcon = document.getElementById('eyeIcon');

                if (password.type == "password") {

                    password.type = 'text';
                    password1.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');

                } else {

                    password.type = 'password';
                    password1.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');

                }

            }

        </script>

    </head>

    <body>

        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-4 col-lg-7">
                        <div class="login-wrap p-4 p-md-5">

                            <div class="icon d-flex align-items-center justify-content-center">
                                <span id="icon" class="fa fa-user-o"></span>
                            </div>

                            <h3 class="text-center mb-4">Sign Up</h3>

                            <form name="login" action="" method="post">

                                <div class="row">

                                    <div class="form-group col">
                                        <input type="text" class="form-control rounded-left" id="username" name="username" placeholder="Username" required>
                                    </div>

                                    <div class="form-group col">
                                        <input type="email" class="form-control rounded-left" id="email" name="email" placeholder="Email" required>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="form-group col">
                                        <input type="text" class="form-control rounded-left" id="name" name="name" placeholder="Name" required>
                                    </div>

                                    <div class="form-group col">
                                        <input type="text" class="form-control rounded-left" id="surname" name="surname" placeholder="Surname" required>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="form-group col">
                                        <input type="date" min="1920-01-01" max="<?=date('Y-m-d', strtotime('-14 years'))?>" class="form-control rounded-left" id="birthdate" name="birthdate" placeholder="Birthdate" required>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="form-group col d-flex">
                                        <input type="password" class="form-control rounded-left" id="password" name="password" placeholder="Password" required>
                                        <span id="togglePassword" onclick="togglePasswordVisibility()">
                                            <i class="fa fa-eye" class="eyeIcon" id="eyeIcon"></i>
                                        </span>
                                    </div>

                                    <div class="form-group col">
                                        <input type="password" class="form-control rounded-left" id="password1" name="password1" placeholder="Confirm password" required>
                                    </div>

                                </div>

                                <div class="form-group text-center" id="error"></div>

                                <div class="form-group">
                                    <input type="submit" class="form-control btn rounded submit px-3" name="invia" value="Sign Up"></input>
                                </div>

                                <div class="form-group d-md-flex">

                                    <div class="w-50">
                                        <label class="checkbox-wrap">Remember me
                                            <input type="checkbox" id="check" name="check" value="false">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <!--<div class="w-50 text-md-right">
                                        <a href="#">Password dimenticata</a>
                                    </div>-->
                                    
                                </div>

                                <div class="form-group mt-4">
                                    <p class="text-center">Already have an account? <a href="login.php" id="login">Sign In</a></p>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php

            if (isset($_POST["invia"])) {

                $invia = $_POST["invia"];
                $username = addslashes(strtolower($_POST["username"]));
                $email = addslashes($_POST["email"]);
                $name = addslashes($_POST["name"]);
                $surname = addslashes($_POST["surname"]);
                $birthdate = $_POST["birthdate"];
                $password = $_POST["password"];
                $password1 = $_POST["password1"];
                $now = date('Y-m-d');

                if ($password === $password1) {

                    $sql = "SELECT * FROM users WHERE (Username = '$username')";
                    $res = $conn->query($sql);

                    if ($res->num_rows == 0) {

                        $sql = "SELECT * FROM users WHERE (Email = '$email')";
                        $res = $conn->query($sql);

                        if ($res->num_rows == 0) {

                            //controllo mail
                            //chars pass

                            if (strlen($username) >= 3 and strlen($username) <= 15) {

                                if (strlen($password) >= 7 and strlen($password) <= 25) {

                                    if (!(!preg_match("/[\"'£%#-§ùç*\()\[\]{}<>ò^ì+\/\|=]/", $password) and !preg_match("/[\"'£%#-§ùç*\()\[\]{}<>ò^ì+\/\|!?=]/", $username))) {
                                        
                                        $password = md5($password);

                                        $sql = "INSERT INTO users (Username, Email, Password, Name, Surname, BirthDate, MemDate) values ('$username', '$email', '$password', '$name', '$surname', '$birthdate', '$now')";
                                        $res = $conn->query($sql);
                                        
                                        if ($res) {

                                            if (isset($_POST["check"])) {

                                                $cookie = base64_encode(json_encode(array("username" => $_POST["username"], "password" => $password)));

                                                setcookie("user", $cookie, time() + 2592000, "/friends"); //30 days
                                                //setcookie("password", $password, time() + 2592000, "/friends"); //30 days
                                                setcookie("login", 1, time() + 2592000, "/friends"); //30 days

                                            } else {

                                                //session_start();
                                                $_SESSION["login"] = 1;
                                                $_SESSION["username"] = $username;
                                                $_SESSION["password"] = $password;

                                            }

                                            header("location: ../user.php");
                                                
                                        } else echo("<script>msg_error('Error', 1)</script>");
                                    
                                    } else echo("<script>msg_error('Special characters not allowed', 4)</script>");

                                } else echo("<script>msg_error('The password must contain between 7 and 25 characters', 1)</script>");

                            } else echo("<script>msg_error('The username must contain between 3 and 15 characters', 2)</script>");

                        } else echo("<script>msg_error('Email already exists', 3);</script>");

                    } else echo("<script>msg_error('Username already exists', 2);</script>");

                } else echo("<script>msg_error('Passwords do not match', 1);</script>");
                    
                $conn->close();

            }

        ?>

        <script> if (window.history.replaceState) window.history.replaceState(null, null, window.location.href); </script> 

        <?php ob_end_flush(); ?>

    </body>

</html>