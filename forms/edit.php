<?php

    ob_start();
    session_start();

    include 'config.php';

    if ((!isset($_SESSION["login"]))) header("location: ../login/login.php");

    $idUser = $_POST["idUser"];

    $sql = "SELECT * FROM users WHERE (IdUser = '$idUser')";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $file = substr($row["Avatar"], 19);

    if ($file == "default.jpg" or intval(substr($file, 26, 1)) == 1) $id = 0;
    else $id = 1; //non funziona. si ferma all'incremento di 1

    if (isset($_FILES["img"])) {

        $img = $_FILES["img"]["tmp_name"];

        $targetDir = "../assets/img/profile/"; 
        $targetFile = $targetDir . "profile" . "$id" . "$idUser" . ".png";
        
        move_uploaded_file($img, $targetFile);

        $targetFile = substr($targetFile, 3);

        $sql = "UPDATE users SET Avatar = '$targetFile' WHERE (IdUser = '$idUser')";
        $res = $conn->query($sql);

        echo $targetFile;

    }

    ob_end_flush(); 
 
?>