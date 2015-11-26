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

</head>
<body>
    <div class="header">
        <div class="header-text">
            <h1>Alfa-college</h1>
            <h2>Created by Alwin & Yaron</h2>
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
                        ';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <section class="events">
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

        }else{
            require_once("includes/HandleEvents.php");
            $handleEvents = new HandleEvents($core, $db);
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

                $query = $db->doquery("SELECT * FROM {{table}} ORDER BY event_date ","events");
                while($row = mysqli_fetch_array($query)){
                    ?>
                    <article class="text-box">
                        <h1><?php echo $row['event_date']; ?></h1>
                        <h2><?php echo $row['name']; ?></h2>
                        <p> <?php echo $row['description']; ?> </p>
                        <div class="rightBottom">
                        <?php
                        if($row['startdate_registration'] <= date("Y-m-d") && date("Y-m-d") <=  $row['enddate_registration']){
                            ?>
                            <a class="button" href="?workshops=<?php echo $row['id']; ?>">Aanmelden</a>
                            <?php
                        }else{
                            ?>
                            <a class="button" href="?workshops=<?php echo $row['id']; ?>">Bekijken</a>
                            <?php
                        }

                        if($user['role'] == 2){
                            echo '<a class="button" href="?edit='.$row['id'].'">Aanpassen</a>';
                        }
                        ?>
                        </div>
                    </article>
                    <?php
                }
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