<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Résevation</title>
</head>
<?php
session_start();
require('classes/user-pdo.php');

$user = new user(NULL, NULL);

// connexion user
$user->connect();
?>
<body>
<!--Une page permettant de voir le planning de la salle
Sur cette page on voit le planning de la semaine avec l’ensemble des
réservations effectuées. Le planning se présente sous la forme d’un
tableau avec les jours de la semaine en cours. Dans ce tableau, il y a en
colonne les jours et les horaires en ligne. Sur chaque réservation, il est
écrit le nom de la personne ayant réservé la salle ainsi que le titre. Si un
utilisateur clique sur une réservation, il est amené sur une page dédiée.

Les réservations se font du lundi au vendredi et de 8h et 19h. Les créneaux
ont une durée fixe d’une heure.-->

<div class="reservation">
    <h1>Planning: </h1>

    <table>
        <thead>
        <tr>
            <th></th>
            <th>Lundi</th>
            <th>Mardi</th>
            <th>Mercredi</th>
            <th>Jeudi</th>
            <th>Vendredi</th>
        </tr>
        </thead>
        <tbody>
            <?php
            for($heure = 8 ; $heure <= 19 ; $heure++)
            {
                echo '<tr></tr>';
                for($jour = 0 ; $jour <= 5 ; $jour++)
                {
                    if($jour == 0)
                    {
                        echo '<th>' .$heure.'h </th>';
                    }
                    else{
                        $user->planning($jour,$heure);
                    }
                }
            }
            ?>
        </tbody>
    </table>

    <div class="signup_link">
        <a href="index.php">Accueil</a>
    </div>
</div>
</body>
</html>