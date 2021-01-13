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
    header('location: connexion.php');
}
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

<table>
    <thead>
    <tr>
        <td>CRENEAUX</td>
        <td>LUNDI</td>
        <td>MARDI</td>
        <td>MERCREDI</td>
        <td>JEUDI</td>
        <td>VENDREDI</td>
    </tr>
    </thead>
    <tbody>
    <?php

    for ($slot = 8; $slot <= 19; $slot++)
    {
        echo '<tr></tr>';
        for ($jour = 0; $jour= 5; $jour++) {
            '<td></td>';
        }

    }

    %w
    if w = 6 ou 0 ne pas prendre car c'est seaedi et diamnce'
    https://www.php.net/manual/fr/datetime.format.php


    ?>
    </tbody>
</table>
<div class="signup_link">
    <a href="inscription.php">Inscription</a> <br>
    <a href="connexion.php">Connexion</a> <br>
    <a href="index.php">Accueil</a>
</div>

</body>
</html>