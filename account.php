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
$errors = null;

if(isset($_GET['checkMail'])){
    $security->checkMail();
}
if($user != false){
    if(!$user['validated']){
        $errors = "U moet uw account nog valideren.";
    }else{
        $core->loadPage("index.php");
    }
}else{
    if(isset($_GET['notValidated'])){
        $errors = "U moet uw account nog valideren.";
    }
}

$query = $db->doquery("SELECT * FROM {{table}} WHERE active='1' LIMIT 1","settings");
$settings = mysqli_fetch_array($query);

$mailHosts = explode(",", $settings['email_hosts']);

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
        if (isset($_POST['login'])) {
            $errors = $security->checkLogin($_POST['username'], $_POST['password']);
        }elseif(isset($_POST['register'])){
            $errors = $security->checkRegister($_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['password'], $_POST['cpassword']);
        }
        ?>
        <img src="img/alfa-college-blue.png" />
        <?php
            if(isset($_GET['validation']) && isset($_GET['id'])){
                $q = $db->doquery("SELECT validate FROM {{table}} WHERE id='".$_GET['id']."'","users");
                $r = mysqli_fetch_array($q);
                if($r['validate'] == $_GET['validation']){
                    $db->doquery("UPDATE {{table}} SET validated='1' WHERE id='".$_GET['id']."' ","users");
                    echo '
                        <div class="succes">
                            Validatie is correct. <br />
                            U kunt nu inloggen.
                        </div>
                    ';
                }else{
                    $errors = "Validatie is niet juist.";
                }
            }
        if($errors == null && isset($_POST['register'])){

            echo '
                <div class="succes">
                    U bent succesvol geregistreerd. <br />
                </div>
            ';

//            Check je email voor je validatie code.<br />
//                    Check ook je spam folder!
        }
//        var_dump($_POST);
        if(isset($_POST['forgot'])){
            $errors = $security->forgotPass($_POST['mail']);
        }
        if(isset($_POST['changePass'])){
            if($_POST['pass'] == $_POST['pass2']){
                $errors = $security->changePass($_GET['id'], $_GET['checkCode'], $_POST['pass']);
                if($errors == null){
                    echo '
                    <div class="succes">
                        Wachtwoord succesvol aangepast.<br />
                        U wordt automatisch doorverstuurd naar het inlogscherm.
                    </div>
                    ';
                    $core->delay(5000);
                    $core->loadPage("account.php");
                }
            }else{
                echo '
                    <div class="errors">
                        Wachtwoorden zijn niet het zelfde.
                    </div>
                ';
            }
        }
        if ($errors != null) {
            echo '
            <div class="errors">
                ' . $errors . '
            </div>
                ';
        }

        if(isset($_GET['checkCode'])){

            echo '
            <form action="" method="post" id="loginRegisterForm" style="height: 150px;">
                <div id="forgotWindow" style="height: 100%;">
                    <input type="password" name="pass" placeholder="Nieuw wachtwoord" class="username"/>
                    <input type="password" name="pass2" placeholder="Herhaal wachtwoord" class="username"/>
                    <input type="submit" name="changePass" value="Verander" />
                </div>
            </form>
            ';
        }else{

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
        <?php
        }
        ?>
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
        var whatIsEmail = document.createElement("span");
        whatIsEmail.innerHTML = "voorletter(s).achternaam@student.alfa-college.nl<br /><span style='color: #F00;font-style: italic;'>Niet je actie account of leerlingnummer!</span>";
        whatIsEmail.style.fontSize = "13px";
        whatIsEmail.style.display = "block";
        whatIsEmail.style.padding = "5px";
        whatIsEmail.style.background = "#555";
        whatIsEmail.style.color = "#FFF";
        whatIsEmail.style.margin = "5px";
        whatIsEmail.style.position = "absolute";
        whatIsEmail.style.width = "330px";
        whatIsEmail.style.top = "-50px";
        whatIsEmail.style.height = "40px";
        whatIsEmail.style.lineHeight = "20px";

        whatIsEmail.style.zIndex = "10";
        whatIsEmail.style.left = "-25px";// -15 (width is 30 bigger than parent), -5 (margin) en -5 (padding)

        whatIsEmail.style.textAlign = "center";


        email.onfocus = function(e){
            var eElement = document.getElementById("apollo");
            var beforeElement = document.getElementById("loginRegisterForm");

            eElement.insertBefore(whatIsEmail, beforeElement);
        };
        email.onblur = function(e){
            document.getElementById("apollo").removeChild(whatIsEmail);
        };
        email.onkeyup = function(e){

            var values = e.originalTarget.value.split("@");
            if(values.length > 1){
                if(values[1].length >= 1){
                    var arr = [];

                    <?php
                        foreach($mailHosts as $key => $val){
                            echo '
                            arr['.$key.'] = finishMail("'.$val.'", values[0], values[1]);';
                        }
                        echo "\n";
                        echo "if(";
                        foreach($mailHosts as $key => $val){
                            echo '!arr['.$key.'] && ';
                        }
                        echo "true";
                        echo "){removeEndEmail()}";
                    ?>


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