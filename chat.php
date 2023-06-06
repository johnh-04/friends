<?php
    
    ob_start();
    session_start();
    
    include 'forms/config.php';

    if (isset($_COOKIE["login"])) { //cookie -> session

        $_SESSION["login"] = 2;
        $_SESSION["username"] = $_COOKIE["username"];
        $_SESSION["password"] = $_COOKIE["password"];

    }

    if (isset($_SESSION["login"])) {

        //$_SESSION["key"] = "";
        
        $username = $_SESSION["username"];

        $sql = "SELECT * FROM users WHERE Username = '$username'";
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();

        $idUser = $row["IdUser"];

    } else header("location: ./login/login.php");

    //$idUtente = $_GET["idUtente"];

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

            var socket = new WebSocket('ws://<?=$_SERVER["SERVER_NAME"]?>:7777/chat');
            var idUser = <?=$idUser?>;

            function selectRoom(room) {

                if (socket.readyState === WebSocket.OPEN) socket.close();

                socket = new WebSocket('ws://<?=$_SERVER["SERVER_NAME"]?>:7777/chat');

                socket.onopen = () => {

                    console.log(`Connessione WebSocket aperta nella stanza ${room}`);
                    var message = 'Ciao server!';
                    socket.send(message);

                    var data = {
                        idUser: idUser,
                        room: room,
                        join: 1
                    };

                    socket.send(JSON.stringify(data));

                };

                socket.onmessage = (event) => {
                
                    //var data = event.data;
                    //console.log(data); //per un singolo messaggio (stringa)

                    //console.log(event.data)
                    var message = JSON.parse(event.data);

                    var user = message.idUser;
                    var room = message.room;
                    var messageText = message.msg;
                    var time = message.time;

                    console.log('Ricevuto messaggio:', messageText);

                    var position = "";

                    if (user == idUser) position = "float-right"; //float right/left
                    else position = "float-left";

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

                socket.onclose = (event) => {
                    console.log('WebSocket connection closed with code:', event.code);
                };

                socket.onerror = (error) => {
                    console.log('WebSocket error:', error);
                };

            }
            
            $(document).ready(() => {
                $(".list").click(function() {

                    var idUser2 = $(this).attr('id');
                    console.log(idUser2);

                    $.ajax({

                        url: './forms/key_session.php',
                        type: 'POST',
                        data: {idUser1: idUser, idUser2: idUser2},
                        success: (data) => {
                            $("#client").html(data);
                            selectRoom($("#idRoom").val());
                        }

                    });

                })
            });

            function islink(text) {
                return /(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])/.test(text) 
            }

            function str2link(text) {
                var replacedStr = text.replace(
                    /((http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-]))/g,
                    '<a href="$1" target="_blank">$1</a>'
                );
                return replacedStr;
            }

            function scrollDown() {

                var chat = document.getElementById('chat');
                chat.scrollTop = chat.scrollHeight;

            }

            function send() {

                var room = $("#idRoom").val(); //togliere key da input hidden e mettere in sessione
                var msg = $("#msg").val();

                if (msg !== '') {

                    if (islink(msg)) msg = str2link(msg);

                    var data = {
                        idUser: idUser,
                        room: room,
                        msg: msg,
                        time: new Date().getTime()
                    };

                    socket.send(JSON.stringify(data));

                    document.getElementById('msg').value = "";

                }

            }

        </script>
        
    </head>

    <body onLoad="scrollDown()">
        <div class="container">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card chat-app" style="height: 715px; justify-content: space-between;">
                        <div id="plist" class="people-list" style="width: 290px">

                            <div class="clearfix pl-2">
                                <img src="<?=$row["Avatar"]?>" alt="avatar" width="50px">
                                <div class="about row">
                                    <div class="col-8">
                                        <div class="name"><?=$_SESSION["username"]?></div>
                                        <div class="status"><a href="user.php" style="text-decoration: none; color: #999" target="_blank"><i class="fa fa-user"></i>&nbsp;&nbsp;Profile</a></div>
                                    </div>
                                    <div class="col-4 hidden-sm text-right">
                                        <a href="javascript:void(0);" class="btn btn-outline-warning"><i class="fa fa-fire"></i></a>
                                    </div>
                                </div>
                            </div>

                            <!--<div class="input-group mb-4">
                                <input type="text" class="form-control" placeholder="Search...">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                            </div>-->
                            
                            <hr>

                            <?php

                                $sql = "SELECT * FROM users WHERE IdUser <> '$idUser'"; //amici dello user sessione
                                $res = $conn->query($sql);

                            ?>

                            <ul class="list-unstyled chat-list mt-2 mb-0" id="users" style="overflow-y: scroll; height: 590px;">

                                <?php while ($row = $res->fetch_assoc()): ?>

                                    <li class="clearfix list" id="<?=$row["IdUser"]?>">
                                        <img src="<?=$row["Avatar"]?>" alt="avatar">
                                        <div class="about">
                                            <div class="name"><?=$row["Username"]?></div>
                                            <div class="status"><i class="fa fa-circle offline"></i> left 7 mins ago </div>
                                        </div>
                                    </li>

                                <?php endwhile; ?>

                            </ul>

                        </div>

                        <div class="chat" id="client">

                            <div class="chat-history">
                                <div class="m-b-0 p-2" id="chat" style="height: 500px; text-align: center; align-items: center; vertical-align: middle;"><!-- applicare overflow al div e farlo funzionare con lo scroll -->
                                    <img src="assets/img/logo.png" width="250px">
                                </div>
                            </div>

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