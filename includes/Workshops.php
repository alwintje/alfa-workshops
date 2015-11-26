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
            echo '<div class="rightBottom">';
            $registered_q = $this->db->doquery("SELECT * FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$row['id']."' ","registrations");

            if($inDate){

                if(mysqli_num_rows($registered_q) > 0){
                    echo '<a class="button" href="?workshops='.$id.'&unregister='.$row['id'].'">Afmelden</a>';
                }else{
                    echo '<a class="button" href="?workshops='.$id.'&register='.$row['id'].'">Aanmelden</a>';
                }
            }else{
                if(mysqli_num_rows($registered_q) > 0){
                    echo '<a class="button" href="#">Aangemeld</a>';
                }
            }
            echo '</div>';
            echo "</article>";
        }
    }
    public function add($event){
        echo '
        <a class="button" href="?workshops='.$event.'">Terug</a><br />';


        if(isset($_POST['add'])){
            $name = $this->db->esc_str($_POST['name']);
            $description = $this->db->esc_str($_POST['description']);

            $startTime = $this->db->esc_str( $_POST['start_time_hour'] ) . ":" . $this->db->esc_str( $_POST['start_time_minutes'] ) . ":00";
            $endTime = $this->db->esc_str( $_POST['end_time_hour'] ) . ":" . $this->db->esc_str( $_POST['end_time_minutes'] ) . ":00";

            $startTime = date("H:i:s", strtotime($startTime));
            $endTime = date("H:i:s", strtotime($endTime));


            $maxReg = $this->db->esc_str($_POST['max_reg']);
            $location = $this->db->esc_str($_POST['location']);
            $error = 0;

            // check op name and description
            if(strlen($name) < 2){$error++;echo '<span class="error">Naam moet langer zijn dan 2 tekens.</span>';}
            if(strlen($description) < 10){$error++;echo '<span class="error">Descriptie moet langer zijn dan 10 tekens.</span>';}
            if(strlen($location) < 2){$error++;echo '<span class="error">Locatie moet langer zijn dan 2 tekens.</span>';}

            //CHECK OP DATUMS
            if(strlen($startTime > $endTime )){$error++; echo '<span class="error">De start tijd mag niet hoger zijn als de eind tijd!</span>';}


            if($error == 0){
                $this->db->doquery("INSERT INTO {{table}} SET name='$name', description='$description', start_time='$startTime', end_time='$endTime', max_registration='$maxReg', location='$location', event='$event'","workshops");

                echo '<span class="succes">Succesvol toegevoegd!</span>';
                $this->form($event,"add");
            }else{
                $this->form($event,"add",$name, $description, $startTime, $endTime, $maxReg, $location);
            }

        }else{
            $this->form($event, "add");
        }







    }

    public function form($event, $action, $name=false, $description=false, $startTime=false, $endTime=false,$maxReg=false, $location=false){

        $action = "?workshops=".$event."&".$action;
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
            <input type="number" name="max_reg" id="max_reg" value="'.($maxReg != false ? $maxReg : "0").'" /><br />

            <label for="location" class="headLabel">Locatie</label>
            <input type="text" name="location" id="location" value="'.($location != false ? $location : "").'" /><br />

            <input type="submit" name="add" value="Toevoegen" />
        </form>
        ';
    }


}

?>