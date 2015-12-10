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
    private $user;


    public function __construct(Core $core, Database $db, $user){
        $this->db = $db;
        $this->core = $core;
        $this->user = $user;
    }
    public function getAll(){
        $monthsSmall = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $months = ['January','February','March','April','May','June','July ','August','September','October','November','December'];
        $monthQuery = "";
        $monthStart = date("Y/m/d");
        $monthEnd = date("Y/m/d", strtotime(date("Y-M-d")) + 2592000);
        $monthSmall = $monthsSmall[array_search(date("m"), $months)];

        if(isset($_GET['month'])) {
            $month = $months[array_search($_GET['month'], $monthsSmall)];
            $monthStart = date("Y/m/d", strtotime(date("Y") . " " . $month));
            $monthEnd = date("Y/m/d", strtotime(date("Y") . " " . $month) + 2592000);
            $monthSmall = $_GET['month'];
        }
        $monthQuery = "event_date >= '$monthStart 00:00:00' AND event_date <= '$monthEnd 00:00:00' ";

        if($this->user['role'] == 2){
            $query = $this->db->doquery("SELECT * FROM {{table}} WHERE $monthQuery ORDER BY event_date","events");
        }else{
            $query = $this->db->doquery("SELECT * FROM {{table}} WHERE $monthQuery AND active='1' ORDER BY event_date","events");
        }
        echo '
            <article class="filter">
                <select onchange="changeDate(this)" class="mobile">
                    <option value="Jan">Januari</option>
                    <option value="Feb">Februari</option>
                    <option value="Mar">Maart</option>
                    <option value="Apr">April</option>
                    <option value="May">Mei</option>
                    <option value="Jun">Juni</option>
                    <option value="Jul">Juli</option>
                    <option value="Aug">Augustus</option>
                    <option value="Sep">September</option>
                    <option value="Oct">Oktober</option>
                    <option value="Nov">November</option>
                    <option value="Dec">December</option>
                </select>
                <script>
                    var mob_filter = document.querySelector(".filter .mobile");
                    var mob_filter_options = mob_filter.children;
                    for(var i=0; i < mob_filter_options.length;i++){
                        if(mob_filter_options[i].value == "'.$monthSmall.'"){
                            mob_filter_options[i].setAttribute("selected","selected");
                        }
                    }
                    function changeDate(t){
                        window.location.href = "?month="+t.value;
                    }
                </script>
                <ul>
                    <li>
                        <a href="?month=Jan">Januari</a>
                    </li>
                    <li>
                        <a href="?month=Feb">Februari</a>
                    </li>
                    <li>
                        <a href="?month=Mar">Maart</a>
                    </li>
                    <li>
                        <a href="?month=Apr">April</a>
                    </li>
                    <li>
                        <a href="?month=May">Mei</a>
                    </li>
                    <li>
                        <a href="?month=Jun">Juni</a>
                    </li>
                    <li>
                        <a href="?month=Jul">Juli</a>
                    </li>
                    <li>
                        <a href="?month=Aug">Augustus</a>
                    </li>
                    <li>
                        <a href="?month=Sep">September</a>
                    </li>
                    <li>
                        <a href="?month=Oct">Oktober</a>
                    </li>
                    <li>
                        <a href="?month=Nov">November</a>
                    </li>
                    <li>
                        <a href="?month=Dec">December</a>
                    </li>
                </ul>
            </article>

            ';

        while($row = mysqli_fetch_array($query)){

            $month = date("M", strtotime($row['event_date']));
            echo '
            <article class="text-box '.$month.' '.($row['active'] ? "" : "not-active").'">
                <h1>'.$row['event_date'].'</h1>
                <h2>'.$row['name'].'</h2>
                <p> '.$row['description'].' </p>
                <div class="rightBottom">
            ';
                if($row['startdate_registration'] <= date("Y-m-d") && date("Y-m-d") <= $row['enddate_registration']){
                    echo ' <a class="button" href="?workshops='.$row['id'].'">Aanmelden</a>';
                }else{
                    echo ' <a class="button" href="?workshops='.$row['id'].'">Bekijken</a>';
                }

                if($this->user['role'] == 2){
                    echo ' <a class="button" href="?edit='.$row['id'].'">Aanpassen</a>';
                }
            echo '
                </div>
            </article>
            ';
        }
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
            $maxReg = $this->db->esc_str($_POST['max_registrations']);
            $mailConfirm = $this->db->esc_str($_POST['mail_confirm']);
            $active = $this->db->esc_str($_POST['active']);
            $error = 0;

            // check op name and description
            if(strlen($name) < 2){$error++;echo '<span class="error">Naam moet langer zijn dan 2 tekens.</span>';}
            if(strlen($description) < 10){$error++;echo '<span class="error">Descriptie moet langer zijn dan 10 tekens.</span>';}

            //CHECK OP DATUMS
            if(strlen($startdateRegistration > $enddateRegistration )){$error++; echo '<span class="error">De start datum mag niet groter zijn als de eind datum!</span>';}


            if($error == 0){
                $this->db->doquery("INSERT INTO {{table}} SET name='$name', description='$description', event_date='$date', startdate_registration='$startdateRegistration', enddate_registration='$enddateRegistration', rating='$rating', max_registrations='$maxReg', mail_confirm='$mailConfirm', active='$active'","events");

                echo '<span class="succes">Succesvol toegevoegd!</span>';
                $this->form("?editEvent&add");
            }else{
                $this->form("?editEvent&add",$name, $description, $date, $startdateRegistration, $enddateRegistration, $rating, $mailConfirm,$active);
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
            $maxReg = $this->db->esc_str($_POST['max_registrations']);
            $mailConfirm = $this->db->esc_str($_POST['mail_confirm']);
            $active = $this->db->esc_str($_POST['active']);
            $error = 0;

            if(strlen($name) < 2){$error++;echo '<span class="error">Naam moet langer zijn dan 2 tekens.</span>';}
            if(strlen($description) < 10){$error++;echo '<span class="error">Descriptie moet langer zijn dan 10 tekens.</span>';}

            //CHECK OP DATUMS
            if(strlen($startdateRegistration > $enddateRegistration )){$error++; echo '<span class="error">De start datum mag niet groter zijn als de eind datum!</span>';}

            if($error == 0){
                $this->db->doquery("UPDATE {{table}} SET name='$name', description='$description', event_date='$date', startdate_registration='$startdateRegistration', enddate_registration='$enddateRegistration', rating='$rating', max_registrations='$maxReg', mail_confirm='$mailConfirm', active='$active' WHERE id='$id'","events");

                echo '<span class="succes">Succesvol aangepast!</span>';

                $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1","events");
                $r = mysqli_fetch_array($q);
                $this->form("?editEvent&edit=".$id,$r['name'],$r['description'],$r['event_date'],$r['startdate_registration'],$r['enddate_registration'],$r['rating'],$r['mail_confirm'],$r['active']);
            }else{
                $this->form("?editEvent&edit=".$id,$name, $description, $date, $startdateRegistration, $enddateRegistration, $rating, $mailConfirm, $active);
            }
        }else{
            $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1","events");
            $r = mysqli_fetch_array($q);
            $this->form("?editEvent&edit=".$id,$r['name'],$r['description'],$r['event_date'],$r['startdate_registration'],$r['enddate_registration'],$r['rating'],$r['mail_confirm'],$r['active']);
        }
    }

    private function form($action,$name=false, $description=false, $date=false, $startdateReg=false, $enddateReg=false, $rating=false, $mailConfirm=false, $active=true){

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
            <label for="max_registrations" class="headLabel">Maximaal aantal aanmeldingen per gebruiker</label>
            <select name="max_registrations" id="max_registrations">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
            <label for="rating" class="headLabel">Waarderingen (werkt nog niet):</label>
            <div id="rating" class="trueFalse">
                <input type="radio" name="rating" value="1" id="true_rating" '.($rating ? 'checked="checked"' : "").'/> <label for="true_rating">Ja</label><br />
                <input type="radio" name="rating" value="0" id="false_rating" '.(!$rating ? 'checked="checked"' : "").'/> <label for="false_rating">Nee</label>
            </div>
            <label for="mail_confirm" class="headLabel">Mail bevestiging:</label>
            <div id="mail_confirm" class="trueFalse">
                <input type="radio" name="mail_confirm" value="1" id="true_mail_confirm" '.($mailConfirm ? 'checked="checked"' : "").'/> <label for="true_mail_confirm">Ja</label><br />
                <input type="radio" name="mail_confirm" value="0" id="false_mail_confirm" '.(!$mailConfirm ? 'checked="checked"' : "").'/> <label for="false_mail_confirm">Nee</label>
            </div>
            <label for="active" class="headLabel">Activatie:</label>
            <div id="active" class="trueFalse">
                <input type="radio" name="active" value="1" id="true_active" '.($active ? 'checked="checked"' : "").'/> <label for="true_active">Actief</label><br />
                <input type="radio" name="active" value="0" id="false_active" '.(!$active ? 'checked="checked"' : "").'/> <label for="false_active">Niet actief</label>
            </div>
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


