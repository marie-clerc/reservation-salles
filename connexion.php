<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Réservation de Salles</title>
</head>
<?php
    // ouverture de la session
    session_start();

    require('classes/user-pdo.php');

    $user = new user(NULL, NULL);

    // connecxion user
    $user->connect();

?>
<body>
    <!--Le formulaire doit avoir deux inputs : “login” et “password”. Lorsque le
    formulaire est validé, s’il existe un utilisateur en bdd correspondant à ces
    informations, alors l’utilisateur devient connecté et une (ou plusieurs)
    variables de session sont créées.-->

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


    <!--formulaire de connexion-->
    <div class="center">
        <h1>Connexion</h1>

        <form action="connexion.php" method="post">

            <div class="txt_field">
                <input type="text" name="login" autofocus required>
                <span></span>
                <label for="login">Login</label>
            </div>

            <div class="txt_field">
                <input type="password" name="password" required>
                <span></span>
                <label for="password">Mot de passe</label>
            </div>

            <input type="submit" name="connexion" value="Connexion"> <!--mon bouton connexion-->

            <div class="signup_link">
                Pas encore membre? <a href="inscription.php">S'inscrire</a> <br>
                <a href="index.php">Accueil</a>
            </div>

        </form>
    </div>
</body>
</html>