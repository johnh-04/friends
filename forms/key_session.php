<?php

    ob_start();
    session_start();

    include 'config.php';

    if ((!isset($_SESSION["login"]))) header("location: ../login/login.php");

    $idUser1 = $_POST["idUser1"];
    $idUser2 = $_POST["idUser2"];

    $sql = "SELECT * FROM rooms WHERE (IdUser1 = '$idUser1' or IdUser1 = '$idUser2') and (IdUser2 = '$idUser1' or IdUser2 = '$idUser2')";
    $res = $conn->query($sql);

    if ($res->num_rows == 1) {

        $row = $res->fetch_assoc();
        $key = $row["KeyCode"];

    } else {

        do {

            $check = false;
            $sql = "SELECT * FROM rooms";
            $res = $conn->query($sql); //aggiorna lista chiavi
            $key = uniqid(); //genera nuova chiave

            while ($row = $res->fetch_assoc()) {

                if ($row["KeyCode"] == $key) $check = true;

            }

        } while ($check);

        $sql = "INSERT INTO rooms (IdUser1, IdUser2, KeyCode) VALUES ('$idUser1', '$idUser2', '$key')";
        $res = $conn->query($sql);

    }

    //echo $key;

    $sql = "SELECT * FROM rooms WHERE (KeyCode = '$key')";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $idRoom = $row["IdRoom"];

    $sql = "SELECT * FROM users WHERE (IdUser = '$idUser2')";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

?>

    <script>

        function scrollDown() {

            var chat = document.getElementById('chat');
            chat.scrollTop = chat.scrollHeight;

        }

        $(document).ready(() => {

            scrollDown();
            document.getElementById("msg").focus();
            
        });

    </script>

    <div class="chat-header clearfix">
        <div class="row">

            <div class="col-lg-6">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                    <img src="<?=$row["Avatar"]?>" width="50px" height="40px" alt="avatar">
                </a>
                <div class="chat-about">
                    <h6 class="m-b-0"><a href="user.php?IdUser=<?=$idUser2?>" style="text-decoration: none; color: #000" target="_blank"><?=$row["Username"]?></a></h6>
                    <small>Last seen: 2 hours ago</small>
                </div>
                <input type="hidden" id="idRoom" value="<?=$idRoom?>">
            </div>

        </div>
    </div>

    <?php

        $sql = "SELECT * FROM rooms INNER JOIN messages USING(IdRoom) WHERE (KeyCode = '$key') ORDER BY Time ASC";
        $res = $conn->query($sql);

    ?>

    <div class="chat-history">
        <ul class="m-b-0 p-2" id="chat" style="overflow-y: scroll; height: 500px;"> <!-- applicare overflow al div e farlo funzionare con lo scroll -->

            <?php

                $title = null;
                
                while ($row = $res->fetch_assoc()):

                    $idSender = $row["IdSender"]; //trovare messaggi con la key della room

                    $timestamp = $row["Time"];
                    $unixTimestamp = strtotime($timestamp);

                    $dateTime = new DateTime();
                    $dateTime->setTimestamp($unixTimestamp);
                    /*$timezone = new DateTimeZone('Europe/Rome');
                    $dateTime->setTimezone($timezone);*/

                    $time = $dateTime->format('H:i');

                    if ($title === null or $title !== date("F j, Y", strtotime($row["Time"]))) {

                        $title = date("F j, Y", strtotime($row["Time"]));
                        
                        echo 
                            "<br>
                            <li class=\"clearfix\">
                                <div class=\"justify-content-center d-flex\">
                                    <span>$title</span>
                                </div>
                            </li>
                            <br>";

                    }

            ?>

                    <li class="clearfix">
                        <div class="message other-message <?php if ($idSender == $idUser1) echo "float-right"; else echo "float-left"?>">
                            <span><?=$row["Message"]?></span>
                            <sub class="message-data-time" style="/*float: right;*/"><?=$time?></sub>
                        </div>
                    </li>

            <?php endwhile; ?>

        </ul>

        <div class="chat-message clearfix">
            <div class="input-group mb-0">
                <input type="text" class="form-control" id="msg" placeholder="Enter text here...">
                <div class="input-group-prepend">
                    <button class="input-group-text" onclick="send()"><i class="fa fa-send"></i></button>
                </div>
            </div>
        </div>

    </div>

    <script> // to send message on Enter
        msg.addEventListener("keydown", (event) => {
            if (event.key === 'Enter' && msg.value !== '') {
                send();
            }
        })
    </script>

<?php ob_end_flush(); ?>