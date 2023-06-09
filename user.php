<?php

    ob_start();
    session_start();

    include './forms/config.php';

    if (!isset($_COOKIE["login"]) and !isset($_SESSION["login"])) header("location: ./login/login.php");
    else if (isset($_COOKIE["login"])) { //cookie -> session

        $_SESSION["login"] = 2;
        $_SESSION["username"] = $_COOKIE["username"];
        $_SESSION["password"] = $_COOKIE["password"];

    }

    $username = $_SESSION["username"];

    $sql = "SELECT * FROM users WHERE Username = '$username'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $idUser = $row["IdUser"];

?>

<!DOCTYPE html>
<html lang="en">

    <head>

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

                var idUser = <?=$idUser?>;
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
                            $("#avatar").attr('src', data); //fare aggiornamento in automatico!!!!
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

                        <div class="profile-status">
                            <i class="fa fa-check-circle"></i>Online
                        </div>

                        <img src="<?=$row["Avatar"]?>" id="avatar" class="profile-img center-block" width="200px" height="200px">

                        <div class="profile-label">
                            <span class="label label-danger">Admin</span>
                        </div>

                        <div class="profile-stars">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-o"></i>
                            <span>Super User</span>
                        </div>

                        <div class="profile-since">
                            Member since: <?=$row["MemDate"]?>
                        </div>

                        <br>

                        <div class="profile-message-btn center-block text-center">
                            <a href="./chat.php" style="text-decoration: none">
                                <i class="fa fa-envelope"></i>&nbsp;Send message <!--non se sei tu stesso -->
                            </a>
                        </div>

                        <br>

                        <div class="profile-message-btn center-block text-center">
                            <a href="./login/logout.php" class="btn btn-danger">
                                <i class="fa fa-sign-out"></i>&nbsp;Logout <!--non se sei tu stesso -->
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-lg-9 col-md-8 col-sm-8">
                    <div class="main-box clearfix">

                        <div class="profile-header">
                            <h3><span>User info</span></h3>
                            <button class="btn btn-primary edit-profile" onclick="document.getElementById('files').click();">
                                <i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit profile
                                <input type="file" id="files" accept="image/png" style="display: none;" onchange="input()">
                            </button>
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

                            <div class="col-sm-4 profile-social">
                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-twitter-square"></i><a href="#">@scjohansson</a></li>
                                    <li><i class="fa-li fa fa-linkedin-square"></i><a href="#">John Doe </a></li>
                                    <li><i class="fa-li fa fa-facebook-square"></i><a href="#">John Doe </a></li>
                                    <li><i class="fa-li fa fa-skype"></i><a href="#">Black_widow</a></li>
                                    <li><i class="fa-li fa fa-instagram"></i><a href="#">Avenger_Scarlett</a></li>
                                </ul>
                            </div>

                        </div>

                        <div class="tabs-wrapper profile-tabs">

                            <ul class="nav nav-tabs">
                                <!--<li><a href="#tab-activity" data-toggle="tab">Activity</a></li>-->
                                <li class="active"><a href="#tab-friends" data-toggle="tab">Friends</a></li>
                            </ul>

                            <div class="tab-content">
                                <!--<div class="tab-pane fade in active" id="tab-activity">
                                    <div class="table-responsive" style="overflow-y: scroll; height: 250px;">
                                        <table class="table">
                                            <tbody>

                                                <tr>
                                                    <td class="text-center">
                                                        <i class="fa fa-comment"></i>
                                                    </td>
                                                    <td>
                                                        John Doe posted a comment in <a href="#">Avengers Initiative</a>
                                                        project.
                                                    </td>
                                                    <td>
                                                        2014/08/08 12:08
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        <i class="fa fa-truck"></i>
                                                    </td>
                                                    <td>
                                                        John Doe changed order status from <span
                                                            class="label label-primary">Pending</span> to <span
                                                            class="label label-success">Completed</span>
                                                    </td>
                                                    <td>
                                                        2014/08/08 12:08
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        <i class="fa fa-check"></i>
                                                    </td>
                                                    <td>
                                                        John Doe posted a comment in <a href="#">Lost in Translation opening
                                                            scene</a> discussion.
                                                    </td>
                                                    <td>
                                                        2014/08/08 12:08
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        <i class="fa fa-users"></i>
                                                    </td>
                                                    <td>
                                                        John Doe posted a comment in <a href="#">Avengers Initiative</a>
                                                        project.
                                                    </td>
                                                    <td>
                                                        2014/08/08 12:08
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        <i class="fa fa-comment"></i>
                                                    </td>
                                                    <td>
                                                        John Doe posted a comment in <a href="#">Avengers Initiative</a>
                                                        project.
                                                    </td>
                                                    <td>
                                                        2014/08/08 12:08
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        <i class="fa fa-comment"></i>
                                                    </td>
                                                    <td>
                                                        John Doe posted a comment in <a href="#">Avengers Initiative</a>
                                                        project.
                                                    </td>
                                                    <td>
                                                        2014/08/08 12:08
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        <i class="fa fa-comment"></i>
                                                    </td>
                                                    <td>
                                                        John Doe posted a comment in <a href="#">Avengers Initiative</a>
                                                        project.
                                                    </td>
                                                    <td>
                                                        2014/08/08 12:08
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>-->

                                <div class="tab-pane fade in active" id="tab-friends">

                                    <ul class="widget-users row" style="overflow-y: scroll; height: 250px;">

                                        <li class="col-md-6">
                                            <div class="img">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    class="img-responsive" alt>
                                            </div>
                                            <div class="details">
                                                <div class="name">
                                                    <a href="#">John Doe </a>
                                                </div>
                                                <div class="time">
                                                    <i class="fa fa-clock-o"></i> Last online: 5 minutes ago
                                                </div>
                                                <div class="type">
                                                    <span class="label label-danger">Admin</span>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="col-md-6">
                                            <div class="img">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    class="img-responsive" alt>
                                            </div>
                                            <div class="details">
                                                <div class="name">
                                                    <a href="#">Mila Kunis</a>
                                                </div>
                                                <div class="time online">
                                                    <i class="fa fa-check-circle"></i> Online
                                                </div>
                                                <div class="type">
                                                    <span class="label label-warning">Pending</span>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="col-md-6">
                                            <div class="img">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    class="img-responsive" alt>
                                            </div>
                                            <div class="details">
                                                <div class="name">
                                                    <a href="#">Ryan Gossling</a>
                                                </div>
                                                <div class="time online">
                                                    <i class="fa fa-check-circle"></i> Online
                                                </div>
                                            </div>
                                        </li>

                                        <li class="col-md-6">
                                            <div class="img">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    class="img-responsive" alt>
                                            </div>
                                            <div class="details">
                                                <div class="name">
                                                    <a href="#">Robert Downey Jr.</a>
                                                </div>
                                                <div class="time">
                                                    <i class="fa fa-clock-o"></i> Last online: Thursday
                                                </div>
                                            </div>
                                        </li>

                                        <li class="col-md-6">
                                            <div class="img">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    class="img-responsive" alt>
                                            </div>
                                            <div class="details">
                                                <div class="name">
                                                    <a href="#">Emma Watson</a>
                                                </div>
                                                <div class="time">
                                                    <i class="fa fa-clock-o"></i> Last online: 1 week ago
                                                </div>
                                            </div>
                                        </li>

                                        <li class="col-md-6">
                                            <div class="img">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    class="img-responsive" alt>
                                            </div>
                                            <div class="details">
                                                <div class="name">
                                                    <a href="#">George Clooney</a>
                                                </div>
                                                <div class="time">
                                                    <i class="fa fa-clock-o"></i> Last online: 1 month ago
                                                </div>
                                            </div>
                                        </li>

                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </body>

</html>

<?php ob_end_flush(); ?>