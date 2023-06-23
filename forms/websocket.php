<?php

    require '../vendor/autoload.php';

    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    use React\EventLoop\Loop;
    use React\Socket\SocketServer;
    require 'MyWebSocket.php';

    $loop = Loop::get();

    $socket = new SocketServer($argv[1].":7777"); //nuova socket con ip (passato lda linea di comando) e porta. argv: parametri da linea di comando

    echo "Launching on " . $argv[1] . ":7777 socket...\n";

    $server = new IoServer(
        new HttpServer(
            new WsServer(
                // WebSocket application class
                new App()
            )
        ),
        $socket,
        $loop
    );

    $server->run();

    //cd ../../laragon/www/friends/forms
    //php websocket.php <ip>

?>