<?php

    ob_start();
    session_start();

    include 'config.php';

    //if ((!isset($_SESSION["login"]))) header("location: ../");

    $idUtente1 = $_POST["idUtente1"];
    $idUtente2 = $_POST["idUtente2"];

    $sql = "SELECT * FROM chats WHERE (IdSender = '$idUtente1' or IdSender = '$idUtente2') and (IdReceiver = '$idUtente1' or idReceiver = '$idUtente2') ORDER BY Time ASC"; //utente attuale deve andare a dx di default. io sono utente1
    $res = $conn->query($sql);

    while ($row = $res->fetch_assoc()):

        $idSender = $row["IdSender"];

        $timestamp = $row["Time"];
        $unixTimestamp = strtotime($timestamp);

        $dateTime = new DateTime();
        $dateTime->setTimestamp($unixTimestamp);

        $time = $dateTime->format('H:i');
    
?>

        <li class="clearfix">
            <div class="message other-message <?php if ($idSender == $idUtente1) echo "float-right"?>">
                <span><?=$row["Message"]?></span>
                <sub class="message-data-time"><?=$time?></sub>
            </div>
        </li>

<?php

    endwhile;

    ob_end_flush();

?>