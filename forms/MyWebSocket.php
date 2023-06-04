<?php

    require '../vendor/autoload.php';
    include 'config.php';

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

            if ($data['idUser'] == 0) {

                $room = $data['room'];

                $this->joinRoom($from, $room);
                echo "connessione room $room";

            } else if (isset($data['room']) and isset($data['msg']) and isset($data['idUser'])) {

                $idUser = mysqli_escape_string($conn, $data['idUser']);
                $room = mysqli_escape_string($conn, $data['room']);
                $message = mysqli_escape_string($conn, $data['msg']);
                $time = mysqli_escape_string($conn, date('Y-m-d H:i:s', $data['time'] / 1000));

                $sql = "INSERT INTO messages (IdSender, IdRoom, Message, Time, Status) VALUES ('$idUser', '$room', '$message', '$time' , 1)";
                $res = $conn->query($sql);

                if ($res) echo "row inserita\n";

                //echo "Messaggio ricevuto da " . $from->clientId . ": " . $message . "\n";

                /*foreach ($this->clients as $client) {
                    $client->send($msg); //send in broadcast to all clients connected. RISOLVI QUESTO, DISTINGUI PER ROOM
                }*/

                if (isset($this->rooms[$room])) {

                    foreach ($this->rooms[$room] as $client) {
                        $client->send($msg);
                    }

                    //ogni utente (con stesso id o diverso) quando entra in una stanza deve fare la joinroom perché adesso si connette solo quando manda un messaggio. quando cambia stanza o logout -> leaveroom (nella stessa chat con stessi utenti bisogna prima mandare un mess)
                    //CONTROLLO: idPartenza e idArrivo diversi, idPartenza uguale e idArrivo diverso, idPartenza uguale e idArrivo uguale, idArrivo messo dal punto di visto che manda e riceve
                    //BROWSER CHROME
                    //$from->send($msg);
                    /*
                    visto che una persona appena manda il messaggio è nella room (e non cambia se questo cambia stanza) allora bisogna assegnarla ogni volta che si collega
                    */
                }

            }

        }

        public function onClose(ConnectionInterface $conn) {

            $this->clients->detach($conn);
            //utente offline

            /*foreach ($this->rooms as &$room) {
                if (($key = array_search($conn, $room)) !== false) {
                    unset($room[$key]);
                }
            }*/

            //$this->leaveRoom($from, $room); CHIUDI CONNESSIONE

        }

        public function onError(ConnectionInterface $conn, \Exception $e) {

            echo "Errore: " . $e->getMessage();
            $conn->close();

        }

        public function joinRoom(ConnectionInterface $conn, $room) {
            // Create the room if it doesn't exist
            if (!isset($this->rooms[$room])) {
                $this->rooms[$room] = new SplObjectStorage();
            }

            // Add the client to the room
            $this->rooms[$room]->attach($conn);
    
            echo "Client ({$conn->resourceId}) joined room: {$room}\n";
        }

        public function leaveRoom(ConnectionInterface $conn, $room) {
            // Check if the room exists
            if (isset($this->rooms[$room])) {
                // Remove the client from the room
                $this->rooms[$room]->detach($conn);

                // If the room is empty, remove it
                if ($this->rooms[$room]->count() === 0) {
                    unset($this->rooms[$room]);
                }
            }
        }

    } 

?>