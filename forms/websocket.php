<?php

    require '../vendor/autoload.php';
    require 'MyWebSocket.php';
    use Ratchet\App;
    session_start();

    $HOST = explode(':', $argv[1])[0];
    $PORT = explode(':', $argv[1])[1];

    echo "Opening $HOST:$PORT...";

    $server = new App($HOST, $PORT);

    $server->route('/chat', new MyWebSocket);
    $SOCKET = "$HOST:$PORT";
    $server->run();
    
?>