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
    $user->makeReservation();

}
else {
    header('location: connexion.php');
}
?>
<body>
<!--Un formulaire de réservation de salle
Ce formulaire contient les informations suivantes : titre, description, date de
début, date de fin.-->

<!--formulaire de résevation-->
<div class="center">
    <h1>Réservez la Salle</h1>

    <form action="reservation-form.php" method="post">

        <div class="txt_field">
            <input type="text" name="title" placeholder="titre de votre réservation" autofocus required>
            <span></span>
            <label for="title">Titre</label>
        </div>

        <div class="txt_field">
            <input hidden><textarea rows="2" name="text" placeholder="entrez votre description" required></textarea>
            <span></span>
            <label for="text">Description</label>
        </div>


        <div class="txt_field">
            <input type="datetime-local" name="timestart" step="3600">
            <span></span>
            <label for="timestart">Réservez de :</label>
        </div>

        <div class="txt_field">
            <input type="datetime-local" name="timeend" step="3600">
            <span></span>
            <label for="timeend">à :</label>
        </div>



        <input type="submit" name="submit" value="Validez"> <!--mon bouton submit-->

        <div class="signup_link">
            <a href="index.php">Accueil</a>
        </div>

    </form>
</div>
</body>
</html>