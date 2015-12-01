<?php
session_start();

require_once("includes/Core.php");
require_once("includes/Database.php");
require_once("includes/Security.php");
$core = new Core();
$db = new Database();
$db->opendb();
$security = new Security($core, $db);
$user = $security->checksession();
if(!$user){
    $core->loadPage("account.php");
}

if(isset($_GET['logout'])){
    $security->logout();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alfa workshops</title>
    <link rel="stylesheet" type="text/css" href="styles/index.css" />
    <link rel="stylesheet" type="text/css" href="styles/base.css" />
    <link rel="stylesheet" type="text/css" href="styles/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="styles/responsive.css" />
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="js/index.js"></script>

    <?php
        if(count($_GET) > 0){
            echo '
            <style>
                .header{
                    height: 300px;
                }
                .header #button{
                    display: none;
                }
            </style>
            ';
        }
    ?>
</head>
<body>
    <div class="header">
        <div class="header-text">
            <h1>Alfa-college workshops</h1>
<!--            <h2>Created by Alwin & Yaron </h2>-->
            <button id="button">Evenementen <i class="fa fa-arrow-down"></i> </button>
        </div>
        <div class="menu">
            <a class="logout" href="?logout">Uitloggen</a>
            <img src="img/alfa-college.png" />
            <ul>
                <li>
                    <a href="?">Evenementen</a>
                </li>
                <?php
                if($user['role'] == 2){
                    if(isset($_GET['workshops'])){
                        echo '
                            <li>
                                <a href="?workshops='.$_GET['workshops'].'&add">Workshop toevoegen</a>
                            </li>
                        ';
                    }else {
                        echo '
                            <li>
                                <a href="?add">Evenementen toevoegen</a>
                            </li>
                            <li>
                                <a href="?users">Gebruikers beheren</a>
                            </li>
                        ';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <section class="events" id="events">
        <?php
        if(isset($_GET['workshops'])){

            require_once("includes/Workshops.php");
            $workshops = new Workshops($core,$db,$user);
            if(isset($_GET['add'])){
                if($user['role'] == 2) {
                    echo '<article class="text-box">';
                    $workshops->add($_GET['workshops']);
                    echo '</article>';
                }else{
                    $core->notAllowed();
                }
            }elseif(isset($_GET['edit'])){
                if($user['role'] == 2) {
                    echo '<article class="text-box">';
                    $workshops->edit($_GET['workshops'],$_GET['edit']);
                    echo '</article>';
                }else{
                    $core->notAllowed();
                }
            }else{
                $workshops->getWorkshops($_GET['workshops']);
            }

        }elseif(isset($_GET['users'])){

            require_once("includes/Users.php");
            $users = new Users($core,$db,$user,$security);
            if(isset($_GET['edit'])){
                if($user['role'] == 2) {
                    echo '<article class="text-box">';
                    $users->edit($_GET['edit']);
                    echo '</article>';
                }else{
                    $core->notAllowed();
                }
            }else{
                $users->getAll();
            }

        }else{
            require_once("includes/HandleEvents.php");
            $handleEvents = new HandleEvents($core, $db, $user);
            if(isset($_GET['add'])){
                if($user['role'] == 2) {
                    echo '<article class="text-box">';
                    $handleEvents->add();
                    echo '</article>';
                }else{
                    $core->notAllowed();
                }
            }elseif(isset($_GET['edit'])){

                if($user['role'] == 2) {
                    echo '<article class="text-box">';
                    $handleEvents->edit($_GET['edit']);
                    echo '</article>';
                }else{
                    $core->notAllowed();
                }
            }else{
                $handleEvents->getAll();


            }
        }
        ?>
    </section>
    <?php
        $core->checkLoad();
    ?>
</body>

<footer class="footer">

</footer>
</html>