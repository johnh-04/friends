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
        
        $username = $_SESSION["username"];

        $sql = "SELECT * FROM users WHERE Username = '$username'";
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();

        $idUser = $row["IdUser"];

        $sql = "SELECT * FROM users WHERE IdUser NOT IN (SELECT DISTINCT users.IdUser FROM friends INNER JOIN users ON friends.IdFriend1 = users.IdUser WHERE friends.IdFriend1 = $idUser OR friends.IdFriend2 = $idUser UNION SELECT DISTINCT users.IdUser FROM friends INNER JOIN users ON friends.IdFriend2 = users.IdUser WHERE friends.IdFriend1 = $idUser OR friends.IdFriend2 = $idUser)"; //elenco amici + io (io non sono nell'elenco se non ho amicizie)
        $res = $conn->query($sql);

    } else header("location: ./login/login.php");

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <title>Friends | Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/index.css">
        <link rel="icon" href="assets/img/favicon.png">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>

            function friend(id) {

                var idUser = <?=$idUser?>;
                
                $.ajax({

                    url: './forms/friend.php',
                    type: 'POST',
                    data: {idUser: idUser, idFriend: id},
                    success: (data) => {
                        console.log('success');
                        location.href = './chat.php';
                    }

                });

            }

        </script>

    </head>

    <body>

        <div class="container mt-5 mb-3">
            <div class="row">
                <?php while ($row = $res->fetch_assoc()): 
                    
                    if ($row["IdUser"] == $idUser): continue;
                    else:
                    
                ?>

                    <div class="col-md-4 mb-4">
                        <div class="card p-3 mb-2">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center clearfix">
                                    <img src="<?=$row["Avatar"]?>" alt="avatar" width="45px" height="45px" style="border-radius: 50%">
                                    <div class="ml-2 c-details">
                                        <h6 class="mb-0"><?=$row["Username"]?></h6><span>Joined: <?=$row["MemDate"]?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="lead"><?=$row["Name"]?> <?=$row["Surname"]?></h5>
                                <button type="button" id="<?=$row["IdUser"]?>" class="user btn btn-primary mt-4" onclick="friend(this.id)" style="float: right">Be a friend</button>
                            </div>
                        </div>
                    </div>

                <?php endif; endwhile; ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>

</html>