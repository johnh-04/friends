<?php

    ob_start();
    session_start();

    include 'config.php';

    if ((!isset($_SESSION["login"]))) header("location: ../login/login.php");

    $idUser = $_POST["idUser"];
    $idFriend = $_POST["idFriend"];

    $sql = "INSERT INTO friends (IdFriend1, IdFriend2) VALUES ('$idUser', '$idFriend')";
    $res = $conn->query($sql);

    ob_end_flush(); 
    
?>