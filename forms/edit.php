<?php

    ob_start();
    session_start();

    include 'config.php';

    if ((!isset($_SESSION["login"]))) header("location: ../login/login.php");

    $idUser = $_POST["idUser"];

    if (isset($_FILES['img'])) {

        $img = $_FILES["img"]["tmp_name"];

        $targetDir = "../assets/img/profile/"; 
        $targetFile = $targetDir . "profile$idUser" . ".png";
        
        move_uploaded_file($img, $targetFile);

        $targetFile = substr($targetFile, 3);

        $sql = "UPDATE users SET Avatar = '$targetFile' WHERE (IdUser = '$idUser')";
        $res = $conn->query($sql);

        echo $targetFile;

    }

?>

<?php ob_end_flush(); ?>