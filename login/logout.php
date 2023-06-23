<?php

    ob_start();
    session_start();
    session_destroy();

    if (isset($_COOKIE["login"])) {

        setcookie("login", "", time() - 2592000, "/friends");
        setcookie("user", "", time() - 2592000, "/friends");

    }

    ob_end_flush();

    header("location: login.php");

?>