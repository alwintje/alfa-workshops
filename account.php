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
if($security->checksession()){
    $core->loadPage("index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
        }elseif(isset($_POST['register'])){
            $errors = $security->checkRegister($_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['password'], $_POST['cpassword']);
        }
        ?>
        <img src="img/alfa-college.png" />
        <?php

        if ($errors != null) {
            echo '
            <div class="errors">
                ' . $errors . '
            </div>
                ';
        }

        ?>
        <form action="?login" method="post" id="loginRegisterForm">
            <div class="bg-left-side"></div>
            <div class="input-block first">
                <input type="text" name="username" id="username" placeholder="E-mail"/>
            </div>
            <div id="endEmail"></div>
            <div class="input-block reg">
                <input type="text" name="firstname" placeholder="Naam"/>
            </div>
            <div class="input-block reg">
                <input type="text" name="lastname" placeholder="Achternaam"/>
            </div>
            <div class="input-block">
                <input type="password" name="password" placeholder="Wachtwoord"/>
            </div>
            <div class="input-block reg">
                <input type="password" name="cpassword" placeholder="Wachtwoord controle"/>
            </div>
            <input type="submit" name="login" id="button" value="" />
            <div class="buttonToClick" onclick="document.getElementById('button').click();"></div>
            <div class="extra"></div>
            <div id="register">Registreer</div>
            <div class="forgot"><a id="forgot">Wachtwoord vergeten</a></div>
            <div id="forgotWindow">
                <input type="text" name="mail" class="username" placeholder="E-mail"/>
                <input type="submit" name="forgot" value="Opvragen" />
                <input type="button" name="back" value="Terug" />
            </div>
        </form>

    </div>

    <script>


        var timeOut = false;
        var timeOutEnterDelay = false;

        var register = false;
        var register_button = document.getElementById("register");


        if(window.location.hash.substr(1) == "register"){
            registerOrNot();
        }
        register_button.onclick = registerOrNot;


        var forgot = false;
        document.getElementById("forgot").onclick = forgotWindow;
        document.querySelector("input[name='back']").onclick = forgotWindow;

        if(window.location.hash.substr(1) == "forgot"){
            forgotWindow();
        }
        var email = document.getElementById("username");
        var endEmail = document.getElementById("endEmail");
        email.onkeypress = function(e){
            e = e || window.event;
            if(e.keyCode == 13){
                if(endEmail.style.display != "none"){
                    endEmail.click();
                }
                if(timeOutEnterDelay){
                    return false;
                }
            }

        };
        email.onkeyup = function(e){

            var values = e.originalTarget.value.split("@");
            if(values.length > 1){
                if(values[1].length >= 1){
                    var a = finishMail("student.alfa-college.nl", values[0], values[1]);
                    var b = finishMail("alfa-college.nl", values[0], values[1]);

                    if(!a && !b){
                        removeEndEmail();
                    }
                }else{
                    removeEndEmail();
                }
                email.setAttribute("autocomplete", "off");
            }else{
                email.setAttribute("autocomplete", "on");
                removeEndEmail();
            }
        };
        endEmail.onclick = function (){
            email.value = this.innerHTML;
        };
        function removeEndEmail(){
            if(endEmail.style.display != "none"){
                clearTimeout(timeOut);
                timeOut = "";
                endEmail.style.display = "none";
            }
        }
        function forgotWindow() {
            if (!forgot) {
                document.getElementById("forgotWindow").style.height = "100%";
            } else {
                document.getElementById("forgotWindow").style.height = "";
            }
            forgot = !forgot;
        }
        function registerOrNot(){
            var reg_fields = document.getElementsByClassName("reg");
            if(!register){
                for(var i = 0; i < reg_fields.length; i++){
                    reg_fields[i].style.display = "block";
                }
                document.getElementById("loginRegisterForm").setAttribute("action", "?login#register");
                register_button.innerHTML = "Login";
                document.getElementById("button").setAttribute("name", "register");
            }else{
                for(var i = 0; i < reg_fields.length; i++){
                    reg_fields[i].style.display = "none";
                }
                document.getElementById("loginRegisterForm").setAttribute("action", "?login");
                register_button.innerHTML = "Registreer";
                document.getElementById("button").setAttribute("name", "login");
            }
            register = !register;
        }
        function stringStartsWith (string, prefix) {
            return string.slice(0, prefix.length) == prefix;
        }
        function finishMail(mail,valBefore, valAfter){
            if(stringStartsWith(mail, valAfter) && mail != valAfter){

                endEmail.innerHTML = valBefore+"@"+mail;
                endEmail.style.display = "block";
                clearTimeout(timeOut);
                timeOut = setTimeout(function (){
                    endEmail.style.display = "none";
                }, 4000);
                timeOutEnterDelay = setTimeout(function (){
                    timeOutEnterDelay = false;
                }, 5000);
                return true;
            }
            return false;
        }
    </script>
<?php
    $core->checkLoad();
?>
</body>
</html>