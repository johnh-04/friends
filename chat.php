<?php
    

    ob_start();
    session_start();
    
    include 'forms/config.php';

    $idUtente = $_GET["idUtente"];

    $idUtente1 = 1;
    $username1 = "johnh04";

    $idUtente2 = 3;
    $username2 = "vanni";

    /*if (isset($_COOKIE["login"])) { //cookie -> session

        $_SESSION["login"] = 2;
        $_SESSION["username"] = $_COOKIE["username"];
        if (isset($_COOKIE["email"])) $_SESSION["email"] = $_COOKIE["email"];
        $_SESSION["password"] = $_COOKIE["password"];

    }

    if (isset($_SESSION["login"])) {
        
        $username = $_SESSION["username"];

        $sql = "SELECT * FROM utenti WHERE Username = '$username'";
        $res1 = $conn->query($sql);
        $row1 = $res1->fetch_assoc();

        $idUtente = $row1["idUtente"];

    }*/

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <title>Friends | Chat</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/chat.css">
        <link rel="icon" href="assets/img/favicon.png">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>

            var idUtente = <?=$idUtente?>;  //prendi utente da sessione

            function scrollDown() {

                var chat = document.getElementById('chat');
                chat.scrollTop = chat.scrollHeight;

            }

            function send() {

                var room = 1; //prendi stanza da input
                var msg = document.getElementById('msg').value;

                var data = {
                    idUtente: idUtente,
                    room: room,
                    msg: msg,
                    time: new Date().getTime()
                };

                socket.send(JSON.stringify(data));

                msg = document.getElementById('msg').value = "";

            }

            function sendMessageToRoom(room, messageText, time) {
                // Construct a new message object with the recipient and content
                const msg = {
                    recipient: room,
                    content: message,
                    time: timepan
                };

                // Send the message to the server
                socket.send(JSON.stringify(msg));
            }

            var socket = new WebSocket('ws://<?=$_SERVER["SERVER_NAME"]?>:7777/chat');

            socket.onopen = function() {

                console.log('Connessione WebSocket aperta.');
                var message = 'Ciao server!';
                socket.send(message);

            };
            
            socket.onmessage = function(e) {
                
                //var data = event.data;
                //console.log(data); //per un singolo messaggio (stringa)

                var message = JSON.parse(event.data);

                var user = message.idUtente;
                var room = message.room;
                var messageText = message.msg;
                var time = message.time;

                //sendMessageToClient(room, messageText, time);

                console.log('Ricevuto messaggio:', messageText);

                var position = "";

                if (user == idUtente) position = "float-right"; //sostituire 1 con utente corrente

                $("#chat").append(`

                    <li class="clearfix">
                        <div class="message other-message ${position}">
                            <span>${messageText}</span>
                            <sub class="message-data-time">${new Date(parseInt(time)).toTimeString().substr(0, 5)}</sub>
                        </div>
                    </li>
                    
                `);

                scrollDown();

            };

            socket.onclose = function(event) {
                console.log('WebSocket connection closed with code:', event.code);
            };

            socket.onerror = function(error) {
                console.log('WebSocket error:', error);
            };

        </script>
        
    </head>

    <body onLoad="scrollDown()">
        <div class="container">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card chat-app">
                        <div id="plist" class="people-list">

                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search...">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                            </div>

                            <ul class="list-unstyled chat-list mt-2 mb-0">

                                <li class="clearfix">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                                    <div class="about">
                                        <div class="name">Vincent Porter</div>
                                        <div class="status"> <i class="fa fa-circle offline"></i> left 7 mins ago </div>
                                    </div>
                                </li>

                                <li class="clearfix active">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                    <div class="about">
                                        <div class="name">Aiden Chavez</div>
                                        <div class="status"> <i class="fa fa-circle online"></i> online </div>
                                    </div>
                                </li>

                                <li class="clearfix">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">
                                    <div class="about">
                                        <div class="name">Mike Thomas</div>
                                        <div class="status"> <i class="fa fa-circle online"></i> online </div>
                                    </div>
                                </li>

                                <li class="clearfix">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">
                                    <div class="about">
                                        <div class="name">Christian Kelly</div>
                                        <div class="status"> <i class="fa fa-circle offline"></i> left 10 hours ago </div>
                                    </div>
                                </li>

                                <li class="clearfix">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar8.png" alt="avatar">
                                    <div class="about">
                                        <div class="name">Monica Ward</div>
                                        <div class="status"> <i class="fa fa-circle online"></i> online </div>
                                    </div>
                                </li>

                                <li class="clearfix">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">
                                    <div class="about">
                                        <div class="name">Dean Henry</div>
                                        <div class="status"> <i class="fa fa-circle offline"></i> offline since Oct 28
                                        </div>
                                    </div>
                                </li>

                            </ul>

                        </div>

                        <div class="chat">

                            <div class="chat-header clearfix">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                            <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                        </a>
                                        <div class="chat-about">
                                            <h6 class="m-b-0">Aiden Chavez</h6>
                                            <small>Last seen: 2 hours ago</small>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 hidden-sm text-right">
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary"><i class="fa fa-camera"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="fa fa-image"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-outline-info"><i class="fa fa-cogs"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-outline-warning"><i class="fa fa-question"></i></a>
                                    </div>

                                </div>
                            </div>

                            <?php

                                $sql = "SELECT * FROM rooms INNER JOIN messages USING(IdRoom) WHERE /*(IdUser1 = '$idUtente1' and IdUser2 = '$idUtente2')*/ IdRoom = 1 ORDER BY Time ASC"; //utente attuale deve andare a dx di default. io sono utente1
                                $res = $conn->query($sql);

                            ?>

                            <div class="chat-history">
                                <ul class="m-b-0 p-2" id="chat" style="overflow-y: scroll; height: 380px;">

                                    <?php
                                     
                                        while ($row = $res->fetch_assoc()):

                                            $idSender = $row["IdSender"];

                                            $timestamp = $row["Time"];
                                            $unixTimestamp = strtotime($timestamp);

                                            $dateTime = new DateTime();
                                            $dateTime->setTimestamp($unixTimestamp);
                                            $timezone = new DateTimeZone('Europe/Rome');
                                            $dateTime->setTimezone($timezone);

                                            $time = $dateTime->format('H:i');

                                    ?>

                                            <li class="clearfix">
                                                <div class="message other-message <?php if ($idSender == $idUtente) echo "float-right"?>"> <!--sostituire idutente1-->
                                                    <span><?=$row["Message"]?></span>
                                                    <sub class="message-data-time"><?=$time?></sub>
                                                </div>
                                            </li>

                                    <?php endwhile; ?>

                                </ul>
                            </div>

                            <div class="chat-message clearfix">
                                <div class="input-group mb-0">
                                    <input type="text" class="form-control" id="msg" placeholder="Enter text here...">
                                    <div class="input-group-prepend">
                                        <button class="input-group-text" onclick="send()"><i class="fa fa-send"></i></button>
                                    </div>
                                </div>
                            </div>

                            <script> // to send message on Enter
                                msg.addEventListener("keydown", (event) => {
                                    if (event.key === 'Enter') {
                                        send();
                                    }
                                })
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
        
    </body>

</html>

<?php ob_end_flush(); ?>