<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 19-11-2015
 * Time: 15:03
 */

// TODO


$query = $db->doquery("SELECT * FROM {{table}} WHERE event='".$_GET['workshops']."' ","workshops");

$event_q = $db->doquery("SELECT * FROM {{table}} WHERE id='".$_GET['workshops']."' ", "events");

if(isset($_GET['register'])){
    $registered_q = $db->doquery("SELECT * FROM {{table}} WHERE user_id='".$user['id']."' AND workshop_id='".$_GET['register']."' ","registrations");

    if(mysqli_num_rows($registered_q) <= 0){
        $db->doquery("INSERT INTO {{table}} SET  user_id='" . $user['id'] . "', workshop_id='" . $_GET['register'] . "' ", "registrations");
    }
}elseif(isset($_GET['unregister'])){
    $registered_q = $db->doquery("SELECT * FROM {{table}} WHERE user_id='".$user['id']."' AND workshop_id='".$_GET['unregister']."' ","registrations");

    if(mysqli_num_rows($registered_q) > 0){
        $db->doquery("DELETE FROM {{table}} WHERE user_id='".$user['id']."' AND workshop_id='".$_GET['unregister']."' ","registrations");
    }
}

$event = mysqli_fetch_array($event_q);
$inDate = false;
if($event['startdate_registration'] <= date("Y-m-d") && date("Y-m-d") <=  $event['enddate_registration']){
    $inDate = true;
}

while($row = mysqli_fetch_array($query)){
    ?>
    <article class="text-box">
        <h2><?php echo $row['name']; ?> - <?php echo $row['location']; ?></h2>
        <p> <?php echo $row['description']; ?> </p>
        <?php
        $registered_q = $db->doquery("SELECT * FROM {{table}} WHERE user_id='".$user['id']."' AND workshop_id='".$row['id']."' ","registrations");

        if($inDate){

            if(mysqli_num_rows($registered_q) > 0){
                echo '<a href="?workshops='.$_GET['workshops'].'&unregister='.$row['id'].'">Afmelden</a>';
            }else{
                echo '<a href="?workshops='.$_GET['workshops'].'&register='.$row['id'].'">Aanmelden</a>';
            }
        }else{
            if(mysqli_num_rows($registered_q) > 0){
                echo '<a href="#">Aangemeld</a>';
            }
        }
        ?>

    </article>
    <?php
}
?>