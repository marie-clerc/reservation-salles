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

    public function checklogin($login) {
        $stm = $this->db -> prepare("SELECT login FROM utilisateurs WHERE login =:login");
        $stm -> execute(["login"=>$login]);
        $result = $stm->fetch();
        return $result;
    }

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
                if (($_POST['login']) != ($_SESSION['login']) && (empty($_POST['password'])) && (empty($_POST['new_pass'])) && (empty($_POST['confirm_new_pass'])))
                {
                    $checklogin = $this->checklogin($login);
                    if ($checklogin)
                    {
                        echo 'login exist déjà. <a href="profil.php">Profil</a> ';
                        exit;
                    }// fin login exist déjà
                    else {
                        $sql= "UPDATE `utilisateurs` SET `login`= :login WHERE `id` = :id";
                        $stm=$this->db->prepare($sql);
                        $stm->execute(['login'=>$login,  'id'=>$id]);
                        //on redéfini les session avec les nouvelles informations (si on ne fait pas ça les modificatoins ne seront pas visible sur le form)
                        $_SESSION['login'] = $login;
                        //s'assurer que la requ^te a marché, car pas de redirection avec header location
                        if ($stm) {
                            echo 'la modification du login a été prise en compte. <a href="profil.php">Profil</a> ';
                            exit();
                        }
                        else {
                            echo 'la modification du login a échouée. <a href="profil.php">Profil</a> ';
                            exit();
                        }
                    }
                }// fin isset changement du login

                //je verif si le password est bon:
                $sql= "SELECT password FROM `utilisateurs` WHERE id = :id";
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
                            $sql= "UPDATE `utilisateurs` SET `login`= :login, password= :password WHERE `id` = :id";
                            $stm=$this->db->prepare($sql);
                            $stm->execute(['login'=>$login, 'password'=>$hashpass, 'id'=>$id]);
                            //on redéfini les session avec les nouvelles informations (si on ne fait pas ça les modificatoins ne seront pas visible sur le form)
                            $_SESSION['login'] = $login;
                            //s'assurer que la requ^te a marché, car pas de redirection avec header location
                            if ($stm) {
                                echo 'la modification a été prise en compte. <a href="profil.php">Profil</a> ';
                                exit();
                            }
                            else {
                                echo 'la modification a échouée. <a href="profil.php">Profil</a> ';
                                exit();
                            }
                        }
                    }
                }// fin password correct avec password verify
                if (($_POST['login']) != ($_SESSION['login']) && (!empty($_POST['password']))) {
                    echo 'vous n avez pas besoin de votre mot de passe pour changer de login seulement. <a href="profil.php">Profil</a> ';
                    exit();
                }
                else {
                    echo 'mdp actuel incorrect. <a href="profil.php">Profil</a> ';
                    exit();
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

            if (empty($title)){
                echo 'Entrez un titre';
            }
            else if (empty($description)) {
                echo 'Ecrivez un mot en description';
            }
            else if (strlen($description) <3) {
                echo 'La description doit faire au moins 3 charactères';
            }
            else if (empty($timeStart)) {
                echo 'Entrez une date de début';
            }
            else if (empty($timeEnd)) {
                echo 'Entrez une date de fin';
            }
            // maintenant tous les champs sont remplis
            else {
                //
                $debutHeure = date_create($timeStart);
                $heure_debut = date_format($debutHeure ,'G'); // Heure, au format 24h, sans les zéros initiaux
                $jour_debut = date_format($debutHeure ,'N'); // 1 (pour Lundi) à 7 (pour Dimanche)

                $finHeure = date_create($timeEnd);
                $heure_fin = date_format($finHeure ,'G');
                $jour_fin = date_format($finHeure ,'N');

                $interval = $heure_fin - $heure_debut ;

                //on verif si le creneau horaire est disponible
                $sqlcreneau = 'SELECT COUNT(*) FROM reservations WHERE debut = :datedebut OR fin = :datefin'; //Pour connaître le nombre de lignes totales dans une table, il suffit d’effectuer la requête SQL ci contre
                $stmcreneau = $this->db->prepare($sqlcreneau);

                $stmcreneau->execute([
                    'datedebut'=> $timeStart,
                    'datefin' => $timeEnd
                ]);

                $creneauPris = $stmcreneau->fetchColumn();
                //

                if($creneauPris == 0 && $interval == 1 && $jour_debut == $jour_fin && $jour_debut > 0 && $jour_debut < 6 && $heure_debut >= 8 && $heure_debut <= 18 && $heure_fin >= 9 && $heure_fin <= 19 && $heure_fin > $heure_debut )
                {
                    //on fait la requête
                    $sql = 'INSERT INTO `reservations`(`titre`, `description`, `debut`, `fin`, `id_utilisateur`) VALUES (:titre, :description, :debut, :fin, :id_utilisateur)';
                    $stm = $this->db->prepare($sql);
                    if ($stm->execute([
                        'titre' => $title,
                        'description' => $description,
                        'debut' => $timeStart,
                        'fin' => $timeEnd,
                        'id_utilisateur' => $id
                    ])) {
                        echo 'Votre résevation est enregistrée. Vérifiez le planning ici : <a href="planning.php">Planning</a>';
                        //header('location:planning.php');
                    }
                }
                elseif ($interval > 1 || $jour_debut != $jour_fin || $jour_debut == 6 OR $jour_debut == 7 || $heure_debut < 8 OR $heure_debut > 18 OR $heure_fin < 9 OR $heure_fin > 19 || $heure_fin <= $heure_debut) {
                    echo 'Les reservations se font du lundi au vendredi de 8h à 19h pour une durée de 1h. Réservez plusieurs fois si necessaire. Vérifiez le planning ici : <a href="planning.php">Planning</a>';
                }
                elseif ( $creneauPris > 0) {
                    echo 'Créneau horaire indisponible. Vérifiez le planning ici : <a href="planning.php">Planning</a>';
                }
            }
        }
    }

    public function showReservation()
    {
        // connexion à la bdd
        $this->db_connect();
        //transformation des session en variables
        $id = $_GET['id'];
        //Requête select l'ensemble des informations de l'utlisateur connecté
        $sql= "SELECT * FROM `reservations` WHERE `id` = :id";
        $stm=$this->db->prepare($sql);
        $stm->execute(['id'=>$id]);
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

    public function planning($jour,$heure)
    {
        $this->db_connect();
        $sql ='SELECT login,reservations.id,titre,description,debut,fin,id_utilisateur FROM utilisateurs INNER JOIN reservations ON utilisateurs.id = reservations.id_utilisateur WHERE DATE_FORMAT(debut, "%w") = :jour AND DATE_FORMAT(debut, "%k") = :heure ';
        $stm = $this->db->prepare($sql);
        $stm->execute(['jour'=>$jour, 'heure'=>$heure]);
        $result = $stm->fetch();
        //var_dump($result);
        if($result)
        {
            $_GET['id'] = @$result['id'];
            //var_dump($_GET);
            echo '<td><a href="reservation.php?id='.$_GET['id'].'">'.$result['login'].'<br>'.$result['titre'].'</a></td>';
        }
        else{
            echo '<td>Crénaux disponible</td>' ;
        }
    }

}
?>