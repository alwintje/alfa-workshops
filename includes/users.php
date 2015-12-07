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
    private $security;


    public function __construct(Core $core, Database $db, $user, Security $security){
        $this->db = $db;
        $this->core = $core;
        $this->user = $user;
        $this->security = $security;
    }
    public function getAll(){ // Krijg alle gebruikers

        echo '<form action="POST">
                <input type="text" name="search" placeholder="Zoek op naam" />

                <input type="button" name="zoeken" value="Zoek"/>
             </form> <br/>
            ';

        if(!empty(isset($_POST['zoeken']))){
            $search = $_POST['search'];

            $zoekQuery = $this->db->doquery("SELECT * FROM {{table}} WHERE firstname LIKE '.$search.'  ", "users");

            while($row = mysqli_fetch_array($zoekQuery)){
                echo $row['name'];
            }
        }

        $q = $this->db->doquery("SELECT * FROM {{table}}","users");
        while($row = mysqli_fetch_array($q)){

            echo '<article class="text-box">';
            echo "Naam: ".$row['firstname']." ".$row['lastname']."<br/>";
            echo "Email: ".$row['email'];
            echo '<br />';
            echo '<br />';
            echo '<a class="edituser" href="?users&edit='.$row['id'].'">Wijzigen</>  ';
            echo '</article>';
        }
    }
    public function edit($id){ // ID van gebruiker

        if(isset($_POST['edit'])){
            $firstname = $this->db->esc_str($_POST['firstname']);
            $lastname = $this->db->esc_str($_POST['lastname']);
            $email = $this->db->esc_str($_POST['email']);
            $role = $this->db->esc_str($_POST['role']);

            $correctMail = $this->security->isEmail($email);

            $err = [];
            if($correctMail != null){
                $err[] = $correctMail;
            }
            if(strlen($firstname) < 2){$err[] = "Voornaam is niet lang genoeg.";}
            if(strlen($lastname) < 2){$err[] = "Achternaam is niet lang genoeg.";}


            if(count($err) == 0){
                $this->db->doquery("UPDATE {{table}} SET firstname='$firstname', lastname='$lastname', email='$email', role='$role' WHERE id='$id'","users");
                echo "Succesvol aangepast";
            }else{
                foreach($err as $error){
                    echo $error."<br />";
                }
            }

        }


        $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' ","users");
        $row = mysqli_fetch_array($q);
        echo '

        <form action="?users&edit='.$id.'" method="post">
            <label class="headLabel">Voornaam: </label>
            <input value= '.$row['firstname'].' name="firstname" /> <br />
            <label class="headLabel">Achternaam: </label>
            <input value= '.$row['lastname'].' name="lastname" /> <br />
            <label class="headLabel">Email: </label>
            <input value= '.$row['email'].' name="email" />  <br />
            <label for="roles" class="headLabel">Gebruikerslevel:</label>
            <div id="roles" class="trueFalse">
                <input type="radio" name="role" value="0" id="role_zero" '.($row['role'] == 0 ? 'checked="checked"' : "").'/> <label for="role_zero">Gebruiker</label><br />
                <input type="radio" name="role" value="1" id="role_one" '.($row['role'] == 1 ? 'checked="checked"' : "").'/> <label for="role_one">Docent</label><br />
                <input type="radio" name="role" value="2" id="role_two" '.($row['role'] == 2 ? 'checked="checked"' : "").'/> <label for="role_two">Administrator</label>
            </div>
            <label>
            <button type="submit" name="edit">Aanpassen</button>
        </form>

        ';
    }


}

?>


