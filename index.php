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
                <a href="#home">Home</a>
            </li>
            <li>
                <a href="#evenementen">Evementen</a>
            </li>
        </ul>
    </div>
    <section class="events">
        <?php
        $query = $db->doquery("SELECT * FROM {{table}} ORDER BY date ","events");
        while($row = mysqli_fetch_array($query)){
            ?>
        <article class="text-box">
            <h1><?php echo $row['date']; ?></h1>
            <h1><?php echo $row['name']; ?></h1>
            <p> <?php echo $row['description']; ?> </p>
            <a href="Workshop.php?id=<?php echo $row['id']; ?>">Aanmelden</a>
        </article>
        <?php
        }
        ?>
    </section>
    <?php
        $core->checkLoad();
    ?>
</body>
</html>