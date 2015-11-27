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
    public function edit($id, $action){ // ID van gebruiker
        $q = $this->db->doquery("SELECT * FROM {{table}} WHERE id='$id' ","users");
        $row = mysqli_fetch_array($q);
//        $action = "?user&edit=$id";

        echo '<form action="'.$action.'" method="post">
            <label>Voornaam: </label>
            <input value= '.$row['firstname'].'> </input> <br />
            <label>Achternaam: </label>
            <input value= '.$row['lastname'].'> </input> <br />
            <label>Email: </label>
            <input value= '.$row['email'].'> </input> <br />
            <label>Gebruikerslevel:</label>
            <select>
                <option value='.$row['role'].'> </option>
            </select>
            <label>
            <button type="submit" name="submit">Verander</button>
        </form>
        ';

        if(isset($_POST['submit'])){
            $error = 0;
            $firstName = $this->db->esc_str(isset($_POST['firstname']));
            $lastName = $this->db->esc_str(isset($_POST['lastname']));
            $email = $this->db->esc_str(isset($_POST['email']));
            $role = $this->db->esc_str(isset($_POST['role']));

            if($error == 0){
                $this->db->doquery("UPDATE {{table}} SET firstname='$firstName', lastname='$lastName', email='$email', role='$role' WHERE id='$id'","users");
                echo "succes";
            }

        }
    }


}

?>


