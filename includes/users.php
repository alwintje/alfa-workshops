<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 19-11-2015
 * Time: 15:04
 */
class Users{

    private $db;
    private $core;
    private $user;


    public function __construct(Core $core, Database $db, $user){
        $this->db = $db;
        $this->core = $core;
        $this->user = $user;
    }
    public function getAll(){ // Krijg alle gebruikers
        $q = $this->db->doquery("SELECT * FROM {{table}}","users");
        while($row = mysqli_fetch_array($q)){
            echo '<a href="?users&edit='.$row['id'].'">';
            echo $row['firstname']." ".$row['lastname'];
            echo '</a><br />';
        }
    }
    public function edit($id){ // ID van gebruiker

        $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' ","users");
        $row = mysqli_fetch_array($q);
        echo $row['firstname']." ".$row['lastname'];
        echo '<br />';

    }

}

?>


