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

        //$sql1 = "SELECT * FROM users INNER JOIN friends ON friends.IdFriend1 = users.IdUser WHERE (friends.IdFriend1 <> 

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

    </head>

    <body>

        <div class="container mt-5 mb-3">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                <div class="icon"><i class="bx bxl-mailchimp"></i></div>
                                <div class="ms-2 c-details">
                                    <h6 class="mb-0">Mailchimp</h6><span>joined: 1 days ago</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            <h3 class="heading">Senior Product<br>Designer-Singapore</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                <div class="icon"> <i class="bx bxl-dribbble"></i> </div>
                                <div class="ms-2 c-details">
                                    <h6 class="mb-0">Dribbble</h6> <span>4 days ago</span>
                                </div>
                            </div>
                            <div class="badge"> <span>Product</span> </div>
                        </div>
                        <div class="mt-5">
                            <h3 class="heading">Junior Product<br>Designer-Singapore</h3>
                            <div class="mt-5">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="mt-3"> <span class="text1">42 Applied <span class="text2">of 70 capacity</span></span> </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                <div class="icon"> <i class="bx bxl-reddit"></i> </div>
                                <div class="ms-2 c-details">
                                    <h6 class="mb-0">Reddit</h6> <span>2 days ago</span>
                                </div>
                            </div>
                            <div class="badge"> <span>Design</span> </div>
                        </div>
                        <div class="mt-5">
                            <h3 class="heading">Software Architect <br>Java - USA</h3>
                            <div class="mt-5">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="mt-3"> <span class="text1">52 Applied <span class="text2">of 100 capacity</span></span> </div>
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