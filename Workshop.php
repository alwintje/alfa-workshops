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
    $getId = $_GET['id'];
    $query = $db->doquery("SELECT * FROM {{table}} WHERE id=$getId","workshops");
    while($row = mysqli_fetch_array($query)){
        echo "Id: " .$row['id'];
        echo "<br/>";
        echo $row['description'];
    }
    ?>
</section>
<?php
$core->checkLoad();
?>
</body>
</html>