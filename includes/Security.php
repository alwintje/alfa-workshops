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
                die("Er is iets mis met de sessions (Error 1).");
                unset($_SESSION['alfa-workshops']);
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
        $pass = $this->db->esc_str($pass);

        $query = $this->db->doquery("SELECT * FROM {{table}} WHERE email='$email' AND password='".$this->makePass($pass, $email)."'", "users");

        if (mysqli_num_rows($query) != 1) {
            return 'Verkeerde mail of wachtwoord.';
        }else{
            $_SESSION['alfa-workshops'] = $email."//".$this->makePass($pass, $email);
            $this->core->loadPage("index.php");
        }
        return null;
    }
    public function checkRegister($email, $firstname, $lastname, $password1, $password2){
        $email = $this->db->esc_str($email);
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

        $query = $this->db->doquery("INSERT INTO {{table}} SET email='$email', firstname='$firstname', lastname='$lastname', password='$pass' ","users");

        $_SESSION['alfa-workshops'] = $email."//".$pass;
        $this->core->loadPage("index.php");


        return null;
    }

    public function isEmail($email){
        $mail = explode('@', $email);
        if(count($mail) != 2){
            return "Email is niet juist.";
        }elseif($mail[1] != "alfa-college.nl" && $mail[1] != "student.alfa-college.nl"){
            return "Alleen Alfa-mail adres is toegestaan.";
        }else{
            return null;
        }
    }

    public function makePass($pass, $mail){
        return md5($pass.$mail);
    }


}