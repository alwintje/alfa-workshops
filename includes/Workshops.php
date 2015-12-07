<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 19-11-2015
 * Time: 15:03
 */

//$registered_users = $this->db->doquery("SELECT * FROM {{table}} WHERE workshop_id='".$r['id']."' AND user_id='".$this->user['id']."' ", "registrations");
//if(mysqli_num_rows($registered_users) > 1){
//    while($row = mysqli_fetch_array($registered_users)){
//        echo $row['firstname']. "</br>";
//        echo $row['lastname'];
//    }
//}else{
//    echo "Niemand heeft zich nog aangemeld voor deze workshop!";
//}
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
        $event = mysqli_fetch_array($event_q);

        $maxRegEvent = $event['max_registrations'];
        $registrations = 0;
        while($r = mysqli_fetch_array($query)){
            $q = $this->db->doquery("SELECT * FROM {{table}} WHERE workshop_id='".$r['id']."' AND user_id='".$this->user['id']."' ", "registrations");
            if(mysqli_num_rows($q) > 0){
                $registrations++;
            }
        }
        if(isset($_GET['register'])){
            if($registrations < $maxRegEvent){

                $registered_q = $this->db->doquery("SELECT * FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$_GET['register']."' ","registrations");

                if(mysqli_num_rows($registered_q) <= 0){
                    $this->db->doquery("INSERT INTO {{table}} SET  user_id='" . $this->user['id'] . "', workshop_id='" . $_GET['register'] . "' ", "registrations");

                    $workshop_q = $this->db->doquery("SELECT name, start_time, end_time, location, event FROM {{table}} WHERE id='".$_GET['register']."' ","workshops");
                    $workshop_r = mysqli_fetch_array($workshop_q);
                    $events_q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='".$workshop_r['event']."'","events");
                    $events_r = mysqli_fetch_array($events_q);
                    if($events_r['mail_confirm']){
                        $headers = "From: no-reply@workshopsalfacollege.com\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        $startTime = date("H:i",strtotime($workshop_r['start_time']));
                        $endTime = date("H:i",strtotime($workshop_r['end_time']));

                        $message = '
                            Hallo '.$this->user['firstname'].', <br />
                            <br />
                            U bent aangemeld voor de workshop '.$workshop_r['name'].'. Het begint om '.$startTime.' en is om '.$endTime.' afgelopen. Het wordt gehouden in '.$workshop_r['location'].'.<br />
                            <br />
                            Met vriendelijke groet, <br />
                            <br />
                            Workshops Alfa-College
                        ';

                        mail($this->user['email'], "Aanmelding - Workshops Alfa-College",$message,$headers);
                    }
                    $registrations++;
                }
            }
        }elseif(isset($_GET['unregister'])){
            $registered_q = $this->db->doquery("SELECT * FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$_GET['unregister']."' ","registrations");

            if(mysqli_num_rows($registered_q) > 0){
                $this->db->doquery("DELETE FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$_GET['unregister']."' ","registrations");
                $registrations--;
            }
        }
        $inDate = false;
        if($event['startdate_registration'] <= date("Y-m-d") && date("Y-m-d") <=  $event['enddate_registration']){
            $inDate = true;
        }

        $query = $this->db->doquery("SELECT * FROM {{table}} WHERE event='$id' ","workshops");
        while($row = mysqli_fetch_array($query)) {


            $regs_q = $this->db->doquery("SELECT * FROM {{table}} WHERE workshop_id='".$r['id']."' ", "registrations");
            $registrationsForThisWorkshop = mysqli_num_rows($regs_q);


            echo '<article class="text-box">';
            echo '<h2>'.$row['name'].' - '.$row['description'].'</h2>';
            echo '<div class="rightBottom">';
            $registered_q = $this->db->doquery("SELECT * FROM {{table}} WHERE user_id='".$this->user['id']."' AND workshop_id='".$row['id']."' ","registrations");
            if($this->user['role'] != 1 && $this->user['role'] != 2){
                if($inDate){
                    if(mysqli_num_rows($registered_q) > 0){
                        echo '<a class="button" href="?workshops='.$id.'&unregister='.$row['id'].'">Afmelden</a>';
                    }else{
                        if ($registrations < $maxRegEvent && $registrationsForThisWorkshop < $row['max_registration']) {
                            echo '<a class="button" href="?workshops=' . $id . '&register=' . $row['id'] . '">Aanmelden</a>';
                        }
                    }
                }else{
                    if(mysqli_num_rows($registered_q) > 0){
                        echo '<a class="button" href="#">Aangemeld</a>';
                    }
                }
            }
            if($this->user['role'] == 2){
                echo ' <a class="button" href="?workshops=' . $id . '&edit=' . $row['id'] . '">Aanpassen</a>';
            }
            if($this->user['role'] == 2 || $this->user['role'] == 1){
                echo ' <a class="button" href="?workshops=' . $id . '&show=' . $row['id'] . '">Bekijk</a>';
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
                $this->form($event,"add", false);
            }else{
                $this->form($event,"add", false,$name, $description, $startTime, $endTime, $maxReg, $location);
            }

        }else{
            $this->form($event, "add", false);
        }







    }
    public function edit($event,$id){
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
                $this->db->doquery("UPDATE {{table}} SET name='$name', description='$description', start_time='$startTime', end_time='$endTime', max_registration='$maxReg', location='$location', event='$event' WHERE id='$id'","workshops");

                echo '<span class="succes">Succesvol toegevoegd!</span>';
                $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1","workshops");
                $r = mysqli_fetch_array($q);
                $this->form($event, "edit=".$id, true,$r['name'],$r['description'],$r['start_time'],$r['end_time'],$r['max_registration'],$r['location']);
            }else{
                $this->form($event,"edit=".$id, true,$name, $description, $startTime, $endTime, $maxReg, $location);
            }

        }else{
            if(isset($_POST['delete'])){
                echo '
                <form action="?workshops='.$event.'&edit='.$id.'" method="post">
                    Weet u het zeker? <br />
                    <input type="submit" name="yesDelete" value="Ja" /><input type="submit" name="no" value="Nee" />
                </form>
                ';
            }elseif(isset($_POST['yesDelete'])){

                $this->db->doquery("DELETE FROM {{table}} WHERE workshop_id='$id'","registrations");
                $this->db->doquery("DELETE FROM {{table}} WHERE id='$id'","workshops");


                $this->core->loadPage("?workshops=".$event);

            }else{
                $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1","workshops");
                $r = mysqli_fetch_array($q);
                $this->form($event, "edit=".$id, true,$r['name'],$r['description'],$r['start_time'],$r['end_time'],$r['max_registration'],$r['location']);
            }
            //$this->form("?editEvent&edit=".$id,$r['name'],$r['description'],$r['event_date'],$r['startdate_registration'],$r['enddate_registration'],$r['rating'],$r['mail_confirm']);
        }







    }
    public function show($event, $id){

        $q = $this->db->doquery("SELECT * FROM {{table}} WHERE workshop_id='$id'","registrations");
        while($row = mysqli_fetch_array($q)){
            $query = $this->db->doquery("SELECT * FROM {{table}} WHERE id='".$row['user_id']."'","users");
            $r = mysqli_fetch_array($query);
            echo " ".$r['firstname']." ".$r['lastname']."<br />";
        }

    }

    public function form($event, $action,$delete=false, $name=false, $description=false, $startTime=false, $endTime=false,$maxReg=false, $location=false){

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
            '.($delete ? '<input type="submit" name="delete" value="Verwijderen" />' : "").'
        </form>
        ';
    }


}

?>