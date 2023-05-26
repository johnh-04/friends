<?php

    require '../vendor/autoload.php';
    include 'config.php';

    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class MyWebSocket implements MessageComponentInterface {

        protected $clients;
        protected $rooms;

        public function __construct() {
            $this->clients = new \SplObjectStorage;
            $this->rooms = new \SplObjectStorage;
        }

        public function onOpen(ConnectionInterface $conn) {
            // Aggiungi la connessione alla lista dei client
            $this->clients->attach($conn);
            // Assegna un identificatore univoco al client
            $conn->clientId = uniqid();
        }

        public function onMessage(ConnectionInterface $from, $msg) {

            global $conn;
            //echo "Messaggio ricevuto: {$msg}\n";
            //$response = "Risposta dal server: Messaggio ricevuto correttamente.";
            //$from->send($response);

            echo "Messaggio ricevuto: {$msg}\n";
            $data = json_decode($msg, true);
            //print_r($data);

            if (isset($data['room']) and isset($data['msg']) and isset($data['idUtente'])) {

                $idUtente = $data['idUtente'];
                $room = $data['room'];
                $message = $data['msg'];
                $time = date('Y-m-d H:i:s', $data['time'] / 1000);

                $sql = "INSERT INTO messages (IdSender, IdRoom, Message, Time, Status) VALUES ('$idUtente', '$room', '$message', '$time' , 1)";
                $res = $conn->query($sql);

                if ($res) echo "row inserita\n";

                /*foreach ($this->rooms as $rooms) {
                    if ($rooms !== $from && $rooms->rooms === 1) {
                        $rooms->send($msg);
                        break;
                    }
                }*/

                //$from->send($message); INVIA AL CLIENT

                echo "Messaggio ricevuto da " . $from->clientId . ": " . $message . "\n";

                /*foreach ($this->clients as $client) {
                    $client->send($message);
                }*/

                $from->send($msg);

                //$this->broadcastToRoom($room, $message, $idUtente);

            }

        }

        public function onClose(ConnectionInterface $conn) {
            // Rimuovi la connessione dalla lista dei client
            $this->clients->detach($conn);
        }

        public function onError(ConnectionInterface $conn, \Exception $e) {
            // Gestisci gli errori
            echo "Errore: " . $e->getMessage();
            $conn->close();
        }
        
    }

?>