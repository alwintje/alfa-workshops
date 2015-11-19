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
        <img src="img/alfa-college.png" />
    </div>
    <div class="menu">
        <ul>
            <li>
                <a href="?">Evenementen</a>
            </li>
            <?php
            if($user['role'] == 2){
                if(isset($_GET['workshops'])){
                    echo '
                        <li>
                            <a href="?editWorkshop='.$_GET['workshops'].'&add">Workshop toevoegen</a>
                        </li>
                    ';
                }else {
                    echo '
                        <li>
                            <a href="?editEvent&add">Evenementen toevoegen</a>
                        </li>
                    ';
                }
            }
            ?>
            <li>
                <a href="?logout">Loguit</a>
            </li>
        </ul>
    </div>
    <section class="events">
        <?php
        if(isset($_GET['editEvent'])){
            require_once("includes/HandleEvents.php");
            echo '<article class="text-box">';
            $handleEvents = new HandleEvents($core, $db);
            if(isset($_GET['add'])){
                $handleEvents->add();
            }elseif(isset($_GET['edit'])){
                $handleEvents->edit($_GET['edit']);
            }
            echo '</article>';
        }elseif(isset($_GET['workshops'])){

            require_once("includes/Workshops.php");
            $workshops = new Workshops($core,$db,$user);
            $workshops->getWorkshops($_GET['workshops']);

        }else{

            $query = $db->doquery("SELECT * FROM {{table}} ORDER BY event_date ","events");
            while($row = mysqli_fetch_array($query)){
                ?>
                <article class="text-box">
                    <h1><?php echo $row['event_date']; ?></h1>
                    <h2><?php echo $row['name']; ?></h2>
                    <p> <?php echo $row['description']; ?> </p>
                    <?php
                        if($row['startdate_registration'] <= date("Y-m-d") && date("Y-m-d") <=  $row['enddate_registration']){
                    ?>
                        <a href="?workshops=<?php echo $row['id']; ?>">Aanmelden</a>
                    <?php
                        }else{
                    ?>
                        <a href="?workshops=<?php echo $row['id']; ?>">Bekijken</a>
                    <?php
                        }
                    ?>
                </article>
                <?php
            }
        }
        ?>
    </section>
    <?php
        $core->checkLoad();
    ?>
</body>
</html>