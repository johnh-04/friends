<?php

    ob_start();
    session_start();

    include 'config.php';

    if ((!isset($_SESSION["login"]))) header("location: ../login/login.php");

    $idUser = $_POST["idUser"];

    $sql = "SELECT * FROM users WHERE (IdUser = '$idUser')";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $old_picture = substr($row["Avatar"], 19); // remove the targetDir

    if (isset($_FILES["img"])) {

        $img = $_FILES["img"]["tmp_name"];

        $targetDir = "../assets/img/profile/";
        $targetFile = $targetDir . str_pad(random_int(2**28, 2**33), 10, '0', STR_PAD_LEFT) . "." . explode("/", $_FILES["img"]["type"])[1];
        
        move_uploaded_file($img, $targetFile);
        try {
            unlink($targetDir . $old_picture);
        } catch(Exception) {}

        $targetFile = substr($targetFile, 3);

        $sql = "UPDATE users SET Avatar = '$targetFile' WHERE (IdUser = '$idUser')";
        $res = $conn->query($sql);

        echo $targetFile;

    }

    ob_end_flush(); 
 
?>