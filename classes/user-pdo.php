<?php

class user {
    private $id;
    private $connect;
    private $db;
    public $login;
    public $password;

    public function __construct($login, $password, $bddpass = null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->bddpass = $bddpass;
        $this->db = $this->db_connect();
    }

    
    public function db_connect() { //static??

        $host = 'localhost';
        $dbs   = 'reservationsalles';
        $user = 'root';
        $password = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$dbs;charset=$charset";
        try {
            $db = new PDO($dsn, $user, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db; 
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }
    }

    /*public function checklogin() {
        //on fait la requête dans la bd pour rechercher si ces données existent:
        $sql= "SELECT * FROM `utilisateurs` WHERE `login`='$login'";
        $data = $this->db->prepare($sql);
        $data->execute(['login'=>$login]);
        $row = $data->fetch(PDO::FETCH_ASSOC);
        if(count($row) >0)
        {
            echo 'login exist déjà';
            exit;
        }// fin login exist déjà
    }*/

    public function register() {

        $this->db_connect();

        
        if (isset($_POST['inscription']))
        {
            $login = htmlspecialchars(trim($_POST['login']));
            $password = htmlspecialchars(trim($_POST['password']));
            $confpass = htmlspecialchars(trim($_POST['confirm_pass']));

            if (empty($login)){
                echo 'Entrez un login';
            }
            else if (empty($password)) {
                echo 'Entrez un mdp';
            }
            else if (strlen($password) <3) {
                echo 'MDP doit faire au moins 3 charactères';
            }
            else if ($password != $confpass) {
                echo 'Vos mots de passes sont différents';
            }
            else
            {
                $sql='SELECT login FROM utilisateurs WHERE login = :login';
                $select_stmpdo=$this->db_connect()->prepare($sql);
                $select_stmpdo->bindParam(':login', $login); //???
                $select_stmpdo->execute();

                if($select_stmpdo->rowCount() > 0){
                    echo "Login déjà prit";
                }
                else if (!isset($errorMsg)) {
                    $hashpass = password_hash($password, PASSWORD_DEFAULT);
                    $sql= 'INSERT INTO utilisateurs (login, password) VALUES (:login, :password)';
                    $insert_stmpdo=$this->db->prepare($sql);

                    if ($insert_stmpdo->execute(['login' => $login, 'password' => $hashpass])) {
                        echo "Vous êtes enregistré !";
                        //var_dump($insert_stmpdo);
                        //return[$insert_stmpdo];
                        header('Location:connexion.php');
                    }
                }    
            }
        }
    }

    public function connect() {

        $this->db_connect();
        $db = $this->db_connect();

        if (isset($_POST['connexion']))
        {
            $login = htmlspecialchars(trim($_POST['login']));
            $password = htmlspecialchars(trim($_POST['password']));

            $getpass = $this->db->prepare( 'SELECT password FROM utilisateurs WHERE login = :login');
            $getpass->execute(['login'=>$login]);
            $row = $getpass->fetch();
            //var_dump($row); //ca prend le mpd hash

            if (!$row) {
                echo 'utilisateur introuvable ou mot de passe incorrect';
            }
            else {
                $bddpass = $row['password'];
                if (password_verify($password, $bddpass)){
                    $data = $this->db->prepare('SELECT * FROM utilisateurs WHERE login = :login AND password = :password');
                    $data->execute(['login'=>$login, 'password'=>$bddpass]);

                    $user = $data->fetch(PDO::FETCH_ASSOC);
                    //var_dump($user);

                    if ($data->rowCount()) {
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['login'] = $user['login'];
                        $_SESSION['password'] = $user['password'];
                    }
                    header ('location:index.php');
                }
                else
                {
                    echo 'utilisateur introuvable ou mot de passe incorrect';
                }
            }
        }
    }

    public function updateProfil()
    {
        if (isset($_POST['update']))
        {

            // connexion à la bdd

            $this->db_connect();

            $id = $_SESSION['id'];

            // modifier son profil
            if (isset($_POST['update']))
            {

                $login = htmlspecialchars(trim($_POST['login']));
                $password = htmlspecialchars(trim($_POST['password']));
                $newpass = htmlspecialchars(trim($_POST['new_pass']));

                //on vérif si le login a changé et s'il est déjà prit dans la bdd
                // PROBLEME on ne peu pas changer le login seul !!!!!!!!!!!!!!!
                if (($_POST['login']) != ($_SESSION['login']))
                {
                    //on fait la requête dans la bd pour rechercher si ces données existent:
                    $sql= "SELECT * FROM `utilisateurs` WHERE `login`= :login";
                    $data = $this->db->prepare($sql);
                    $data->execute(['login'=>$login]);
                    $row = $data->fetch(PDO::FETCH_ASSOC);
                    var_dump($row); /// boolean false ???????????????????????
                    if(count($row['login']) >0)
                    {
                        echo 'login exist déjà';
                        exit;
                    }// fin login exist déjà
                }// fin isset changement du login

                //je verif si le password est bon:
                $sql= "SELECT password FROM `utilisateurs` WHERE id = $id";
                //je fait la requête pour le password qui correspont au login.
                $data = $this->db->prepare($sql);
                $data->execute(['id'=>$id]);
                //je vais créer un tableau avec mon résultat
                $row = $data->fetch(PDO::FETCH_ASSOC);
                //je transforme ma ligne password (ligne de la bdd) en variable
                $bddpass = $row['password'];

                if(password_verify($password, $bddpass)) //toujours dans cet ordre
                {
                    //on regarde s'il y a un nouveau mdp
                    if ((!empty($_POST['new_pass'])) || !empty($_POST['confirm_new_pass']))
                    {
                        //vérifier si le nouveau mdp est assez long
                        if (strlen($_POST["new_pass"]) <3)
                        {
                            echo 'pas assez de caractères pour le nouveau mdp';
                        }
                        //on vérifie si nouveaux mdp sont identiques par vérifier s'ils sont différents
                        else if($_POST["new_pass"] != $_POST["confirm_new_pass"] )
                        {
                            echo 'les nouveaux mots de passe sont différents';
                        }
                        else
                        {
                            //on sécurise le nouveau mdp
                            $hashpass = password_hash($newpass, PASSWORD_BCRYPT);
                            //on l'ajoute dans la bdd
                            $sql= "UPDATE `utilisateurs` SET `login`= '$login', password='$hashpass' WHERE `id` = '$id'";
                            $stm=$this->db->prepare($sql);
                            $stm->execute(['login'=>$login, 'password'=>$hashpass, 'id'=>$id]);
                            //on redéfini les session avec les nouvelles informations (si on ne fait pas ça les modificatoins ne seront pas visible sur le form)
                            $_SESSION['login'] = $login;
                            //s'assurer que la requ^te a marché, car pas de redirection avec header location
                            if ($stm) {
                                echo 'la modification a été prise en compte';
                            }
                            else {
                                echo 'la modification a échouée';
                            }
                        }
                    }
                }// fin password correct avec password verify
                else {
                    echo 'mdp actuel incorrect';
                }
            }
        }
    }

    public function makeReservation()
    {
        // connexion à la bdd
        $this->db_connect();
        // faire une reservation
        if (isset($_POST['submit']))
        {
            $title = htmlspecialchars(trim($_POST['title']));
            $description = htmlspecialchars(trim($_POST['text']));
            $timeStart = $_POST['timestart'];
            $timeEnd = $_POST['timeend'];
            $id = $_SESSION['id'];

            // vérifier que tous les champs soient remplis

            if (!isset($title)){
                echo 'Entrez un titre';
            }
            else if (!isset($description)) {
                echo 'Ecrivez un mot en description';
            }
            else if (strlen($description) <3) {
                echo 'La description doit faire au moins 3 charactères';
            }
            else if (!isset($timeStart)) {
                echo 'Entrez une date de début';
            }
            else if (!isset($timeEnd)) {
                echo 'Entrez une date de fin';
            }
            // maintenant tous les champs sont remplis
            else {

                $hourBegins_number = date_create($timeStart);
                $hourBegins = date_format($hourBegins_number ,'H'); // renvoi 15H00 pour ex
                $dayBegins = date_format($hourBegins_number ,'N'); // lundi 1, dimanche 7

                $hourEnds_number = date_create($timeEnd);
                $hourEnds = date_format($hourEnds_number ,'H');
                $dayEnds = date_format($hourEnds_number ,'N');

                //on fait la requête
                $sql= 'INSERT INTO `reservations`(`titre`, `description`, `debut`, `fin`, `id_utilisateur`) VALUES (:titre, :description, :debut, :fin, :id_utilisateur)';
                $stm=$this->db->prepare($sql);

                if ($stm->execute([
                    'titre' => $title,
                    'description' => $description,
                    'debut' => $timeStart,
                    'fin' => $timeEnd,
                    'id_utilisateur' => $id
                ])) {
                    echo 'Votre résevation est enregistrée';
                    //header('location:planning.php');
                }
            }
        }
    }
    public function reservation(){

            $date = new DateTime($Ddate);

            if($date->format('N') == 6 || $date->format('N') == 7){

                echo '<section class="alert alert-danger text-center" role="alert"><b>Attention !</b> Les réservations se font <b>uniquement du lundi au vendredi</b>.</section>';

            }
            else{

                $statement = $this->db->prepare("INSERT INTO reservations (titre, description, debut, fin, id_utilisateur) VALUES (:titre, :description, :debut, :fin, :id_utilisateur)");

                $statement->execute([
                    "titre"=>$titre,
                    "description"=>$description,
                    "debut"=>$Ddate,
                    "fin"=>$Fdate,
                    "id_utilisateur"=>$id_user
                ]);

                echo '<section class="alert alert-success text-center" role="alert"><b>Réservation éffectuée</b></section>';
            }
        }
    }


    public function showReservation()
    {
        // connexion à la bdd
        $this->db_connect();
        //transformation des session en variables
        $id = $_SESSION['id'];
        //Requête select l'ensemble des informations de l'utlisateur connecté
        $sql= "SELECT * FROM `reservations` WHERE `id_utilisateur` = :id_utilisateur";
        $stm=$this->db->prepare($sql);
        $stm->bindParam(':id_utilisateur', $id);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function showTitleReservation()
    {
        // connexion à la bdd
        $this->db_connect();
        //Requête select le nom des titres
        $sql= "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='reservations'";
        $stm=$this->db->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*public function slot()
    {
        $id = $_SESSION['id'];
        $login = $_SESSION['login'];

        //array_search pourrait être utiliser pour savoir si l'user à bien choisi un jour de la semaine
        $day = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi');
        $timeslot = array('08-09', '09-10', '10-11', '11-12', '12-13', '13-14', '14-15', '15-16', '16-17', '17-18', '18-19');

        else
            echo 'Vous pouvez réserver la salle du lundi au vendredi, de 8h00 à 19hOO pour une durée de 1H.';
    }*/

}
?>