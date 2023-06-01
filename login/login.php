<?php 

    ob_start();
    session_start();

    include '../forms/config.php';
    
    if (isset($_COOKIE["login"]) or isset($_SESSION["login"])) header("location: ../user.php");
    
?>

<!DOCTYPE html>
<html lang="it">

    <head>

        <title>Sign In</title>
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
                background-color: #ffe7de;
                /*background-image: url("../assets/img/slide/slide-3.jpg");
                overflow: hidden;
                height: 600px;
                width: 100vw;*/
            }

            .login-wrap {
                background-color: #ffbaba !important;
                border: 1px solid #ffabab;
                margin-top: -20px;
            }

            input[type=submit], .icon {
                background-color: #dc3545 !important;
                color: #fff;
            }

            input[type=submit]:hover {
                color: #eaedf6 !important;
            }

            a, .checkbox-wrap {
                color: #dc3545 !important;
                font-weight: bold;
            }

            .form-control:focus {
                border-color: #dc3545 !important;
            }

            #error {
                color: #ff0000;
                font-weight: bold;
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
                                    <!--<input type="checkbox" onclick="alert('u')" style="border-color: white;"><i class="bi bi-eye" id="togglePassword" style="margin-left: -30px; margin-top: 13px; cursor: pointer;"></i>-->
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

                                    <!--<div class="w-50 text-md-right">
                                        <a href="#">Password dimenticata</a>
                                    </div>-->
                                    
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
                $username = $_POST["username"];
                $password = $_POST["password"];

                $password = md5($password);

                $sql = "SELECT * FROM users WHERE (Username = '$username' and Password = '$password')";
                $res = $conn->query($sql);
            
                if ($res->num_rows == 1) {

                    $row = $res->fetch_assoc();

                    if (isset($_POST["check"])) {

                        setcookie("username", $_POST["username"], time() + 2592000, "/friends"); //30 days
                        setcookie("password", $password, time() + 2592000, "/friends"); //30 days
                        setcookie("login", 1, time() + 2592000, "/friends"); //30 days

                    } else {

                        //session_start();
                        $_SESSION["login"] = 1;
                        $_SESSION["username"] = $username;
                        $_SESSION["password"] = $password;

                    }

                    header("location: ../user.php");
                    
                } else {
                    
                    echo("<script>error();</script>");

                }
                
                $conn->close();

            }

        ?>

        <script> if (window.history.replaceState) window.history.replaceState(null, null, window.location.href); </script> 

        <?php ob_end_flush(); ?>

    </body>

</html>