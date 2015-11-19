<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 19-11-2015
 * Time: 15:03
 */


class Workshops {


    private $db;
    private $core;
    private $user;


    public function __construct(Core $core, Database $db, $user){
        $this->db = $db;
        $this->core = $core;
        $this->user = $user;
    }

    public function getWorkshops($id){

        $query = $this->db->doquery("SELECT * FROM {{table}} WHERE event='$id' ","workshops");

        $event_q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' ", "events");

        if(isset($_GET['register'])){
            $registered_q = $this->db->doquery("SELECT * FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$_GET['register']."' ","registrations");

            if(mysqli_num_rows($registered_q) <= 0){
                $this->db->doquery("INSERT INTO {{table}} SET  user_id='" . $this->user['id'] . "', workshop_id='" . $_GET['register'] . "' ", "registrations");
            }
        }elseif(isset($_GET['unregister'])){
            $registered_q = $this->db->doquery("SELECT * FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$_GET['unregister']."' ","registrations");

            if(mysqli_num_rows($registered_q) > 0){
                $this->db->doquery("DELETE FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$_GET['unregister']."' ","registrations");
            }
        }

        $event = mysqli_fetch_array($event_q);
        $inDate = false;
        if($event['startdate_registration'] <= date("Y-m-d") && date("Y-m-d") <=  $event['enddate_registration']){
            $inDate = true;
        }

        while($row = mysqli_fetch_array($query)) {
            echo '<article class="text-box">';
            echo '<h2>'.$row['name'].' - '.$row['description'].'</h2>';
            $registered_q = $this->db->doquery("SELECT * FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$row['id']."' ","registrations");

            if($inDate){

                if(mysqli_num_rows($registered_q) > 0){
                    echo '<a href="?workshops='.$id.'&unregister='.$row['id'].'">Afmelden</a>';
                }else{
                    echo '<a href="?workshops='.$id.'&register='.$row['id'].'">Aanmelden</a>';
                }
            }else{
                if(mysqli_num_rows($registered_q) > 0){
                    echo '<a href="#">Aangemeld</a>';
                }
            }
            echo "</article>";

        }
    }


}

?>