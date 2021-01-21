<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Résevation</title>
</head>
<?php
// ouverture de la session
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirige-le vers la page de connexion
if (isset($_SESSION['id'])) {

    require('classes/user-pdo.php');

    $user = new user(NULL, NULL);

    // connexion user
    $user->connect();

}
else {
    header ('location: connexion.php');
}


?>
<body>
    <!--Une page permettant de voir une réservation
    Cette page affiche le nom du créateur, le titre de l’événement, la
    description, l’heure de début et de fin. Pour savoir quel évènement afficher,
    vous devez récupérer l’id de l’événement en utilisant la méthode get. (ex :
    http://localhost/reservationsalles/evenement/?id=1) Seuls les personnes
    connectées peuvent accéder aux événements.-->


    <header>
        <h1>Salle de conférence</h1>
        <section>
            <a href="index.php">Accueil</a>
            <a href="reservation-form.php">Réservez</a>
            <a href="planning.php">Planning</a>
            <?php //si l'utilisateur est connecté
            if (isset($_SESSION['login']))
                // echo les liens necessaire
                echo ('<a href="profil.php">Profil</a></section>
                    <form action="index.php" method="post"><input type="submit" name="deconnexion" id="logout" value="Déconnexion"/></form>');
            //si l'utilisateur est connecté
            else if (!isset($_SESSION['login']))
                // echo les liens necessaire
                echo ('<a href="inscription.php">Inscription</a>
                    <a href="connexion.php">Connexion</a></section>');
            ?>
    </header>


    <!--Affichage des résevations-->
    <div class="reservation">
        <h1>La réservation: </h1>

        <table>
            <thead>
            <tr>
                <?php
                $nomchamp = $user->showTitleReservation();

                //aficher tous les champs de la table
                foreach ($nomchamp as $result)
                {
                    foreach ($result as $key => $value) {
                        echo '<td>' .$value.'</td>';
                    }
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $all_result = $user->showReservation();
            foreach ($all_result as $result)
            {
                echo '<tr>';
                foreach ($result as $key => $value) {
                    echo '<td>' .$value.'</td>';
                }
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
        <div class="signup_link">
            <a href="planning.php">Planning</a> <br>
            <a href="index.php">Accueil</a>
        </div>
    </div>

</body>
</html>