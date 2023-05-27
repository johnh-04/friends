<?php

    require '../vendor/autoload.php';

    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    use React\EventLoop\Loop;
    use React\Socket\SocketServer;
    require 'MyWebSocket.php';

    $loop = Loop::get();

    // Create a SocketServer and bind it to the desired IP address and port
    $socket = new SocketServer($argv[1].":7777");

    $_SESSION['RUN'] = TRUE;

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

?>