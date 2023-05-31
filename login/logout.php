<?php

    ob_start();
    session_start();
    session_destroy();

    if (isset($_COOKIE["login"])) {

        setcookie("login", "", time() - 2592000, "/friends");
        setcookie("username", "", time() - 2592000, "/friends");
        if (isset($_COOKIE["email"])) setcookie("email", "", time() - 2592000, "/friends");
        setcookie("password", "", time() - 2592000, "/friends");

    }

    ob_end_flush();

    header("location: ../");

?>