<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 6-7-2015
 * Time: 13:13
 */


class Security{

    private $db;
    private $core;

    public function __construct(Core $core, Database $db){
        $this->db = $db;
        $this->core = $core;
    }

    public function checksession(){

        $row = false;

        if (isset($_SESSION['alfa-workshops'])) {
            $theuser = explode("//", $_SESSION['alfa-workshops']);
            $query = $this->db->doquery("SELECT * FROM {{table}} WHERE email='$theuser[0]' AND password='$theuser[1]'", "users");

            if (mysqli_num_rows($query) != 1) {
                unset($_SESSION['alfa-workshops']);
                die("Er is iets mis met de sessions (Error 1).");
            }
            $row = mysqli_fetch_array($query);
        }

        return $row;
    }
    public function logout(){

        unset($_SESSION['alfa-workshops']);
        $this->core->loadPage("index.php");
    }
    public function checkLogin($email, $pass){
        $email = $this->db->esc_str($email);
        $email = strtolower($email);
        $pass = $this->db->esc_str($pass);

        $query = $this->db->doquery("SELECT * FROM {{table}} WHERE email='$email' AND password='".$this->makePass($pass, $email)."'", "users");

        if (mysqli_num_rows($query) != 1) {
            return 'Verkeerde mail of wachtwoord.';
        }else{
            $r = mysqli_fetch_array($query);
            if($r['validated']){
                $_SESSION['alfa-workshops'] = $email."//".$this->makePass($pass, $email);
            }else{
                return 'U hebt u nog niet gevalideerd.';
            }
            $this->core->loadPage("index.php");
        }
        return null;
    }
    public function checkRegister($email, $firstname, $lastname, $password1, $password2){
        $email = $this->db->esc_str($email);
        $email = strtolower($email);
        $firstname = $this->db->esc_str($firstname);
        $lastname = $this->db->esc_str($lastname);
        $password1 = $this->db->esc_str($password1);
        $password2 = $this->db->esc_str($password2);



        $query = $this->db->doquery("SELECT * FROM {{table}} WHERE email='$email'", "users");

        if (mysqli_num_rows($query) > 0) {
            return 'Dit email adres is bij ons al geregistreerd.';
        }

        $correctMail = $this->isEmail($email);

        if($correctMail != null){
            return $correctMail;
        }
        $err = [];
        if(strlen($firstname) < 2){$err[] = "Voornaam is niet lang genoeg.";}
        if(strlen($lastname) < 2){$err[] = "Achternaam is niet lang genoeg.";}
        if(strlen($password1) < 2){$err[] = "Wachtwoord is niet lang genoeg.";}
        if($password1 != $password2){$err[] = "Wachtwoorden komen niet overeen.";}

        if(count($err) != 0){
            $error = "";
            foreach($err as $val){
                $error .= $val."<br />";
            }
            return $error;
        }

        $pass = $this->makePass($password1, $email);
        $validate = rand(11111,99999);


        $headers = "From: no-reply@workshopsalfacollege.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $id = $this->db->doQueryWithId("INSERT INTO {{table}} SET email='$email', firstname='$firstname', lastname='$lastname', password='$pass', validate='$validate', edit_pass_token='".rand(11111,99999)."', validated='1'  ","users");

//        $message = '
//            Hallo '.$firstname.', <br />
//            <br />
//            U moet uw account nog valideren. Dit kunt u doen door op de link hieronder te klikken:<br />
//            <a href="http://workshopsalfacollege.com/account.php?validation='.$validate.'&id='.$id.'">http://workshopsalfacollege.com/account.php?validation='.$validate.'&id='.$id.'</a><br />
//            <br />
//            Met vriendelijke groet, <br />
//            <br />
//            Workshops Alfa-College
//        ';
        $message = '
            Hallo '.$firstname.', <br />
            <br />
            Bedankt voor uw registratie. U kunt nu inloggen met uw email en wachtwoord.<br />
            <br />
            Met vriendelijke groet, <br />
            <br />
            Workshops Alfa-College
        ';

        mail($email, "Workshops Alfa-College",$message,$headers);

//        $_SESSION['alfa-workshops'] = $email."//".$pass;
        //$this->core->loadPage("index.php");



        return null;
    }
    public function checkMail(){

        $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='1'","users");
        $r = mysqli_fetch_array($q);
        $headers = "From: no-reply@workshopsalfacollege.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $email = $r['email'];
        $firstname = $r['firstname'];
        $validate = rand(11111,99999);

        $id = 1;
//        $id = $this->db->doQueryWithId("INSERT INTO {{table}} SET email='$email', firstname='$firstname', lastname='$lastname', password='$pass', validate='$validate'  ","users");

//        $message = '
//            Hallo '.$firstname.', <br />
//            <br />
//            U moet uw account nog valideren. Dit kunt u doen door op de link hieronder te klikken:<br />
//            <a href="http://workshopsalfacollege.com/account.php?validation='.$validate.'&id='.$id.'">http://workshopsalfacollege.com/account.php?validation='.$validate.'&id='.$id.'</a><br />
//            <br />
//            Met vriendelijke groet, <br />
//            <br />
//            Workshops Alfa-College
//        ';
        $message = '
            Hallo '.$firstname.', <br />
            <br />
            Bedankt voor uw registratie. U kunt nu inloggen met uw email en wachtwoord.<br />
            <br />
            Met vriendelijke groet, <br />
            <br />
            Workshops Alfa-College
        ';


        if(@mail($email, "Workshops Alfa-College validatie",$message,$headers)){
            echo "mail verstuurd";
        }else{
            echo "mail niet verstuurd";
        }
    }
    public function forgotPass($email){
        $q = $this->db->doquery("SELECT * FROM {{table}} WHERE email='$email'","users");
        if(mysqli_num_rows($q) <= 0){
            return "Geen gebruiker gevonden.";
        }
        $r = mysqli_fetch_array($q);

        $headers = "From: no-reply@workshopsalfacollege.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $email = $r['email'];
        $email = strtolower($email);
        $firstname = $r['firstname'];
        $token = rand(11111,99999);
        $this->db->doquery("UPDATE {{table}} SET edit_pass_token='$token' WHERE id='".$r['id']."'", "users");


        $message = '
            Hallo '.$firstname.', <br />
            <br />
            U wilt uw wachtwoord wijzigen. Klik op de link hieronder om uw wachtwoord te wijzigen:<br />
            <a href="http://workshopsalfacollege.com/account.php?id='.$r['id'].'&checkCode='.$token.'">http://workshopsalfacollege.com/account.php?id='.$r['id'].'&checkCode='.$token.'</a><br />
            <br />
            Met vriendelijke groet, <br />
            <br />
            Workshops Alfa-College
        ';

        mail($email, "Wachtwoord vergeten - workshops Alfa-College",$message,$headers);
        return null;
    }
    public function changePass($id, $token, $pass){

        $q = $this->db->doquery("SELECT email, edit_pass_token FROM {{table}} WHERE id='$id'","users");
        if(mysqli_num_rows($q) <= 0){
            return "Geen gebruiker gevonden.";
        }
        $r = mysqli_fetch_array($q);

        if($r['edit_pass_token'] != $token){
            return "Token is niet juist of het wachtwoord is al aangepast.";
        }

        $email = $r['email'];
        $email = strtolower($email);
        $token = rand(11111,99999);
        $this->db->doquery("UPDATE {{table}} SET password='".$this->makePass($pass,$email)."', edit_pass_token='$token' WHERE id='$id'", "users");

        return null;

    }

    public function isEmail($email){
        $mail = explode('@', $email);



        if(count($mail) != 2){
            return "Email is niet juist.";
        }elseif(!$this->emailIsAlfaMail($mail[1])){
            return "Alleen Alfa-mail adres is toegestaan.";
        }else{
            return null;
        }

    }
    public function emailIsAlfaMail($mail){
        $q = $this->db->doquery("SELECT * FROM {{table}} WHERE active='1' LIMIT 1","settings");
        $r = mysqli_fetch_array($q);


        $isAlfaMail = false;
        $mails = explode(",", $r['email_hosts']);
        foreach($mails as $val){
            $isAlfaMail = $mail == $val ? true : $isAlfaMail;
        }
        return $isAlfaMail;

    }
    public function makePass($pass, $mail){
        return md5($pass.$mail);
    }

}