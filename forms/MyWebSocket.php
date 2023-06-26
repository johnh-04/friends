<?php

    require '../vendor/autoload.php';
    include 'config.php';

    session_start();

    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class App implements MessageComponentInterface {
        
        protected $clients;
        protected $rooms;

        public function __construct() {

            $this->clients = new \SplObjectStorage;
            $this->rooms = [];
            
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

            if (isset($data['idUser']) and isset($data['room'])) {
                
                if (isset($data['join']) and !isset($data['msg']) and !isset($data['time'])) {

                    if ($data['join'] == 1) {

                        $room = $data['room'];
                        $this->joinRoom($from, $room);

                    }

                } else if (!isset($data['join']) and isset($data['msg']) and isset($data['time'])) {

                    $idUser = mysqli_escape_string($conn, $data['idUser']);
                    $room = mysqli_escape_string($conn, $data['room']);
                    $message = mysqli_escape_string($conn, $data['msg']);
                    $time = mysqli_escape_string($conn, date('Y-m-d H:i:s', $data['time'] / 1000));

                    /*$username = $_SESSION["username"];

                    $sql = "SELECT * FROM users WHERE Username = '$username'";
                    $res = $conn->query($sql);
                    $row = $res->fetch_assoc();

                    $idUserSession = $row["IdUser"];

                    if ($idUser !== $idUserSession) return; --controllo sicurezza se qualcuno cambia id, session nella classe*/

                    $sql = "INSERT INTO messages (IdSender, IdRoom, Message, Time, Status) VALUES ('$idUser', '$room', '$message', '$time' , 1)";
                    $res = $conn->query($sql);

                    if ($res) echo "row inserita\n";

                    //echo "Messaggio ricevuto da " . $from->clientId . ": " . $message . "\n";

                    /*foreach ($this->clients as $client) {
                        $client->send($msg); //send in broadcast to all clients connected.
                    }*/

                    $this->sendMessage($from, $room, $msg, null);
            
                    //$from->send($msg);

                }

            }

        }

        public function onClose(ConnectionInterface $conn) {

            $this->clients->detach($conn);
            //utente offline...

            //$this->leaveRoom($from, $room); CHIUDI CONNESSIONE

        }

        public function onError(ConnectionInterface $conn, \Exception $e) {

            echo "Error: " . $e->getMessage();
            $conn->close();

        }

        public function joinRoom(ConnectionInterface $conn, $room) {

            //crea room se non esiste
            if (!isset($this->rooms[$room])) {
                $this->rooms[$room] = new SplObjectStorage();
            }

            //aggiunta user alla room
            $this->rooms[$room]->attach($conn);
    
            echo "Client ({$conn->resourceId}) joined room: {$room}\n";

        }

        protected function sendMessage(ConnectionInterface $from, $room, $msg, $toUser) {

            if (isset($this->rooms[$room])) {

                foreach ($this->rooms[$room] as $client) {

                    //Broadcast room
                    $client->send($msg);

                }

            }

        }

    } 

?>