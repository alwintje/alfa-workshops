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
        $errors = null;
        if (isset($_POST['login'])) {
            $errors = $security->checkLogin($_POST['username'], $_POST['password']);
        }
        if ($errors != null) {
            echo '
            <div class="errors">
                ' . $errors . '
            </div>
                ';
        }
        ?>
        <img src="alfa-college.png" />
        <form action="?login" method="post">
            <div class="bg-left-side"></div>
            <div class="input-block first">
                <input type="text" name="username" id="username" placeholder="E-mail"/> <label for="username">@alfa-college.nl</label>
            </div>
            <div class="input-block reg">
                <input type="text" name="name" placeholder="Naam"/>
            </div>
            <div class="input-block reg">
                <input type="text" name="surname" placeholder="Achternaam"/>
            </div>
            <div class="input-block last">
                <input type="password" name="password" placeholder="Wachtwoord"/>
            </div>
            <div class="input-block reg">
                <input type="password" name="cpassword" placeholder="Wachtwoord controle"/>
            </div>
            <input type="submit" name="login" class="button" value=""/>

            <div class="extra"></div>
            <div id="register">Registreer</div>
            <div class="forgot"><a href="#forgot">Wachtwoord vergeten</a></div>
        </form>
    </div>
    <script>
        var register = false;
        var register_button = document.getElementById("register");
        register_button.onclick = function(){
            var reg_fields = document.getElementsByClassName("reg");
            if(!register){
                for(var i = 0; i < reg_fields.length; i++){
                    reg_fields[i].style.display = "block";
                }
                register_button.innerHTML = "Login";
                register = true;
            }else{

                for(var i = 0; i < reg_fields.length; i++){
                    reg_fields[i].style.display = "none";
                }
                register_button.innerHTML = "Registreer";
                register = false;
            }
            var last = document.querySelector(".last");
            last.className = last.className.replace(/\blast\b/,'');

            var input_blocks = document.querySelectorAll(".input-block");

            var displayed_blocks = [];

            for(var i=0; i< input_blocks.length;i++){
                if(input_blocks[i].style.display != "none"){
                    displayed_blocks[displayed_blocks.length] = input_blocks[i];
                    console.log(displayed_blocks);
                }
            }
            console.log("input blocks: ");
            console.log(displayed_blocks);
            var last_input_block = displayed_blocks[displayed_blocks.length-1];
            console.log("Last block: "+last_input_block);
            last_input_block.setAttribute("class", last_input_block.getAttribute("class")+" last");
        };
    </script>
</body>
</html>