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
    public function add($event){



        if(isset($_POST['add'])){
            $name = $this->db->esc_str($_POST['name']);
            $description = $this->db->esc_str($_POST['description']);

            $date = $this->db->esc_str($_POST['date_day'])."-".$this->db->esc_str($_POST['date_month'])."-".$this->db->esc_str($_POST['date_year']);
            $startdateRegistration = $this->db->esc_str($_POST['startdate_registration_day'])."-".$this->db->esc_str($_POST['startdate_registration_month'])."-".$this->db->esc_str($_POST['startdate_registration_year']);//$this->db->esc_str($_POST['startdate_registration']);
            $enddateRegistration = $this->db->esc_str($_POST['enddate_registration_day'])."-".$this->db->esc_str($_POST['enddate_registration_month'])."-".$this->db->esc_str($_POST['enddate_registration_year']);//$this->db->esc_str($_POST['enddate_registration']);


            $date = date("Y-m-d H:i:s", strtotime($date));
            $startdateRegistration = date("Y-m-d H:i:s", strtotime($startdateRegistration));
            $enddateRegistration = date("Y-m-d H:i:s", strtotime($enddateRegistration));


            $rating = $this->db->esc_str($_POST['rating']);
            $mailConfirm = $this->db->esc_str($_POST['mail_confirm']);


        }else{
            $this->form("?workshops=".$event."&add");
        }







    }

    public function form($action, $name=false, $description=false, $startTime=false, $endTime=false,$maxReg=false, $location=false){

        echo '
        <form action="'.$action.'" method="post">
            <label for="name" class="headLabel">Naam:</label>
            <input type="text" name="name" id="name" value="'.($name != false ? $name : "").'" /><br />

            <label for="description" class="headLabel">Omschrijving:</label>
            <textarea name="description" id="description">'.($description != false ? $description : "").'</textarea><br />

            <label for="form_start_time" class="headLabel">Begin tijd:</label>
            <div id="form_start_time">
                '.$this->core->getHour("start_time",($startTime != false ? $startTime : false)).'
                '.$this->core->getMinutes("start_time",($startTime != false ? $startTime : false)).'
            </div>
            <label for="form_end_time" class="headLabel">Eind tijd:</label>
            <div id="form_end_time">
                '.$this->core->getHour("end_time",($endTime != false ? $endTime : false)).'
                '.$this->core->getMinutes("end_time",($endTime != false ? $endTime : false)).'
            </div>

            <label for="max_reg" class="headLabel">Maximale aanmeldingen:</label>
            <input type="number" name="max_reg" id="max_reg" value="'.($maxReg != false ? $maxReg : "").'" /><br />

            <label for="location" class="headLabel">Locatie</label>
            <input type="text" name="location" id="location" value="'.($location != false ? $location : "").'" /><br />

            <input type="submit" name="add" value="Toevoegen" />
        </form>
        ';
    }


}

?>