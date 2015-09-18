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

    public function checkLogin($user, $pass){
        $user = $this->db->esc_str($user);
        $pass = $this->db->esc_str($pass);

        $query = $this->db->doquery("SELECT * FROM {{table}} WHERE username='$user' AND password='".md5($pass)."'", "users");

        if (mysqli_num_rows($query) != 1) {
            return 'Wrong username or password';
        }else{
            $_SESSION['alfa-workshops'] = $user."//".md5($pass);
            $this->core->loadPage("?page=events");
        }
        return null;
    }

}