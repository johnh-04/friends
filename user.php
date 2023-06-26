<?php

    ob_start();
    session_start();

    include './forms/config.php';

    if (isset($_GET["IdUser"])) $idUser = $_GET["IdUser"];
    else header("location: ./");

    if (!isset($_COOKIE["login"]) and !isset($_SESSION["login"])) header("location: ./login/login.php");
    else if (isset($_COOKIE["login"])) { //cookie -> session

        $_SESSION["login"] = 2;
        $_SESSION["username"] = json_decode(base64_decode($_COOKIE["user"]))->username;
        $_SESSION["password"] = json_decode(base64_decode($_COOKIE["user"]))->password;

    }

    $username = $_SESSION["username"];

    $sql = "SELECT * FROM users WHERE Username = '$username'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $idUserSession = $row["IdUser"];

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <?php 
            
            $sql = "SELECT * FROM users WHERE IdUser = '$idUser'";
            $res = $conn->query($sql);
            
            if ($res->num_rows == 1) $row = $res->fetch_assoc();
            else header("location: ./");

        ?>

        <meta charset="utf-8">
        <title>Friends | <?=$row["Username"]?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/user.css">
        <link rel="icon" href="assets/img/favicon.png">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>

            function input() {

                var idUser = <?=$idUserSession?>;
                var img = document.getElementById('files');
                var formdata = new FormData();

                var file = img.files[0];

                if (file != null) {
                    
                    formdata.append('idUser', idUser);
                    formdata.append('img', file, file.name);

                    $.ajax({

                        url: './forms/edit.php',
                        type: 'POST',
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: (data) => {
                            console.log(data)
                            $("#avatar").attr('src', data);
                        }

                    });

                }

            }
            
        </script>

    </head>

    <body>

        <div class="container bootstrap snippets bootdeys">
            <div class="row" id="user-profile">
                <div class="col-lg-3 col-md-4 col-sm-4">
                    <div class="main-box clearfix">

                        <h2><?=$row["Username"]?></h2>

                        <img src="<?=$row["Avatar"]?>" id="avatar" class="profile-img center-block" width="200px" height="200px"> <!--.img-responsive-->

                        <br>

                        <div class="profile-since">
                            Member since: <b><?=$row["MemDate"]?></b>
                        </div>

                        <br>

                        <?php if ($idUser == $idUserSession): ?>

                            <div class="center-block text-center">
                                <a href="./" class="btn btn-info">
                                    <i class="fa fa-home"></i>&nbsp;Home
                                </a>
                            </div>

                            <br>

                            <div class="center-block text-center">
                                <a href="./chat.php" class="btn btn-primary">
                                    <i class="fa fa-comment"></i>&nbsp;Send message
                                </a>
                            </div>

                            <br>

                            <div class="profile-message-btn center-block text-center">
                                <a href="./login/logout.php" class="btn btn-danger">
                                    <i class="fa fa-sign-out"></i>&nbsp;Logout
                                </a>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

                <div class="col-lg-9 col-md-8 col-sm-8">
                    <div class="main-box clearfix">

                        <div class="profile-header">
                            <h3><span>User info</span></h3>
                            <?php if ($idUser == $idUserSession): ?>
                                <button class="btn btn-primary edit-profile" onclick="document.getElementById('files').click();">
                                    <i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit profile
                                    <input type="file" id="files" accept="image/png" style="display: none;" onchange="input()">
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="row profile-user-info">
                            <div class="col-sm-8">

                                <div class="profile-user-details clearfix">
                                    <div class="profile-user-details-label">
                                        First Name
                                    </div>
                                    <div class="profile-user-details-value">
                                        <?=$row["Name"]?>
                                    </div>
                                </div>

                                <div class="profile-user-details clearfix">
                                    <div class="profile-user-details-label">
                                        Last Name
                                    </div>
                                    <div class="profile-user-details-value">
                                        <?=$row["Surname"]?>
                                    </div>
                                </div>

                                <div class="profile-user-details clearfix">
                                    <div class="profile-user-details-label">
                                        Date of Birth
                                    </div>
                                    <div class="profile-user-details-value">
                                        <?=$row["BirthDate"]?>
                                    </div>
                                </div>

                                <div class="profile-user-details clearfix">
                                    <div class="profile-user-details-label">
                                        Email
                                    </div>
                                    <div class="profile-user-details-value">
                                        <a><?=$row["Email"]?></a><!--href="mailto:$row["Email"]-->
                                    </div>
                                </div>

                            </div>

                            <!--<div class="col-sm-4 profile-social">
                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-twitter-square"></i><a href="#">@scjohansson</a></li>
                                    <li><i class="fa-li fa fa-linkedin-square"></i><a href="#">John Doe</a></li>
                                    <li><i class="fa-li fa fa-facebook-square"></i><a href="#">John Doe</a></li>
                                    <li><i class="fa-li fa fa-skype"></i><a href="#">Black_widow</a></li>
                                    <li><i class="fa-li fa fa-instagram"></i><a href="#">Avenger_Scarlett</a></li>
                                </ul>
                            </div>-->

                        </div>

                        <div class="tabs-wrapper profile-tabs">

                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-friends" data-toggle="tab">Friends</a></li>
                            </ul>

                            <div class="tab-content">

                                <?php

                                    $sql = "SELECT DISTINCT * FROM friends INNER JOIN users ON friends.IdFriend1 = users.IdUser WHERE friends.IdFriend1 = $idUser OR friends.IdFriend2 = $idUser UNION SELECT DISTINCT * FROM friends INNER JOIN users ON friends.IdFriend2 = users.IdUser WHERE friends.IdFriend1 = $idUser OR friends.IdFriend2 = $idUser";
                                    $res = $conn->query($sql);

                                ?>

                                <div class="tab-pane fade in active" id="tab-friends">

                                    <ul class="widget-users row" style="overflow-y: scroll; height: 250px;">

                                        <?php while ($row = $res->fetch_assoc()): 
                        
                                            if ($row["IdUser"] == $idUser): continue;
                                            else:
                                            
                                        ?>

                                            <li class="col-md-6">
                                                <div class="img">
                                                    <img src="<?=$row["Avatar"]?>" width="50px" height="50px" class="">
                                                </div>
                                                <div class="details">
                                                    <div class="name">
                                                        <a href="user.php?IdUser=<?=$row["IdUser"]?>" style="text-decoration: none; color: #000"><?=$row["Username"]?></a>
                                                    </div>
                                                    <div class="time">
                                                        <span>Joined: <?=$row["MemDate"]?></span>
                                                    </div>
                                                </div>
                                            </li>

                                        <?php endif; endwhile; ?>

                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </body>

</html>

<?php ob_end_flush(); ?>