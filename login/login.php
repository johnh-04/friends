<?php 

    ob_start();
    session_start();

    include '../forms/config.php';
    
    if (isset($_COOKIE["login"]) or isset($_SESSION["login"])) header("location: ../");
    
?>

<!DOCTYPE html>
<html lang="it">

    <head>

        <title>Friends | Sign In</title>
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

            function error() {

                document.getElementById('icon').classList.remove('fa');
                document.getElementById('icon').classList.remove('fa-user-o');
                document.getElementById('icon').classList.add('bi');
                document.getElementById('icon').classList.add('bi-exclamation-circle');

                document.getElementById('error').innerText = 'Wrong Credentials';
                document.getElementById('username').style.borderColor = '#ff0000';
                document.getElementById('password').style.borderColor = '#ff0000';

            }

            function togglePasswordVisibility() {

                var password = document.getElementById("password");
                var eyeIcon = document.getElementById("eyeIcon");

                if (password.type == "password") {

                    password.type = "text";
                    eyeIcon.classList.remove("fa-eye");
                    eyeIcon.classList.add("fa-eye-slash");

                } else {

                    password.type = "password";
                    eyeIcon.classList.remove("fa-eye-slash");
                    eyeIcon.classList.add("fa-eye");

                }

            }

        </script>

    </head>

    <body>

        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="login-wrap p-4 p-md-5">

                            <div class="icon d-flex align-items-center justify-content-center">
                                <span id="icon" class="fa fa-user-o"></span>
                            </div>

                            <h3 class="text-center mb-4">Sign In</h3>

                            <form name="login" action="" method="post">

                                <div class="form-group">
                                    <input type="text" class="form-control rounded-left" id="username" name="username" placeholder="Username" required>
                                </div>

                                <div class="form-group d-flex">
                                    <input type="password" class="form-control rounded-left" id="password" name="password" placeholder="Password" required>
                                    <span id="togglePassword" onclick="togglePasswordVisibility()">
                                        <i class="fa fa-eye" class="eyeIcon" id="eyeIcon"></i>
                                    </span>
                                </div>

                                <div class="form-group text-center" id="error"></div>

                                <div class="form-group">
                                    <input type="submit" class="form-control btn rounded submit px-3" name="invia" value="Sign In"></input>
                                </div>

                                <div class="form-group d-md-flex">

                                    <div class="w-50">
                                        <label class="checkbox-wrap">Remember me
                                            <input type="checkbox" id="check" name="check" value="false">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                </div>

                                <div class="form-group mt-4">
                                    <p class="text-center">Don't have an account yet? <a href="register.php" id="register">Sign Up</a></p>
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
                $password = addslashes($_POST["password"]);

                if (!(!preg_match("/[\"'£%#-§ùç*\()\[\]{}<>ò^ì+\/\|=]/", $password) and !preg_match("/[\"'£%#-§ùç*\()\[\]{}<>ò^ì+\/\|!?=]/", $username))) {

                    $password = md5($password);

                    $sql = "SELECT * FROM users WHERE (Username = '$username' and Password = '$password')";
                    $res = $conn->query($sql);
                
                    if ($res->num_rows == 1) {

                        $row = $res->fetch_assoc();

                        if (isset($_POST["check"])) {

                            $cookie = base64_encode(json_encode(array("username" => $_POST["username"], "password" => $password)));

                            setcookie("user", $cookie, time() + 2592000, "/friends"); //30 days
                            setcookie("login", 1, time() + 2592000, "/friends"); //30 days

                        } else {

                            $_SESSION["login"] = 1;
                            $_SESSION["username"] = $username;
                            $_SESSION["password"] = $password;

                        }

                        header("location: ../");
                        
                    } else echo("<script>error();</script>");
                    
                    $conn->close();

                } else echo("<script>error()</script>");

            }

        ?>

        <script> if (window.history.replaceState) window.history.replaceState(null, null, window.location.href); </script> 

        <?php ob_end_flush(); ?>

    </body>

</html>