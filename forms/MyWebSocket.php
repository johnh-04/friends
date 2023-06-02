<?php

    require '../vendor/autoload.php';
    include 'config.php';

    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class App implements MessageComponentInterface {
        
        protected $clients;

        public function __construct() {

            $this->clients = new \SplObjectStorage;
            
        }

        public function onOpen(ConnectionInterface $conn) {

            $this->clients->attach($conn);
            echo "New connection! ({$conn->resourceId})\n";

        }

        public function onMessage(ConnectionInterface $from, $msg) {

            global $conn;

            echo "Messaggio ricevuto: {$msg}\n";
            $data = json_decode($msg, true);
            //print_r($data);

            if (isset($data['room']) and isset($data['msg']) and isset($data['idUser'])) {

                $idUser = mysqli_escape_string($conn, $data['idUser']);
                $room = mysqli_escape_string($conn, $data['room']);
                $message = mysqli_escape_string($conn, $data['msg']);
                $time = mysqli_escape_string($conn, date('Y-m-d H:i:s', $data['time'] / 1000));

                $sql = "INSERT INTO messages (IdSender, IdRoom, Message, Time, Status) VALUES ('$idUser', '$room', '$message', '$time' , 1)";
                $res = $conn->query($sql);

                if ($res) echo "row inserita\n";

                //echo "Messaggio ricevuto da " . $from->clientId . ": " . $message . "\n";

                foreach ($this->clients as $client) {
                    $client->send($msg); //send in broadcast to all clients connected. RISOLVI QUESTO, DISTINGUI PER ROOM
                }

                //$from->send($msg);

            }

        }

        public function onClose(ConnectionInterface $conn) {

            $this->clients->detach($conn);
            //utente offline

        }

        public function onError(ConnectionInterface $conn, \Exception $e) {

            echo "Errore: " . $e->getMessage();
            $conn->close();

        }

    }

?>