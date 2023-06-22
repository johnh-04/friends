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

        <header class="lead-demo shadow">
            <nav class="navbar navbar-expand-lg navbar-light bg-white">
                <div class="container">
                    <a class="navbar-brand" href="#"><img src="assets/images/logo.svg" alt="lead-ui-kit logo"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#leadUIDemoNav-1"
                        aria-controls="leadUIDemoNav-1" aria-expanded="false" aria-label="Toggle navigation">
                        <i data-feather="menu"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="leadUIDemoNav-1">
                        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="index.html">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Pages</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Resources</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Docs</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Sign in</a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" class="btn btn-info">Buy Now</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

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
                                        <h6 class="mb-0"><a href="user.php?IdUser=<?=$row["IdUser"]?>" style="text-decoration: none; color: #000"><?=$row["Username"]?></a></h6>
                                        <span>Joined: <b><?=$row["MemDate"]?></b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="lead"><?=$row["Name"]?> <?=$row["Surname"]?></h5>
                                <button type="button" id="<?=$row["IdUser"]?>" class="user btn mt-4" style="background-color: #5d3fff; border-color: #5d3fff; color: #fff" onclick="friend(this.id)" style="float: right">Send message</button>
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