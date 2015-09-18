<?php
session_start();

require_once("includes/Core.php");
require_once("includes/Database.php");
require_once("includes/Security.php");
$core = new Core();
$db = new Database();
$db->opendb();
$security = new Security($core, $db);
$user = false;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alfa-Workshops</title>
    <link href="styles/base.css" rel="stylesheet" />
    <link href="styles/loginScreens.css" rel="stylesheet" />
</head>
<body>
<?php
//    if($db->checksession())
?>
<!--    <section class="login">-->
<!--        <form >-->
<!--            <input type="text" name="user" placeholder="Email" /> <br />-->
<!--            <input type="password" name="pass" placeholder="Wachtwoord" />  <br />-->
<!--            <input type="submit" name="login" value="Login" />-->
<!--            <input type="button" name="registreer" value="Registreer" />-->
<!---->
<!--        </form>-->
<!--    </section>-->
    <div id="apollo">
        <?php
        $bttnColor = "#1C1D21";
        $errors = null;
        if (isset($_POST['login'])) {
            $errors = $security->checkLogin($_POST['username'], $_POST['password']);
        }
        if ($errors != null) {
            $bttnColor = "#880000";
            echo '
            <div class="errors">
                ' . $errors . '
            </div>
                ';
        }
        ?>

        <form action="?login" method="post">
            <input type="text" name="username" class="username" placeholder="username"/>
            <input type="password" name="password" class="password" placeholder="password"/>
            <input type="submit" name="login" class="button" value="" style="background-color: <?php echo $bttnColor; ?>;"/>

            <div class="extra"></div>
            <div class="register">Registreer</div>
            <div class="forgot"><a href="#forgot">Wachtwoord vergeten</a></div>
        </form>
    </div>
</body>
</html>