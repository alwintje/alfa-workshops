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
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <link rel="stylesheet" type="text/css" href="styles/base.css"
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
                echo '
                        <li>
                            <a href="?addEvent">Evenementen toevoegen</a>
                        </li>
                    ';
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
            if(isset($_GET['add'])){

            }
        ?>
            <article class="text-box">
                <form action="?editEvent&add">
                    <label for="name">Naam:</label><input type="text" name="name" id="name" />
                    <label for="description">Omschrijving:</label><input type="text" name="description" id="description" />
                    <label for="date">Datum:</label><input type="text" name="date" id="date" />
                    <label for="startdate_registration">Start aanmeldingen:</label><input type="text" name="startdate_registration" id="startdate_registration" />
                    <label for="enddate_registration">Einde aanmeldingen:</label><input type="text" name="enddate_registration" id="enddate_registration" />
                    <label for="rating">Waarderingen:</label><input type="text" name="rating" id="rating" />
                    <label for="mail_confirm">Mail bevestiging:</label><input type="text" name="mail_confirm" id="mail_confirm" />
                </form>
            </article>

        <?


        }elseif(isset($_GET['workshops'])){
            $query = $db->doquery("SELECT * FROM {{table}} WHERE event='".$_GET['workshops']."' ","workshops");
            while($row = mysqli_fetch_array($query)){
                ?>
                <article class="text-box">
                    <h2><?php echo $row['name']; ?> - <?php echo $row['location']; ?></h2>
                    <p> <?php echo $row['description']; ?> </p>
                    <a href="?register=<?php echo $row['id']; ?>">Aanmelden</a>
                </article>
                <?php
            }
        }else{

            $query = $db->doquery("SELECT * FROM {{table}} ORDER BY date ","events");
            while($row = mysqli_fetch_array($query)){
                ?>
                <article class="text-box">
                    <h1><?php echo $row['date']; ?></h1>
                    <h2><?php echo $row['name']; ?></h2>
                    <p> <?php echo $row['description']; ?> </p>
                    <a href="?workshops=<?php echo $row['id']; ?>">Aanmelden</a>
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