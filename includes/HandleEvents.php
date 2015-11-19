<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 19-11-2015
 * Time: 15:04
 */
class HandleEvents{

    private $db;
    private $core;


    public function __construct(Core $core, Database $db){
        $this->db = $db;
        $this->core = $core;
    }
    public function add(){

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
            $error = 0;

            // check op name and description
            if(strlen($name) < 2){$error++;echo 'Naam moet langer zijn dan 2 tekens.<br />';}
            if(strlen($description) < 10){$error++;echo 'Descriptie moet langer zijn dan 10 tekens.<br />';}

            //CHECK OP DATUMS
            if(strlen($startdateRegistration > $enddateRegistration )){$error++; echo "De startdatum mag niet groter zijn!";}


            if($error == 0){
                $this->db->doquery("INSERT INTO {{table}} SET name='$name', description='$description', event_date='$date', startdate_registration='$startdateRegistration', enddate_registration='$enddateRegistration', rating='$rating', mail_confirm='$mailConfirm'","events");
                $this->form("?editEvent&add");
            }else{
                $this->form("?editEvent&add",$name, $description, $date, $startdateRegistration, $enddateRegistration, $rating, $mailConfirm);
            }
        }else{
            $this->form("?editEvent&add");
        }
    }
    public function edit($id){

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
            $error = 0;


            if(strlen($name) < 2){$error++;echo 'Naam moet langer zijn dan 2 tekens.<br />';}
            if(strlen($description) < 10){$error++;echo 'Descriptie moet langer zijn dan 10 tekens.<br />';}

            //TODO CHECK OP DATUMS

            if($error == 0){
                $this->db->doquery("UPDATE {{table}} SET name='$name', description='$description', event_date='$date', startdate_registration='$startdateRegistration', enddate_registration='$enddateRegistration', rating='$rating', mail_confirm='$mailConfirm' WHERE id='$id'","events");

                $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1","events");
                $r = mysqli_fetch_array($q);
                $this->form("?editEvent&edit=".$id,$r['name'],$r['description'],$r['event_date'],$r['startdate_registration'],$r['enddate_registration'],$r['rating'],$r['mail_confirm']);
            }else{
                $this->form("?editEvent&edit=".$id,$name, $description, $date, $startdateRegistration, $enddateRegistration, $rating, $mailConfirm);
            }
        }else{
            $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1","events");
            $r = mysqli_fetch_array($q);
            $this->form("?editEvent&edit=".$id,$r['name'],$r['description'],$r['event_date'],$r['startdate_registration'],$r['enddate_registration'],$r['rating'],$r['mail_confirm']);
        }
    }

    private function form($action,$name=false, $description=false, $date=false, $startdateReg=false, $enddateReg=false, $rating=false, $mailConfirm=false){

        echo '
        <form action="'.$action.'" method="post">
            <label for="name" class="headLabel">Naam:</label>
            <input type="text" name="name" id="name" value="'.($name != false ? $name : "").'" /><br />
            <label for="description" class="headLabel">Omschrijving:</label>
            <textarea name="description" id="description">'.($description != false ? $description : "").'</textarea><br />
            <label for="form_date" class="headLabel">Datum:</label>
            <div id="form_date">
                '.$this->core->getDay("date",($date != false ? $date : false)).'
                '.$this->core->getMonth("date",($date != false ? $date : false)).'
                '.$this->core->getYear("date",($date != false ? $date : false)).'
            </div>
            <label for="form_startdate_registration" class="headLabel">Begin aanmeldingen:</label>
            <div id="form_startdate_registration">
                '. $this->core->getDay("startdate_registration",($startdateReg != false ? $startdateReg : false)) .'
                '. $this->core->getMonth("startdate_registration",($startdateReg != false ? $startdateReg : false)) .'
                '. $this->core->getYear("startdate_registration",($startdateReg != false ? $startdateReg : false)) .'
            </div>

            <label for="form_enddate_registration" class="headLabel">Laatste dag aanmeldingen:</label>
            <div id="form_enddate_registration">
                '. $this->core->getDay("enddate_registration",($enddateReg != false ? $enddateReg : false)) .'
                '. $this->core->getMonth("enddate_registration",($enddateReg != false ? $enddateReg : false)) .'
                '. $this->core->getYear("enddate_registration",($enddateReg != false ? $enddateReg : false)) .'
            </div>

            <label for="rating" class="headLabel">Waarderingen:</label>
            <div id="rating">
                <input type="radio" name="rating" value="true" id="true_rating" '.($rating ? 'checked="checked"' : "").'/> <label for="true_rating">Ja</label>
                <input type="radio" name="rating" value="false" id="false_rating" '.(!$rating ? 'checked="checked"' : "").'/> <label for="false_rating">Nee</label>
            </div>
            <label for="mail_confirm" class="headLabel">Mail bevestiging:</label>
            <input type="radio" name="mail_confirm" value="true" id="true_mail_confirm" '.($mailConfirm ? 'checked="checked"' : "").'/> <label for="true_mail_confirm">Ja</label>
            <input type="radio" name="mail_confirm" value="false" id="false_mail_confirm" '.(!$mailConfirm ? 'checked="checked"' : "").'/> <label for="false_mail_confirm">Nee</label>
            <br /><br />
            <input type="submit" name="add" value="Toevoegen" />
        </form>
        ';
    }
}
/*
//if(isset($_GET['add'])){
//
//    ?>
<!---->
<!--    <article class="text-box">-->
<!--        --><?php
//        if(isset($_POST['add'])){
//            $name = $this->db->esc_str($_POST['name']);
//            $description = $this->db->esc_str($_POST['description']);
//
//            $date = $this->db->esc_str($_POST['date_day'])."-".$db->esc_str($_POST['date_month'])."-".$db->esc_str($_POST['date_year']);
//            $startdateRegistration = $db->esc_str($_POST['startdate_registration_day'])."-".$db->esc_str($_POST['startdate_registration_month'])."-".$db->esc_str($_POST['startdate_registration_year']);//$db->esc_str($_POST['startdate_registration']);
//            $enddateRegistration = $db->esc_str($_POST['enddate_registration_day'])."-".$db->esc_str($_POST['enddate_registration_month'])."-".$db->esc_str($_POST['enddate_registration_year']);//$db->esc_str($_POST['enddate_registration']);
//
//
//            $date = date("Y-m-d H:i:s", strtotime($date));
//            $startdateRegistration = date("Y-m-d H:i:s", strtotime($startdateRegistration));
//            $enddateRegistration = date("Y-m-d H:i:s", strtotime($enddateRegistration));
//
//
//            $rating = $db->esc_str($_POST['rating']);
//            $mailConfirm = $db->esc_str($_POST['mail_confirm']);
//            $error = 0;
//            if(strlen($name) < 2){$error++;echo 'Naam moet langer zijn dan 2 tekens.<br />';}
//            if(strlen($description) < 10){$error++;echo 'Descriptie moet langer zijn dan 10 tekens.<br />';}
//            if($error == 0){
//                $q = $db->doquery("INSERT INTO {{table}} set name='$name', description='$description', event_date='$date', startdate_registration='$startdateRegistration', enddate_registration='$enddateRegistration', rating='$rating', mail_confirm='$mailConfirm'","events");
//            }
//        }
//        ?>
<!--        <form action="?editEvent&add" method="post">-->
<!--            <label for="name" class="headLabel">Naam:</label>-->
<!--            <input type="text" name="name" id="name" /><br />-->
<!--            <label for="description" class="headLabel">Omschrijving:</label>-->
<!--            <textarea name="description" id="description"></textarea><br />-->
<!--            <label for="form_date" class="headLabel">Datum:</label>-->
<!--            <div id="form_date">-->
<!--                --><?php //$core->getDay("date"); ?>
<!--                --><?php //$core->getMonth("date"); ?>
<!--                --><?php //$core->getYear("date"); ?>
<!--            </div>-->
<!--            <!--                        <input type="date" name="date" id="date" class="datePicker"/><br />-->-->
<!---->
<!---->
<!--            <label for="form_startdate_registration" class="headLabel">Begin aanmeldingen:</label>-->
<!--            <div id="form_startdate_registration">-->
<!--                --><?php //$core->getDay("startdate_registration"); ?>
<!--                --><?php //$core->getMonth("startdate_registration"); ?>
<!--                --><?php //$core->getYear("startdate_registration"); ?>
<!--            </div>-->
<!---->
<!--            <label for="form_enddate_registration" class="headLabel">Laatste dag aanmeldingen:</label>-->
<!--            <div id="form_enddate_registration">-->
<!--                --><?php //$core->getDay("enddate_registration"); ?>
<!--                --><?php //$core->getMonth("enddate_registration"); ?>
<!--                --><?php //$core->getYear("enddate_registration"); ?>
<!--            </div>-->
<!---->
<!--            <label for="rating" class="headLabel">Waarderingen:</label>-->
<!--            <div id="rating">-->
<!--                <input type="radio" name="rating" value="true" id="true_rating" /> <label for="true_rating">Ja</label>-->
<!--                <input type="radio" name="rating" value="false" id="false_rating"/> <label for="false_rating">Nee</label>-->
<!--            </div>-->
<!--            <label for="mail_confirm" class="headLabel">Mail bevestiging:</label>-->
<!--            <input type="radio" name="mail_confirm" value="true" id="true_mail_confirm" /> <label for="true_mail_confirm">Ja</label>-->
<!--            <input type="radio" name="mail_confirm" value="false" id="false_mail_confirm"/> <label for="false_mail_confirm">Nee</label>-->
<!--            <br /><br />-->
<!--            <input type="submit" name="add" value="Toevoegen" />-->
<!--        </form>-->
<!--    </article>-->
<!---->
<!--    --><?php
//
//}

 */
?>